<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardTrendingPost extends Model
{
    protected $table = 'dashboard_trending_post';
    protected $primaryKey = 'dashboard_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
