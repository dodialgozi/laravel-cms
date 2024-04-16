<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Page extends Model
{
    protected $table = 'page';
    protected $primaryKey = 'page_id';
    protected $perPage = 20;
    protected $guarded = [];

    use ModelFunction;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id');
    }

    public function scopeWhereRentangTanggal($query, $mulai, $sampai)
    {
        return $query->where(DB::raw('DATE(created_at)'), '>=', $mulai)
            ->where(DB::raw('DATE(created_at)'), '<=', $sampai);
    }
}
