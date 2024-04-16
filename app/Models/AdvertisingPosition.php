<?php

namespace App\Models;

use Exception;
use App\Models\Traits\ModelFunction;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdvertisingPosition extends Authenticatable
{
    protected $table = 'advertising_position';
    protected $primaryKey = 'position_id';
    protected $perPage = 20;
    public $timestamps = false;
    protected $guarded = [];
    const CREATED_AT = null;
    const UPDATED_AT = null;
}
