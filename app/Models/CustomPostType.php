<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPostType extends Model
{
    protected $table = 'custom_post_type';
    protected $primaryKey = 'post_type_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function custom_posts()
    {
        return $this->hasMany(CustomPost::class, 'post_type_id', 'post_type_id');
    }
}
