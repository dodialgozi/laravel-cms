<?php

namespace App\Http\Controllers\Admin;

use App\Models\PermissionGroup;
use App\Http\Controllers\BaseController as Controller;

class PermissionGroupController extends Controller
{
    protected $title = 'Permission Group';
    protected $modelClass = PermissionGroup::class;
    protected $alias = 'pg';
    protected $descKey = 'name';
    protected $view = 'backend.admin.partials.permission-group';
    protected $useDefaultAddView = true;
    protected $useDefaultEditView = true;
    protected $urlRedirect = 'permission-group';
    protected $searchColumn = ['nama' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'name'];

    protected $selectFind = ['name'];
    protected $selectColumn = ['id', 'name AS nama'];
}
