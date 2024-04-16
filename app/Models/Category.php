<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function post_categories()
    {
        return $this->hasMany(PostCategory::class, 'category_id', 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function user_categories()
    {
        return $this->hasMany(UserCategory::class, 'category_id');
    }

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id', 'instance_id');
    }
}
