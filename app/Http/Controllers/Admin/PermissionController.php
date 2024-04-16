<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as Controller;
use Exception;
use Illuminate\Database\QueryException;

class PermissionController extends Controller
{
    protected $title = 'Permission';
    protected $modelClass = Permission::class;
    protected $alias = 'p';
    protected $descKey = 'name';
    protected $column = ['p.*', 'gp.name AS group'];
    protected $view = 'backend.admin.partials.permission';
    protected $useDefaultEditView = true;
    protected $urlRedirect = 'permission';
    protected $searchColumn = ['nama' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'p.name'];

    protected $selectFind = ['name'];
    protected $selectColumn = ['id', 'name As nama'];

    protected function optionalQuery($db)
    {
        return $db->leftJoin('permissions_group AS gp', 'gp.id', 'p.group_id')
            ->when(request()->group, function ($query) {
                $query->where('gp.id', decodeId(request()->group));
            })
            ->orderBy('gp.name')
            ->orderBy('p.name');
    }

    protected function moreDataIndex()
    {
        return [
            'group' => PermissionGroup::findOrNull(decodeId(request()->group)),
        ];
    }

    protected function moreDataTambah()
    {
        return [
            'defaultPermission' => Permission::getDefaultPermission(),
        ];
    }

    public function store()
    {
        try {
            DB::beginTransaction();

            $input = request()->input($this->getInput());

            foreach ($input['name'] as $key => $value) {
                $dataWhere = [
                    'name' => $value,
                ];
                $dataInput = [
                    'group_id' => $input['group_id'],
                    'description' => $input['description'][$key],
                    'guard_name' => 'web',
                ];
                Permission::updateOrCreate($dataWhere, $dataInput);

                logHistory([
                    'nama_proses' => 'UPDATE OR CREATE DATA',
                    'ket_proses' => 'TABEL ' . $this->getTableName(),
                    'data_proses' => json_encode([$dataWhere, $dataInput]),
                ]);
            }

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Ditambahkan',
                    'next' => url($this->getUrlToRedirect())
                ]);
            } else {
                return redirect($this->getUrlToRedirect())->with([
                    'success' => true,
                    'message' => 'Data Berhasil Ditambahkan',
                ]);
            }
        } catch (Exception $ex) {
            DB::rollback();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'errorMessage' => errorMessage($ex),
                    'next' => url($this->getUrlToRedirect())
                ]);
            } else {
                return redirect($this->getUrlToRedirect())->with([
                    'success' => false,
                    'errorMessage' => errorMessage($ex),
                ]);
            }
        }
    }

    protected function moreDataEdit($result)
    {
        return [
            'group' => $result->group,
        ];
    }

    public function deleteAll()
    {
        $ids = request()->ids;

        $status = false;
        try {
            DB::beginTransaction();

            foreach ($ids as $value) {
                $id = decodeId($value);

                $oldData = DB::table($this->getTableName())
                    ->where($this->getTableIdentifier(), $id)
                    ->first();

                $this->beforeSaveDelete($oldData);

                DB::table($this->getTableName())
                    ->where($this->getTableIdentifier(), $id)
                    ->delete();

                logHistory([
                    'nama_proses' => 'DELETE DATA',
                    'ket_proses' => 'TABEL ' . $this->getTableName(),
                    'data_proses' => json_encode($oldData),
                ]);
            }

            DB::commit();

            $status = true;
        } catch (QueryException $ex) {
            DB::rollback();

            $status = false;

            // constraint violation
            if ($ex->errorInfo[1] == '1451') {
                $message = 'Tidak bisa menghapus data yang sedang digunakan oleh data lain.';
            } else {
                $message = $ex->errorInfo[2];
            }
        } catch (Exception $ex) {
            DB::rollback();

            $status = false;
            $message = "{$ex->getMessage()}\n{$ex->getLine()} on {$ex->getFile()}";
        }

        return [
            'success' => $status,
            'message' => $message ?? '',
        ];
    }

    public function permission()
    {
        $data = Permission::where('group_id', request()->group)
            ->select(['name', 'description'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
