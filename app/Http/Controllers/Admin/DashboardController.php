<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Http\Traits\Upload;
use App\Http\Controllers\BasicController as Controller;

class DashboardController extends Controller
{
    protected $view = 'backend.admin.partials.profile';

    use Upload;

    public function index()
    {
        // Dashboard
        return view($this->getView('dashboard'));
    }

}
