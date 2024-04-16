<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use App\Models\PermissionGroup;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $perPage = 20;
    protected $guarded = [];

    use ModelFunction;

    public function group()
    {
        return $this->belongsTo(PermissionGroup::class, 'group_id');
    }

    public static function getDefaultPermission()
    {
        return [
            'lihat' => 'Lihat',
            'tambah' => 'Tambah',
            'ubah' => 'Ubah',
            'hapus' => 'Hapus',
            'rincian' => 'Rincian',
        ];
    }
}
