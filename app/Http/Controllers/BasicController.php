<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class BasicController extends Controller
{
    // Title => String; required
    protected $title = '';

    // Folder View => String; nullable
    protected $view = null;

    // Redirect => String; required
    protected $urlRedirect = 'testing';
    // Form Input Name => String; required
    protected $input = 'input';
    // Form Input Name for File Upload => String; required
    protected $inputFile = 'file';

    // Permission to Access Action => Array<String, String>; optional
    // ex: ['lihat' => 'index', 'tambah' => 'create,store']
    protected $permissions = [];
    // Permission Name => String; nullable
    protected $permissionName = null;

    public function getView($view = null)
    {
        return !empty($this->view) ? "{$this->view}.{$view}" : $view;
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
        return $this->urlRedirect . exist($subto, prefix: '/');
    }

    public function getPermission()
    {
        return $this->permissions;
    }

    public function getPermissionName()
    {
        return $this->permissionName;
    }

    public function __construct()
    {
        // Middleware only applied to these methods
        $arrayPermission = $this->getPermission();
        foreach ($arrayPermission as $permission => $action) {
            $this->middleware("permission:{$this->getPermissionName()}.{$permission}")->only(explode(',', $action));
        }

        $this->middleware(function ($request, $next) {
            view()->share('title', $this->getTitle());
            view()->share('mainURL', $this->getUrlToRedirect());
            view()->share('view', $this->view);
            view()->share('input', $this->getInput());
            view()->share('inputFile', $this->getInputFile());
            view()->share('permission', $this->getPermissionName());

            return $next($request);
        });
    }
}
