<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as Controller;
use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use Exception;

class RoleController extends Controller
{
    protected $title = 'Role';
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $descKey = 'name';
    protected $view = 'backend.admin.partials.roles';
    protected $useDefaultAddView = true;
    protected $useDefaultEditView = true;
    protected $urlRedirect = 'role';

    protected $sortColumnField = ['nama' => 'name'];

    protected $permissions = ['lihat' => 'index', 'tambah' => 'create,store', 'ubah' => 'edit,update', 'hapus' => 'destroy', 'rincian' => 'show', 'permission' => 'permission'];
    protected $selectFind = ['name'];
    protected $selectColumn = ['id', 'name AS nama'];

    // protected $formRequest = RoleRequest::class;

    protected function optionalQuery($db)
    {
        return $db->when(getLevel() != 'administrator', function ($query) {
            $query->where('name', '!=', 'root');
        });
    }

    protected function changeInputSave($input)
    {
        $input['guard_name'] = 'web';
        return $input;
    }

    public function permission($id)
    {
        $id = decodeId($id);

        if (request()->isMethod('post')) {
            $input = request()->input($this->input);
            $input = array_keys($input);

            $result = RoleService::savePermission($input, $id);

            return redirect($this->getUrlToRedirect())->with($result);
        }

        $permission = RoleService::getPermission($id);

        return view(
            "{$this->view}.permission",
            [
                'title' => $this->getTitle(),
                'mainURL' => $this->getUrlToRedirect(),
                'primaryKey' => $this->getTableIdentifier(),
                'input' => $this->getInput(),
                'group' => $permission['group'],
                'defaultPermission' => $permission['defaultPermission'],
                'permission' => $permission['permission'],
                'rolePermission' => $permission['rolePermission'],
            ]
        );
    }

    protected function getSelectOptionalQuery($db)
    {
        return $db->where('name', '!=', 'root');
    }
}
