<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class Alumnus extends Model
{
    protected $table = 'alumnus';
    protected $primaryKey = 'alumnus_id';
    protected $perPage = 20;
    protected $guarded = [];

    use ModelFunction;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id', 'instance_id');
    }
}
