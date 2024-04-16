<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    protected $table = 'location';
    protected $primaryKey = 'location_id';
    protected $perPage = 20;
    protected $guarded = [];

    const UPDATED_AT = 'updated_time';

    use ModelFunction;

    public function categories() {
        return $this->hasMany(LocationCategories::class, 'location_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeWhereRentangTanggal($query, $mulai, $sampai)
    {
        return $query->where(DB::raw('DATE(location_date)'), '>=', $mulai)
            ->where(DB::raw('DATE(location_date)'), '<=', $sampai);
    }
}
