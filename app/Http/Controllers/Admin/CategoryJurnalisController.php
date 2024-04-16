<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CategoryJurnalisRequest;
use App\Models\Category;
use App\Models\User;
use App\Models\UserCategory;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class CategoryJurnalisController extends BaseController
{
    protected $title = 'Kategori Jurnalis';
    protected $modelClass = User::class;
    protected $alias = 'p';
    protected $descKey = 'user_name';
    protected $column = ['p.*'];
    protected $view = 'backend.admin.partials.kategori-jurnalis';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $urlRedirect = 'kategori-jurnalis';
    protected $searchColumn = ['nama' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'user_name'];

    protected $selectFind = ['user_id', 'user_name'];
    protected $selectColumn = ['user_id as id', 'user_name as nama'];

    protected $sortColumn = ['nama'];
    protected $sortColumnField = ['nama' => 'user_name'];

    protected $permissionName = "kategori-jurnalis";

    protected $formRequest = CategoryJurnalisRequest::class;

    protected function optionalQuery($db)
    {
        $db->join('user_category as uc', 'uc.user_id', '=', 'p.user_id')
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

            foreach ($input['categories'] as $category) {
                $inputInsert = [
                    'user_id' => $input['user_id'],
                    'category_id' => $category,
                ];

                UserCategory::insert($inputInsert);
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
        } catch (Exception $ex) {
            DB::rollback();

            if (is_a($ex, HttpResponseException::class)) throw $ex;

            return redirect($this->getUrlToRedirect())->with([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }

    protected function moreDataEdit($result)
    {
        $categories = UserCategory::where('user_id', $result->user_id)->get();

        return [
            'categories' => $categories,
        ];
    }

    public function update($id)
    {
        $redirectParams = json_decode(base64_decode(request()->redirect_params));
        $urlRedirectParams = !empty($redirectParams) ? Arr::query($redirectParams) : '';

        try {
            DB::beginTransaction();

            $id = $this->getIdParemeter($id);

            $this->alias = null;
            $oldData = $this->dbBuilder()
                ->where($this->getTableIdentifier(), $id)
                ->first();
            if (empty($oldData)) abort(404);

            if (!empty($this->formRequest)) {
                $request = app()->makeWith($this->formRequest, ['input' => $this->getInput(), 'file' => $this->getInputFile(), 'model' => $oldData]);

                if (!$request->getValidator()->fails())
                    $input = request()->input($this->getInput());
                else
                    throw new \Exception($request->getValidator()->errors());
            } else {
                $input = request()->input($this->getInput());
            }

            UserCategory::where('user_id', $oldData->user_id)->delete();

            foreach ($input['categories'] as $category) {
                $inputInsert = [
                    'user_id' => $input['user_id'],
                    'category_id' => $category,
                ];

                UserCategory::insert($inputInsert);
            }

            logHistory([
                'nama_proses' => 'UPDATE DATA',
                'ket_proses' => 'TABEL user_category',
                'data_proses' => json_encode([
                    'old' => $oldData,
                    'new' => $input
                ]),
            ]);

            DB::commit();

            return redirect($this->getUrlToRedirect() . exist($urlRedirectParams, prefix: '?'))->with([
                'success' => true,
                'message' => 'Perubahan Berhasil Disimpan.',
            ]);
        } catch (Exception $ex) {
            DB::rollback();

            if (is_a($ex, HttpResponseException::class)) throw $ex;

            return redirect($this->getUrlToRedirect() . exist($urlRedirectParams, prefix: '?'))->with([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }

    public function destroy($id)
    {
        $id = $this->getIdParemeter($id);

        try {
            DB::beginTransaction();

            $oldData = DB::table($this->getTableName())
                ->where($this->getTableIdentifier(), $id)
                ->first();

            UserCategory::where('user_id', $oldData->user_id)->delete();

            logHistory([
                'nama_proses' => 'DELETE DATA',
                'ket_proses' => 'TABEL ' . $this->getTableName(),
                'data_proses' => json_encode($oldData),
            ]);

            DB::commit();

            $response = [
                'success' => true,
                'message' => ucfirst(exist(request()->message, 'Data berhasil dihapus.', suffix: ' berhasil dihapus.')),
            ];

            foreach ($response as $key => $value) {
                session()->flash($key, $value);
            }

            return response()->json($response);
        } catch (QueryException $ex) {
            DB::rollback();

            // constraint violation
            if ($ex->errorInfo[1] == '1451') {
                $message = 'Tidak bisa menghapus data yang sedang digunakan oleh data lain.';
            } else {
                $message = $ex->errorInfo[2];
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'errorMessage' => errorMessage($ex),
            ], 500);
        } catch (Exception $ex) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ], 500);
        }
    }

    public function getSelect()
    {
        try {
            $db = UserCategory::where('user_id', auth()->user()->user_id)
                ->join('category as c', 'c.category_id', '=', 'user_category.category_id');

            if ($db->count() == 0) {
                $db = Category::where('category_active', 1);
                $this->selectColumn = ['category_id as id', 'category_name_id as nama', 'category_slug_id as slug'];
            } else {
                $this->selectColumn = ['user_category.category_id as id', 'category_name_id as nama'];
            }

            if (request()->q != '') {
                $db->where(function ($where) {
                    $sWhere = $this->getSelectFind();
                    foreach ($sWhere as $key => $value) {
                        if (is_numeric($key)) {
                            $where->orWhere($value, 'LIKE', '%' . request()->q . '%');
                        } else if (strtolower($value) == 'raw') {
                            $where->orWhere(DB::raw($key), 'LIKE', '%' . request()->q . '%');
                        }
                    }
                });
            }

            if (!empty($this->getSelectColumn())) {
                $select = collect($this->getSelectColumn());
                [$normalSelect, $rawSelect] = $select->partition(fn ($item, $idx) => is_numeric($idx));
                $rawSelect = $rawSelect->filter(fn ($item, $idx) => strtolower($item) == 'raw')
                    ->map(fn ($item, $idx) => DB::raw($idx));
                $db->select($normalSelect->merge($rawSelect)->toArray());
            }

            $results = $db->paginate($this->getSelectLimit());

            if (!empty(request()->hash_id)) {
                $items = $results->getCollection()->map(function ($item) {
                    $item = toArray($item);
                    $item[request()->hash_id] = Hashids::encode($item[request()->hash_id]);
                    return $item;
                });
                $results->setCollection($items);
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendapatkan data.',
                'data' => $results,
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ], 500);
        }
    }
}
