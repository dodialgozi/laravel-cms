<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class UserInstance extends Model
{
    protected $table = 'user_instance';
    protected $primaryKey = 'user_instance_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id', 'instance_id');
    }
}
