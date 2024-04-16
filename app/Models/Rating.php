<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rating extends Model
{
    protected $table = 'rating';
    protected $primaryKey = 'rating_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function scopeOrderByHighest($query)
    {
        return $query->orderByDesc(DB::raw("(IFNULL(highest,0)+IFNULL(high,0)+IFNULL(medium,0)+IFNULL(low,0)+IFNULL(lowest,0))"));
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'rating_object_id', 'post_id');
    }
}
