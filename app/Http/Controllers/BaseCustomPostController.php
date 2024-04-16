<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseCustomPostController extends Controller
{
    // Title => String; required
    protected $title = '';

    // Folder View => String; nullable
    protected $view = null;

     // Type Custom Post
     protected $type = null;
     protected $customPostType = null;

    // Default Folder View => String; required
    private $defaultView = 'backend.partials.default';

    // List View => String; required if using action index
    protected $listView = 'index';
    // Add View => String; required if using action create
    protected $addView = 'tambah';
    // Edit View => String; required if using action edit
    protected $editView = 'ubah';
    // Edit View => String; required if using action show
    protected $showView = 'detail';
    // Edit View => String; required if using action show
    protected $showViewAjax = 'detail-ajax';
    // Form View => String; required if using action create and edit
    protected $formView = 'form';

    // Using Default Folder for View => Boolean; required
    protected $useDefaultListView = false;
    // Using Default Folder for View => Boolean; required
    protected $useDefaultAddView = false;
    // Using Default Folder for View => Boolean; required
    protected $useDefaultEditView = false;
    // Using Default Folder for View => Boolean; required
    protected $useDefaultShowView = false;
    // Using Default Folder for View => Boolean; required
    protected $useDefaultFormView = false;

    // Upload Form on View => Boolean; required
    protected $addViewUpload = false;
    // Upload Form on View => Boolean; required
    protected $editViewUpload = false;

    // Redirect => String; required
    protected $urlRedirect = 'testing';
    // Form Input Name => String; required
    protected $input = 'input';
    // Form Input Name for File Upload => String; required
    protected $inputFile = 'file';

    // Eloquent Model => String; nullable; required if using Eloquent Model
    protected $modelClass = null;
    // Table Name => String; nullable; required if not using Eloquent Model
    protected $table = null;
    // Table Primary Key => String; nullable; required if not using Eloquent Model
    protected $primaryKey = null;
    // Description Field for Table => String; nullable
    protected $descKey = null;
    // Table Alias => String; nullable
    protected $alias = null;

    // Eloquent Model => String; nullable; required if using Eloquent Model
    protected $parentModelClass = null;
    // Table Name => String; nullable; required if not using Eloquent Model
    protected $parentTable = null;
    // Table Primary Key => String; nullable; required if not using Eloquent Model
    protected $parentPrimaryKey = null;

    // Find Searching Column => Array<String>; ex: ['id', 'name']
    protected $find = [];
    // Column From Table => Array<String>; ex: ['id', 'name']
    protected $column = [];
    // Data Per Page => Integer; required
    protected $limit = 20;

    // Search Column => Array<Integer, String> or Array<String, String>; optional
    // ex: ['desc' => '%LIKE%', 'name']
    // 'desc' and 'name' will be request form name
    protected $searchColumn = [];
    // Field Name for Search Column => Array<Integer, String> or Array<String, String>; optional
    // ex: ['desc' => 'description', 'name' => 'fullname']
    // 'desc' and 'name' will be request form name and 'description' and 'fullname' will be the real field name of table in database
    protected $searchColumnField = [];
    // Sort By Column => Array; optional
    // ex: ['desc', 'name']
    protected $sortColumn = [];
    // Field Name for Sort By => Array<String, String>; optional
    // ex: ['desc' => 'description', 'name' => 'fullname']
    // 'desc' and 'name' will be request form name and 'description' and 'fullname' will be the real field name of table in database
    protected $sortColumnField = [];

    /* Get Data */
    // Find Searching Column => Array<String>; ex: ['id', 'name']
    protected $selectFind = [];
    // Column From Table => Array<String>; ex: ['id', 'name']
    protected $selectColumn = [];
    // Data Per Page => Integer; required
    protected $selectLimit = 20;

    protected $usePermission = true;
    // Permission to Access Action => Array<String, String>; optional
    // ex: ['lihat' => 'index', 'tambah' => 'create,store']
    protected $permissions = ['lihat' => 'index', 'tambah' => 'create,store', 'ubah' => 'edit,update', 'hapus' => 'destroy', 'rincian' => 'show'];
    // Permission Name => String; nullable
    protected $permissionName = null;

    protected $usePermit = false;
    // Permits => Array<String>; optional
    protected $permits = [];

    // Use UUIDv4 When Insert/Create => Boolean;
    protected $useUUID = false;

    // created_at Field => String; nullable
    protected $createdAt = 'created_at';
    // updated_at Field => String; nullable
    protected $updatedAt = 'updated_at';

    // Form Request
    protected $formRequest = null;

    protected function dbBuilder()
    {
        if ($this->useModel()) {
            $db = app($this->modelClass);
            if (!empty($this->getAlias())) {
                return $db->from("{$db->getTable()} AS {$this->getAlias()}");
            }
            return $db->from($db->getTable());
        }

        if (!empty($this->getAlias())) {
            return DB::table("{$this->getTableName()} AS {$this->getAlias()}");
        } else {
            return DB::table($this->getTableName());
        }
    }

    protected function optionalQuery($db)
    {
        return $db;
    }

    protected function optionalQuery2($db)
    {
        return $db;
    }

    protected function buildQuery($db)
    {
        // search column using request parameter q
        if (request()->q != '') {
            $db->where(function ($where) {
                $iWhere = $this->getFind();
                foreach ($iWhere as $value) {
                    $where->orWhere($value, 'LIKE', '%' . request()->q . '%');
                }
            });
        }

        // individual search column
        foreach ($this->searchColumn as $key => $value) {
            if (
                (!is_int($key) && request()->input($key) != '') ||
                (is_int($key) && request()->input($value) != '')
            ) {
                if (is_int($key)) {
                    $field = $this->searchColumnField[$value] ?? $value;
                    $db->where($field, '=', request()->input($value));
                } else {
                    $operator = (str_contains($value, '%') ? str_replace('%', '', $value) : $value);
                    $search = (str_contains($value, 'LIKE') ? str_replace('LIKE', request()->input($key), $value) : request()->input($key)
                    );
                    $field = $this->searchColumnField[$key] ?? $key;
                    $db->where($field, $operator, $search);
                }
            }
        }

        if (!empty(request()->sort_by) && in_array(request()->sort_by, $this->sortColumn)) {
            $sortBy = $this->sortColumnField[request()->sort_by] ?? request()->sort_by;
            $db->orderBy($sortBy, request()->sort_type ?? 'ASC');
        }

        if (!empty($this->getColumn())) {
            $db->select($this->getColumn());
        }

        return $db;
    }

    protected function moreDataIndex($parent_id)
    {
        return [];
    }

    public function index($parent_id)
    {
        $parent_id = $this->getIdParemeter($parent_id);

        $db = $this->dbBuilder();

        $db = $this->optionalQuery($db);
        $db = $this->buildQuery($db);
        $db = $this->optionalQuery2($db);

        $db->where($this->parentPrimaryKey, $parent_id);

        $results = empty($this->getLimit()) ? $db->get() : $db->paginate($this->getLimit())->withQueryString();

        return view(
            $this->getListView(),
            [
                'results' => $results,
                'descKey' => $this->getTableDesc(),
                'more' => $this->moreDataIndex($parent_id),
            ]
        );
    }

    protected function moreDataForm()
    {
        return [];
    }

    protected function moreDataTambah($parent_id)
    {
        return [];
    }

    public function create($parent_id)
    {
        $parent_id = $this->getIdParemeter($parent_id);

        return view(
            $this->getAddView(),
            [
                'more' => array_merge($this->moreDataForm(), $this->moreDataTambah($parent_id)),
                'form' => $this->getFormView(),
                'upload' => $this->getAddViewUpload(),
            ]
        );
    }

    protected function moreDataEdit($result, $parent_id)
    {
        return [];
    }

    protected function optionalDataEdit($db, $id)
    {
        return $db;
    }

    private function dataEdit($id)
    {
        $db = $this->dbBuilder();
        $db = $this->optionalDataEdit($db, $id);

        if (!empty($this->getAlias())) {
            $db->where("{$this->getAlias()}.{$this->getTableIdentifier()}", $id);
        } else {
            $db->where("{$this->getTableName()}.{$this->getTableIdentifier()}", $id);
        }

        $result = $db->first();
        if (empty($result)) abort(404);

        return $result;
    }

    public function edit($parent_id, $id)
    {
        $id = $this->getIdParemeter($id);
        $parent_id = $this->getIdParemeter($parent_id);

        $result = $this->dataEdit($id);
        return view(
            $this->getEditView(),
            [
                'result' => $result,
                'more' => array_merge($this->moreDataForm(), $this->moreDataEdit($result, $parent_id)),
                'form' => $this->getFormView(),
                'upload' => $this->getEditViewUpload(),
            ]
        );
    }

    protected function moreDataDetail($result)
    {
        return [];
    }

    protected function optionalDataDetail($db, $id)
    {
        return $db;
    }

    public function dataDetail($id)
    {
        $db = $this->dbBuilder();

        $db = $this->optionalDataDetail($db, $id);

        if (!empty($this->getAlias())) {
            $db->where("{$this->getAlias()}.{$this->getTableIdentifier()}", $id);
        } else {
            $db->where("{$this->getTableName()}.{$this->getTableIdentifier()}", $id);
        }

        $result = $db->first();
        if (empty($result)) abort(404);

        return $result;
    }

    // Override this function
    public function constructDataDetail($model)
    {
        $array = toArray($model);
        $data = [];
        foreach ($array as $key => $value) {
            $data[snake2Words($key)] = $value;
        }
        return $data;
    }

    public function show($parent_id, $id)
    {
        $id = $this->getIdParemeter($id);
        $parent_id = $this->getIdParemeter($parent_id);
        

        $result = $this->dataDetail($id);
        if (empty($result)) abort(404);

        $result = $this->constructDataDetail($result);

        return view(
            $this->getShowView(),
            [
                'result' => $result,
                'more' => array_merge($this->moreDataForm(), $this->moreDataDetail($result)),
            ]
        );
    }

    protected function changeInputSave($parent_id, $input)
    {
        return $input;
    }

    public function store($parent_id)
    {
        $parent_id = $this->getIdParemeter($parent_id);

        try {
            DB::beginTransaction();


            if(!empty($this->formRequest)){
                $request = app()->makeWith($this->formRequest, ['input' => $this->getInput(), 'file' => $this->getInputFile()]);
                if(!$request->getValidator()->fails())
                    $input = request()->input($this->getInput());
                else 
                    throw new \Exception($request->getValidator()->errors());
            }else{
                $input = request()->input($this->getInput());
            }

            $input = $this->changeInputSave($parent_id, $input);

            $now = date('Y-m-d H:i:s');
            if ($this->useModel()) {
                $model = app($this->modelClass);
                if (!empty($model->getCreatedAtColumn())) $input[$model->getCreatedAtColumn()] = $now;
                if (!empty($model->getUpdatedAtColumn())) $input[$model->getUpdatedAtColumn()] = $now;
            } else {
                if (!empty($this->createdAt)) $input[$this->createdAt] = $now;
                if (!empty($this->updatedAt)) $input[$this->updatedAt] = $now;
            }

            $this->beforeSave($input);

            $id = null;
            if ($this->useUUID) {
                $id = Str::uuid();
                $input[$this->getTableIdentifier()] = $id;

                DB::table($this->getTableName())
                    ->insert($input);
            } else {
                $id = DB::table($this->getTableName())
                    ->insertGetId($input);
            }

            $this->afterSave($input, $id);

            logHistory([
                'nama_proses' => 'INSERT DATA',
                'ket_proses' => 'TABEL ' . $this->getTableName(),
                'data_proses' => json_encode($input),
            ]);

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Ditambahkan.',
                    'next' => url($this->getUrlToRedirect()),
                ]);
            } else {
                return redirect($this->getUrlToRedirect())->with([
                    'success' => true,
                    'message' => 'Data Berhasil Ditambahkan.',
                ]);
            }
        } catch (Exception $ex) {
            DB::rollback();

            if (is_a($ex, HttpResponseException::class)) throw $ex;

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'errorMessage' => errorMessage($ex),
                    'next' => url($this->getUrlToRedirect()),
                ]);
            } else {
                return redirect($this->getUrlToRedirect())->with([
                    'success' => false,
                    'errorMessage' => errorMessage($ex),
                ]);
            }
        }
    }

    protected function changeInputSaveEdit($parent_id, $input, $model)
    {
        return $input;
    }

    public function update($parent_id, $id)
    {
        $redirectParams = json_decode(base64_decode(request()->redirect_params));
        $urlRedirectParams = !empty($redirectParams) ? Arr::query($redirectParams) : '';

        $parent_id = $this->getIdParemeter($parent_id);

        try {
            DB::beginTransaction();

            $id = $this->getIdParemeter($id);

            $this->alias = null;
            $oldData = $this->dbBuilder()
                ->where($this->getTableIdentifier(), $id)
                ->first();
            if (empty($oldData)) abort(404);

            if(!empty($this->formRequest)){
                $request = app()->makeWith($this->formRequest, ['input' => $this->getInput(), 'file' => $this->getInputFile(), 'model' => $oldData]);

                if(!$request->getValidator()->fails())
                    $input = request()->input($this->getInput());
                else 
                    throw new \Exception($request->getValidator()->errors());
            }else{
                $input = request()->input($this->getInput());
            }

            $input = $this->changeInputSaveEdit($parent_id, $input, $oldData);

            $now = date('Y-m-d H:i:s');
            if ($this->useModel()) {
                $model = app($this->modelClass);
                if (!empty($model->getUpdatedAtColumn())) $input[$model->getUpdatedAtColumn()] = $now;
            } else {
                if (!empty($this->updatedAt)) $input[$this->updatedAt] = $now;
            }

            $this->beforeSaveEdit($oldData);

            DB::table($this->getTableName())
                ->where($this->getTableIdentifier(), $id)
                ->update($input);

            $this->afterSaveEdit($input, $id);

            logHistory([
                'nama_proses' => 'UPDATE DATA',
                'ket_proses' => 'TABEL ' . $this->getTableName(),
                'data_proses' => json_encode([
                    'old' => $oldData,
                    'new' => $input
                ]),
            ]);

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Perubahan Berhasil Disimpan.',
                    'next' => url($this->getUrlToRedirect()),
                ]);
            } else {
                return redirect($this->getUrlToRedirect() . exist($urlRedirectParams, prefix: '?'))->with([
                    'success' => true,
                    'message' => 'Perubahan Berhasil Disimpan.',
                ]);
            }
        } catch (Exception $ex) {
            DB::rollback();

            if (is_a($ex, HttpResponseException::class)) throw $ex;

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'errorMessage' => errorMessage($ex),
                    'next' => url($this->getUrlToRedirect()),
                ]);
            } else {
                return redirect($this->getUrlToRedirect() . exist($urlRedirectParams, prefix: '?'))->with([
                    'success' => false,
                    'errorMessage' => errorMessage($ex),
                ]);
            }
        }
    }

    public function destroy($parent_id, $id)
    {
        $id = $this->getIdParemeter($id);
        $parent_id = $this->getIdParemeter($parent_id);

        try {
            DB::beginTransaction();

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

            $this->afterSaveDelete($id);

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

    protected function beforeSave($input)
    {
    }

    protected function beforeSaveEdit($model)
    {
    }

    protected function beforeSaveDelete($model)
    {
    }

    protected function afterSave($input, $id)
    {
    }

    protected function afterSaveEdit($input, $id)
    {
    }

    protected function afterSaveDelete($id)
    {
    }

    public function getSelect()
    {
        try {
            $db = $this->dbBuilder();
            $db = $this->getSelectOptionalQuery($db);

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

    protected function getSelectOptionalQuery($db)
    {
        return $db;
    }


    // SETTER AND GETTER
    public function getTableName()
    {
        if ($this->useModel()) return app($this->modelClass)->getTable();

        return $this->table;
    }

    public function useModel()
    {
        return !empty($this->modelClass);
    }

    public function getTableIdentifier()
    {
        if ($this->useModel()) return app($this->modelClass)->getKeyName();

        return $this->primaryKey;
    }

    public function getTableDesc()
    {
        return $this->descKey;
    }

    public function getFind()
    {
        return $this->find;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getSelectFind()
    {
        return $this->selectFind;
    }

    public function getSelectColumn()
    {
        return $this->selectColumn;
    }

    public function getSelectLimit()
    {
        return $this->selectLimit;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getInputFile()
    {
        return $this->inputFile;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUrlToRedirect($subto = null)
    {
        $url_segments = request()->segments();
        $parent_url = $url_segments[1] . '/' . $url_segments[2];

        return $this->urlRedirect . exist($parent_url, prefix: '/') . exist($subto, prefix: '/');
    }

    public function getIdParemeter($id)
    {
        return decodeId($id);
    }

    public function getListView()
    {
        if ($this->useDefaultListView) return "{$this->defaultView}.{$this->listView}";

        return $this->getView($this->listView);
    }

    public function getAddView()
    {
        if ($this->useDefaultAddView) return "{$this->defaultView}.{$this->addView}";

        return $this->getView($this->addView);
    }

    public function getEditView()
    {
        if ($this->useDefaultEditView) return "{$this->defaultView}.{$this->editView}";

        return $this->getView($this->editView);
    }

    public function getShowView()
    {
        if ($this->useDefaultShowView) {
            if (request()->ajax()) return "{$this->defaultView}.{$this->showViewAjax}";

            view()->share('detailView', "{$this->defaultView}.{$this->showViewAjax}");
            return "{$this->defaultView}.{$this->showView}";
        }

        if (request()->ajax()) return $this->getView($this->showViewAjax);

        view()->share('detailView', $this->getView($this->showViewAjax));
        return $this->getView($this->showView);
    }

    public function getFormView()
    {
        if ($this->useDefaultFormView) return "{$this->defaultView}.{$this->formView}";

        return $this->getView($this->formView);
    }

    public function getAddViewUpload()
    {
        return $this->addViewUpload;
    }

    public function getEditViewUpload()
    {
        return $this->editViewUpload;
    }

    public function getView($view = null)
    {
        return !empty($this->view) ? "{$this->view}.{$view}" : $view;
    }

    public function getPermission()
    {
        return $this->permissions;
    }

    public function getPermissionName()
    {
        return $this->permissionName ?? $this->getTableName();
    }

    public function __construct()
    {
        $this->type = request()->segments()[1] ?? '';
        $this->customPostType = DB::table('custom_post_type')->where('post_type_code', $this->type)->first();
        if ($this->usePermission) {
            // Middleware only applied to these methods
            $arrayPermission = $this->getPermission();
            foreach ($arrayPermission as $permission => $action) {
                $this->middleware("permission:{$this->getPermissionName()}.{$this->type}.{$permission}")->only(explode(',', $action));
            }
        }

        $this->middleware(function ($request, $next) {
            if ($this->usePermit && !hasPermit($this->permits)) {
                abort(403, 'Anda tidak memiliki hak akses.');
            }
            
            view()->share('title', $this->getTitle());
            view()->share('mainURL', $this->getUrlToRedirect());
            view()->share('view', $this->view);
            view()->share('primaryKey', $this->getTableIdentifier());
            view()->share('input', $this->getInput());
            view()->share('inputFile', $this->getInputFile());
            view()->share('permission', $this->getPermissionName());
            view()->share('type', $this->type);
            return $next($request);
        });
    }
}
