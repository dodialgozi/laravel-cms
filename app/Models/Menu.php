<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    protected $table = 'admin_menu';
    protected $primaryKey = 'menu_id';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id');
    }

    public static function getPermit()
    {
        return arrayWithKey([
            // 'pegawai.absen',
            // 'pegawai.absen_istirahat',
            // 'pegawai.lembur',
            // 'pegawai.cuti',
            // 'pegawai.sakit',
            // 'pegawai.izin',
            // 'pegawai.approve_workplan',
            // 'pegawai.lihat_slip_gaji',
            // 'pegawai.penugasan',
            // 'pegawai.marketing',
            // 'pegawai.marketing',
            // 'pegawai.penilaian_kpi',
            // 'device.mobile',
        ]);
    }

    public function getEnableAttribute()
    {
        return !empty($this->menu_enable);
    }

    public function getPermitAttribute()
    {
        return decodeJsonArray($this->menu_permit);
    }

    public function getHasPermitAttribute()
    {
        return hasPermit($this->permit);
    }

    public static function allMenu()
    {
        if (empty(auth()->user())) return [];

        $menu = self::with(['children' => function ($query) {
            $query->with(['children' => function ($query) {
                $query->orderBy('menu_order')
                    ->orderBy('menu_id');
            }])
                ->orderBy('menu_order')
                ->orderBy('menu_id');
        }])
            ->whereNull('parent_id')
            ->orderBy('menu_order')
            ->orderBy('menu_id')
            ->get();

        return $menu->map(function ($menu1) {
            $menu1->menu_children = $menu1->children->map(function ($menu2) {
                $menu2->menu_children = $menu2->children->reject(function ($menu3) {
                    // disable
                    if (!$menu3->enable) return true;

                    // link kosong
                    if (empty($menu3->menu_link)) return true;

                    // permission kosong dan tidak ada permit
                    if (empty($menu3->permission_id)) return !$menu3->hasPermit;

                    // tidak memiliki akses permission
                    return !auth()->user()->hasPermissionTo($menu3->permission_id);
                })->values();

                /* // jika child hanya satu, jadikan parent (gunakan icon parent)
                if ($menu2->menu_children->count() == 1) {
                    $tempMenu = $menu2->menu_children->first();
                    $tempMenu->menu_name = "{$menu2->menu_name} {$tempMenu->menu_name}";
                    $tempMenu->menu_icon = $menu2->menu_icon;
                    return $tempMenu;
                } */

                return $menu2;
            });
            $menu1->menu_children = $menu1->menu_children->reject(function ($menu2) {
                // tidak memiliki children
                if (empty($menu2->menu_children) || $menu2->menu_children->isEmpty()) {
                    // disable
                    if (!$menu2->enable) return true;

                    // link kosong
                    if (empty($menu2->menu_link)) return true;

                    // permission kosong dan tidak ada permit
                    if (empty($menu2->permission_id)) return !$menu2->hasPermit;

                    // tidak memiliki akses permission
                    return !auth()->user()->hasPermissionTo($menu2->permission_id);
                }

                return false;
            })->values();

            /* // jika child hanya satu, jadikan parent (gunakan icon parent)
            if ($menu1->menu_children->count() == 1) {
                $tempMenu = $menu1->menu_children->first();
                $tempMenu->menu_name = "{$menu1->menu_name} {$tempMenu->menu_name}";
                $tempMenu->menu_icon = $menu1->menu_icon;
                return $tempMenu;
            } */

            return $menu1;
        })
            ->reject(function ($menu1) {
                // tidak memiliki children
                if (empty($menu1->menu_children) || $menu1->menu_children->isEmpty()) {
                    // disable
                    if (!$menu1->enable) return true;

                    // link kosong
                    if (empty($menu1->menu_link)) return true;

                    // permission kosong dan tidak ada permit
                    if (empty($menu1->permission_id)) return !$menu1->hasPermit;

                    // tidak memiliki akses permission
                    return !auth()->user()->hasPermissionTo($menu1->permission_id);
                }

                return false;
            })->values();
    }
}
