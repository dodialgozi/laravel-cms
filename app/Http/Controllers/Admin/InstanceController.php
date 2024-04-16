<?php

namespace App\Http\Controllers\Admin;

use App\Models\Instance;
use Illuminate\Support\Str;
use App\Http\Requests\InstanceRequest;
use App\Http\Controllers\BaseController as Controller;
use App\Http\Traits\Upload;

class InstanceController extends Controller
{
    use Upload;
    protected $tilte = 'Instansi';
    protected $modelClass = Instance::class;
    protected $alias = 'i';
    protected $desc_key = 'instance_name';
    protected $view = 'backend.admin.partials.instances';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'instance';

    protected $permissions = ['lihat' => 'index', 'tambah' => 'create,store', 'ubah' => 'edit,update', 'hapus' => 'destroy', 'rincian' => 'show'];

    protected $searchColumn = ['nama' => '%LIKE%', 'status' => '=', 'domain' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'instance_name', 'status' => 'instance_active', 'domain' => 'instance_domain'];

    protected $selectFind = ['instance_name', 'instance_active', 'instance_domain'];
    protected $selectColumn = ['instance_id as id', 'instance_name as nama', 'instance_active as status', 'instance_domain as domain'];

    protected $sortColumn = ['nama', 'status', 'domain'];
    protected $sortColumnField = ['nama' => 'instance_name', 'status' => 'instance_active', 'domain' => 'instance_domain'];

    protected $permissionName = "instance";

    protected $formRequest = InstanceRequest::class;

    protected $limit = null;

    protected function optionalQuery($db)
    {
        return $db->orderBy('instance_name', 'asc');
    }

    protected function changeInputSave($input)
    {
        $input['instance_active'] = !empty($input['instance_active']) ? 1 : 0;
        $input['instance_slug'] = Str::slug($input['instance_name'], '-');

        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'instansi', 'instance_thumbnail'))) {
            $input['instance_thumbnail'] = $fileUpload;
        }

        return $input;
    }

    protected function changeInputSaveEdit($input, $model)
    {
        $input['instance_active'] = !empty($input['instance_active']) ? 1 : 0;
        $input['instance_slug'] = Str::slug($input['instance_name'], '-');

        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'instansi', 'instance_thumbnail'))) {
            $input['instance_thumbnail'] = $fileUpload;

            if (!empty($model->instance_thumbnail)) {
                $this->deleteFile($model->instance_thumbnail);
            }
        }

        return $input;
    }

    protected function beforeSaveDelete($oldData)
    {
        if (!empty($oldData->instance_thumbnail)) {
            $this->deleteFile($oldData->instance_thumbnail);
        }
    }

    public function setInstance($id)
    {
        $id = decodeId($id);

        $instance = $this->modelClass::find($id);

        if (empty($instance)) {
            return redirect($this->getUrlToRedirect())->with(['error' => 'Data tidak ditemukan']);
        }

        // set session instance
        session(['instance_id' => encodeId($instance->instance_id)]);

        return redirect()->back()->with(['success' => true, 'message' => 'Instansi berhasil di set']);
    }
}
