<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partner';
    protected $primaryKey = 'partner_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id', 'instance_id');
    }
}
