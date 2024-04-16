<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardPengunjung extends Model
{
    protected $table = 'dashboard_pengunjung';
    protected $primaryKey = 'dashboard_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;
}
