<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomPost extends Model
{
    protected $table = 'custom_post';
    protected $primaryKey = 'post_id';
    protected $perPage = 20;
    protected $guarded = [];

    use ModelFunction;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories() {
        return $this->hasMany(CustomPostCategoryRelation::class, 'post_id');
    }

    public function custom_post_type()
    {
        return $this->belongsTo(CustomPostType::class, 'post_type_id');
    }
}
