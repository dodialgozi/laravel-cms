<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = 'post_categories';
    protected $primaryKey = 'cat_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function posts() {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
