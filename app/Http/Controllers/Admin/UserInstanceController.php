<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserInstanceRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\BaseController as Controller;

class UserInstanceController extends Controller
{
    protected $title = 'Kategori Instansi Pengguna';
    protected $modelClass = User::class;
    protected $alias = 'p';
    protected $descKey = 'user_name';
    protected $column = ['p.*'];
    protected $view = 'backend.admin.partials.user-instance';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $urlRedirect = 'user-instance';
    protected $searchColumn = ['nama' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'user_name'];

    protected $selectFind = ['user_id', 'user_name'];
    protected $selectColumn = ['user_id as id', 'user_name as nama'];

    protected $sortColumn = ['nama'];
    protected $sortColumnField = ['nama' => 'user_name'];

    protected $permissionName = "user-instance";

    protected $formRequest = UserInstanceRequest::class;

    protected function optionalQuery($db)
    {
        $db->join('user_instance as ui', 'ui.user_id', '=', 'p.user_id')
            ->distinct();

        return $db;
    }

    public function store()
    {
        try {
            DB::beginTransaction();

            if (!empty($this->formRequest)) {
                $request = app()->makeWith($this->formRequest, ['input' => $this->getInput(), 'file' => $this->getInputFile()]);
                if (!$request->getValidator()->fails())
                    $input = request()->input($this->getInput());
                else
                    throw new \Exception($request->getValidator()->errors());
            } else {
                $input = request()->input($this->getInput());
            }

            foreach ($input['instances'] as $instance) {
                $data = [
                    'user_id' => $input['user_id'],
                    'instance_id' => $instance
                ];

                $this->modelClass::find($input['user_id'])->userInstances()->create($data);
            }

            logHistory([
                'nama_proses' => 'INSERT DATA',
                'ket_proses' => 'TABEL user_category',
                'data_proses' => json_encode($input),
            ]);

            DB::commit();

            return redirect($this->getUrlToRedirect())->with([
                'success' => true,
                'message' => 'Data Berhasil Ditambahkan.',
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();

            if (is_a($ex, HttpResponseException::class)) throw $ex;

            return redirect($this->getUrlToRedirect())->with([
                'success' => false,
                'message' => 'Data Gagal Ditambahkan.',
            ]);
        }
    }

    protected function moreDataEdit($result)
    {
        return [
            'instances' => $result->instances()->get(),
        ];
    }

    public function update($id)
    {
        try {
            DB::beginTransaction();

            $id = $this->getIdParemeter($id);

            $this->alias = null;
            $oldData = $this->dbBuilder()
                ->where($this->getTableIdentifier(), $id)
                ->first();
            if (empty($oldData)) abort(404);

            if (!empty($this->formRequest)) {
                $request = app()->makeWith($this->formRequest, ['input' => $this->getInput(), 'file' => $this->getInputFile()]);
                if (!$request->getValidator()->fails())
                    $input = request()->input($this->getInput());
                else
                    throw new \Exception($request->getValidator()->errors());
            } else {
                $input = request()->input($this->getInput());
            }

            $user = $this->modelClass::find($input['user_id']);

            foreach ($input['instances'] as $instance) {
                $data = [
                    'user_id' => $input['user_id'],
                    'instance_id' => $instance
                ];

                $user->userInstances()->updateOrCreate(['instance_id' => $instance], $data);
            }

            logHistory([
                'nama_proses' => 'UPDATE DATA',
                'ket_proses' => 'TABEL user_category',
                'data_proses' => json_encode($input),
            ]);

            DB::commit();

            return redirect($this->getUrlToRedirect())->with([
                'success' => true,
                'message' => 'Data Berhasil Diubah.',
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();

            if (is_a($ex, HttpResponseException::class)) throw $ex;

            return redirect($this->getUrlToRedirect())->with([
                'success' => false,
                'message' => 'Data Gagal Diubah.',
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $this->modelClass::find($id)->userInstance()->delete();

            logHistory([
                'nama_proses' => 'DELETE DATA',
                'ket_proses' => 'TABEL user_category',
                'data_proses' => json_encode(['user_id' => $id]),
            ]);

            DB::commit();

            return redirect($this->getUrlToRedirect())->with([
                'success' => true,
                'message' => 'Data Berhasil Dihapus.',
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();

            if (is_a($ex, HttpResponseException::class)) throw $ex;

            return redirect($this->getUrlToRedirect())->with([
                'success' => false,
                'message' => 'Data Gagal Dihapus.',
            ]);
        }
    }
}
