<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    protected $table = 'post';
    protected $primaryKey = 'post_id';
    protected $perPage = 20;
    protected $guarded = [];

    use ModelFunction;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->hasMany(PostCategory::class, 'post_id');
    }

    public function tags()
    {
        return $this->hasMany(PostTag::class, 'post_id');
    }

    public function rating()
    {
        return $this->belongsTo(Rating::class, 'post_id', 'rating_object_id')
            ->where('rating_object', 'post');
    }

    public function scopeWhereRentangTanggal($query, $mulai, $sampai)
    {
        return $query->where(DB::raw('DATE(post_date)'), '>=', $mulai)
            ->where(DB::raw('DATE(post_date)'), '<=', $sampai);
    }

    public function getRatingPostAttribute()
    {
        return [
            'highest' => $this->rating->highest,
            'high' =>  $this->rating->high,
            'medium' =>  $this->rating->medium,
            'low' =>   $this->rating->low,
            'lowest' =>   $this->rating->lowest,
        ];
    }

    public function dashboardTrendingPost()
    {
        return $this->hasMany(DashboardTrendingPost::class, 'post_id', 'post_id');
    }
}
