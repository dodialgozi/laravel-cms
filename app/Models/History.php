<?php

namespace App\Models;

use App\Models\Traits\ModelFunction;
use Jenssegers\Agent\Agent;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'log_aktifitas';
    protected $primaryKey = 'tgl_aktifitas';
    protected $keyType = 'string';
    protected $perPage = 20;
    protected $guarded = [];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    use ModelFunction;

    public static function log($param)
    {
        if (!empty($param)) {
            $agent = new Agent();
            $platform = $agent->platform();
            $devices = 'Tidak diketahui';
            $ip = request()->ip();
            $browser = 'Tidak diketahui';
            if ($agent->isDesktop()) {
                $devices = 'PC';
                $browser = $agent->browser();
                $version = $agent->version($browser);
                $browser .= " " . $version;
            } else if ($agent->isRobot()) {
                $devices = $agent->robot();
            } else if ($agent->isMobile()) {
                $devices = $agent->device();
                $browser = $agent->browser();
                $version = $agent->version($browser);
                $browser .= " " . $version;
            }

            $data = [
                'tgl_aktifitas' => microtime(true),
                'platform' => $platform,
                'devices' => $devices,
                'ip' => $ip,
                'browser' => $browser,
                'level' => auth()->user()->roles[0]->name ?? getLevel(),
                'id_user' => auth()->user()->user_id,
                'link' => (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
            ];

            foreach ($param as $key => $value) {
                if (isset($data[$key])) {
                    $data[$key] = $value;
                } else {
                    $data += [$key => $value];
                }
            }

            self::create($data);

            return true;
        }
        return false;
    }
}
