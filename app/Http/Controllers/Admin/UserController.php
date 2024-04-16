<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\Upload;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    use Upload;

    protected $title = 'Pengguna';
    protected $modelClass = User::class;
    protected $alias = 'p';
    protected $descKey = 'user_name';
    protected $column = ['p.*'];
    protected $view = 'backend.admin.partials.user';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $useDefaultShowView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'user';
    protected $searchColumn = ['nama' => '%LIKE%', 'email' => '%LIKE%', 'nick' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'user_name', 'email' => 'user_email', 'nick' => 'user_nick'];

    protected $selectFind = ['user_name', 'user_email', 'user_nick'];
    protected $selectColumn = ['user_id as id', 'user_name As nama', 'user_email as email', 'user_nick as nick'];

    protected $sortColumn = ['nama', 'email', 'nick'];
    protected $sortColumnField = ['nama' => 'user_name', 'email' => 'user_email', 'nick' => 'user_nick'];

    protected $permissionName = "user";

    protected $formRequest = UserRequest::class;

    protected function optionalQuery($db)
    {
        return $db->leftJoin('model_has_roles AS mhr', function ($join) {
            $join->on('mhr.model_id', 'p.user_id')
                ->where('model_type', $this->modelClass);
        })
            ->leftJoin('roles AS r', 'r.id', 'mhr.role_id')
            ->when(request()->role, function ($query) {
                $query->where('r.id', decodeId(request()->role));
            });
    }

    protected function moreDataIndex()
    {
        return [
            'role' => Role::findOrNull(decodeId(request()->role)),
        ];
    }

    protected function moreDataForm()
    {
        $levels = ['administrator' => 'Super Admin', 'jurnalis' => 'User'];
        return [
            'levels' => $levels
        ];
    }

    protected function changeInputSave($input)
    {
        $input['user_password'] = encode($input['user_password']);
        $input['user_active'] = !empty($input['user_active']) ? 1 : 0;

        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'foto', 'user_photo'))) {
            $input['user_photo'] = $fileUpload;
        }

        return $input;
    }

    protected function afterSave($input, $id)
    {
        UserService::assignRole(request()->role, $this->modelClass, $id);
    }

    protected function moreDataEdit($result)
    {
        $role = UserService::getAssignedRole($this->modelClass, $result->user_id);

        return [
            'role' => $role,
            'jabatan' => $result->jabatan,
            'divisi' => $result->divisi,
            'cabang' => $result->cabang,
            'kelompok' => $result->kelompok,
        ];
    }

    protected function changeInputSaveEdit($input, $model)
    {
        $input['user_password'] = encode($input['user_password']);
        $input['user_active'] = !empty($input['user_active']) ? 1 : 0;

        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'foto', 'user_photo'))) {
            $input['user_photo'] = $fileUpload;
        }

        return $input;
    }

    protected function afterSaveEdit($input, $id)
    {
        UserService::reAssignRole(request()->role, $this->modelClass, $id);
    }

    protected function beforeSaveDelete($model)
    {
        UserService::removeRole($this->modelClass, $model->user_id);
    }

    public function constructDataDetail($model)
    {
        return [
            'Role' => $model->roles[0]->name ?? '-',
            'Status' => !empty($model->user_active) ? 'Aktif' : 'Non-Aktif',
            'Level' => $model->user_level == 'administrator' ? 'Super Admin' : 'User',
            'Nama' => $model->user_name ?? '-',
            'Email' => $model->user_email ?? '-',
            'Panggilan' => $model->user_nick ?? '-',
            'Bio' => $model->user_bio ?? '-',
            'Berkas',
            'Foto' => [
                'type' => 'image',
                'value' => $model->user_photo,
            ],

        ];
    }

    protected function getSelectOptionalQuery($db)
    {
        return $db
            ->when(request()->input('level'), function ($q, $level) {
                $q->where('user_level', $level);
            })
            ->whereNotIn('user_id', function ($query) {
                $query->select('user_id')->from('user_category');
            });
    }
}
