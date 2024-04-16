<?php

namespace App\Models;

use Exception;
use App\Models\Traits\ModelFunction;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Advertising extends Authenticatable
{
    protected $table = 'advertising';
    protected $primaryKey = 'ads_id';
    protected $perPage = 20;
    public $timestamps = false;
    protected $guarded = [];
}
