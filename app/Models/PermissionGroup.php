<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $table = 'permissions_group';
    protected $primaryKey = 'id';
    protected $perPage = 20;
    protected $guarded = [];

    use ModelFunction;
}
