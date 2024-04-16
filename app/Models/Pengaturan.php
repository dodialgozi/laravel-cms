<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'setting';
    protected $primaryKey = 'setting_id';
    protected $perPage = 20;
    protected $guarded = [];
    public $timestamps = false;

    use ModelFunction;

    public static function getSetting(string $kode, $default = null, $cabangId = null)
    {
        $settingKey = 'setting';

        $setting = request()->get($settingKey);

        if (empty($setting)) {
            $setting = self::get()
                ->mapWithKeys(function ($item) {
                    return [$item->kode => $item->value];
                })->toArray();
            request()->attributes->add([$settingKey => $setting]);
        }

        return $setting[$kode] ?? $default;
    }

    public static function setSetting(string $kode, $value = null, $cabangId = null)
    {
        $settingKey = 'setting';

        self::updateOrCreate([
            'kode' => $kode,
        ], [
            'value' => $value,
        ]);

        $setting = self::get()
            ->mapWithKeys(function ($item) {
                return [$item->kode => $item->value];
            })->toArray();
        request()->attributes->add([$settingKey => $setting]);
    }
}
