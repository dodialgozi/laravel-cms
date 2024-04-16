<?php

use App\Http\Helper\DataEncryptor;
use App\Models\Menu;
use App\Models\History;
use Jenssegers\Date\Date;
use App\Models\Pengaturan;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Http\Helper\Encryptor;
use App\Models\Permission;
use Ladumor\OneSignal\OneSignal;
use Jenssegers\Agent\Facades\Agent;
use Vinkla\Hashids\Facades\Hashids;
use Intervention\Image\Facades\Image;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

if (!function_exists('encodeId')) {
    function encodeId($id)
    {
        return Hashids::encode($id);
    }
}

if (!function_exists('decodeId')) {
    function decodeId($hashId)
    {
        if (empty($hashId)) return null;

        return Hashids::decode($hashId)[0] ?? null;
    }
}

if (!function_exists('encode')) {
    function encode($data)
    {
        return (new Encryptor())->encode($data);
    }
}

if (!function_exists('decode')) {
    function decode($data)
    {
        return (new Encryptor())->decode($data);
    }
}

if (!function_exists('base64ToBase64Url')) {
    function base64ToBase64Url($base64)
    {
        return rtrim(strtr($base64, '+/', '-_'), '=');
    }
}

if (!function_exists('base64UrlToBase64')) {
    function base64UrlToBase64($data)
    {
        return str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT);
    }
}

if (!function_exists('encodeData')) {
    function encodeData($data)
    {
        return base64ToBase64Url(DataEncryptor::encrypt($data));
    }
}

if (!function_exists('decodeData')) {
    function decodeData($data)
    {
        return DataEncryptor::decrypt(base64UrlToBase64($data));
    }
}

if (!function_exists('getSetting')) {
    function getSetting($kode, $default = null)
    {
        try {
            $set = DB::table('setting')->where('key', $kode)->first();
            return $set->value ?? $default;
        } catch (\Exception $ex) {
            return $default;
        }
    }
}

if (!function_exists('getLevel')) {
    function getLevel()
    {
        return auth()->user()->user_level;
    }
}


if (!function_exists('getMenu')) {
    function getMenu()
    {
        if (env("APP_ENV") == 'production') {
            if (getLevel() != 'administrator') {
                return Menu::allMenu();
            } else {
                // SuperAdmin Menu
                $json = json_decode(file_get_contents(base_path('optional/menu.json')));
                return $json ?? [];
            }
        };

        // IF Dev default menu come from db or otherwise from menu.json
        $menu = Menu::allMenu();
        if (count($menu) > 0) return $menu;

        $json = json_decode(file_get_contents(base_path('optional/menu.json')));
        return $json ?? [];
    }
}

if (!function_exists('getCustomPostType')) {
    function getCustomPostType()
    {
        return DB::table('custom_post_type')->where('post_type_status', 1)->get()->reject(fn ($item) => !auth()->user()->hasPermissionTo("custom-post.{$item->post_type_code}.lihat") && getLevel() != 'administrator')->values();
    }
}

if (!function_exists('getMenuLink')) {
    function getMenuLink($link)
    {
        $link = str_replace("{[BASEURL]}", url('/'), $link);
        return $link;
    }
}

if (!function_exists('hasPermit')) {
    function hasPermit($permits)
    {
        foreach ($permits as $value) {
            [$case, $permit] = explode('.', $value);

            if ($value == 'device.mobile' && !(Agent::isMobile() || Agent::isTablet()))
                return false;
            if ($case == 'pegawai' && !auth()->user()->{"bisa_{$permit}"})
                return false;
        }

        return true;
    }
}

if (!function_exists('findPermissionIdByName')) {
    function findPermissionIdByName($name)
    {
        if (empty($name)) return null;

        $perm = Permission::where(['name' => $name])->first();

        return $perm->id ?? null;
    }
}

if (!function_exists('mergeSortRequest')) {
    function mergeSortRequest($request, $array)
    {
        return http_build_query(array_merge($request, $array));
    }
}

if (!function_exists('userCan')) {
    function userCan($permission)
    {
        return getLevel() == 'administrator' || auth()->user()->hasPermissionTo($permission);
    }
}

if (!function_exists('currentUserId')) {
    function currentUserId()
    {
        return auth()->user()->id();
    }
}

if (!function_exists('logHistory')) {
    function logHistory($params)
    {
        return History::log($params);
    }
}

if (!function_exists('actionButtons')) {
    function actionButtons($actions = [])
    {
        if (empty($actions) || !in_array(true, array_column($actions, 0))) return '';

        $actions = collect($actions)->reject(function ($item) {
            return is_bool($item[0]) && $item[0] !== true;
        })->values()->toArray();

        if (count($actions) == 1)
            return empty($actions[0]) ? '' : actionButton($actions[0], useTooltip: true);

        return '<x-dropdown>' . dropdownMenuContent($actions) . '</x-dropdown>';
    }
}

if (!function_exists('dropdownMenuContent')) {
    function dropdownMenuContent($menuItems = [])
    {
        return collect($menuItems)
            ->map(fn ($item) => '<li>' . actionButton($item) . '</li>')
            ->reject(fn ($item) => empty($item))
            ->implode('');
    }
}

if (!function_exists('actionButton')) {
    function actionButton($item, bool $useTooltip = false)
    {
        if ($item[0] === false) return null;

        if (is_bool($item[0])) array_shift($item);

        $isButton = false;
        $buttonAttribute = new ComponentAttributeBag();
        $iconAttribute = new ComponentAttributeBag([
            'class' => 'align-middle',
        ]);

        if (isset($item['onClick'])) {
            $onClick = $item['onClick'];
            $fn = array_shift($onClick);
            $ocParams = [];
            foreach ($onClick as $ocValue) {
                if (is_array($ocValue)) {
                    $ocParams[] = json_encode($ocValue);
                } else {
                    $ocParams[] = "'{$ocValue}'";
                }
            }

            $buttonAttribute = $buttonAttribute->merge([
                'onClick' => $fn . '(' . implode(',', $ocParams) . ')',
            ]);

            $isButton = true;
        } else {
            if (!empty($item['class'])) $buttonAttribute = $buttonAttribute->class($item['class']);
            if (!empty($item[2])) $buttonAttribute = $buttonAttribute->merge([
                'href' => $item[2],
            ]);
            if (!empty($item['target'])) $buttonAttribute = $buttonAttribute->merge([
                'target' => $item['target'],
            ]);

            if (isset($item['button'])) {
                $isButton = true;
                if (is_array($item['button'])) $buttonAttribute = $buttonAttribute->merge([
                    'data-button' => json_encode($item['button']),
                ]);
            }
        }

        $action = !$useTooltip ? " {$item[0]}" : '';
        $iconAttribute = $iconAttribute->class($item[1]);
        if ($useTooltip) {
            $buttonAttribute = $buttonAttribute->class('btn btn-sm')
                ->merge([
                    'title' => $item[0],
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-placement' => 'top',
                ]);
            preg_match('/text\-[a-z]+/', $iconAttribute->get('class'), $match);
            if (isset($match[0])) {
                $color = substr($match[0], 5);
                $buttonAttribute = $buttonAttribute->class("waves-effect waves-light btn-outline-{$color}");

                $iconAttribute->setAttributes([
                    'class' => preg_replace('/\s{2,}/', ' ', preg_replace('/text\-[a-z]+/', '', $iconAttribute->get('class'))),
                ]);
            } else {
                $buttonAttribute = $buttonAttribute->class("waves-effect waves-light btn-outline-light");
            }
        } else {
            $buttonAttribute = $buttonAttribute->class('dropdown-item');
            $iconAttribute = $iconAttribute->class('me-2');
        }

        return '<' . ($isButton ? 'button type="button"' : 'a') . ' ' . $buttonAttribute . '><i ' . $iconAttribute . '></i>' . $action . '</' . ($isButton ? 'button' : 'a') . '>';
    }
}

if (!function_exists('array_shift_key')) {
    function array_shift_key(&$array, $key)
    {
        if (isset($array[$key])) {
            $value = $array[$key];
            unset($array[$key]);
            return $value;
        }
        return null;
    }
}

if (!function_exists('toArray')) {
    function toArray($data)
    {
        return json_decode(json_encode($data), true);
    }
}

if (!function_exists('rupiah')) {
    function rupiah($angka, $prefix = 'Rp ', $suffix = '', $checkEmpty = false, $emptyBack = '', $digitKoma = 0)
    {
        if ($checkEmpty && empty($angka)) return $emptyBack;
        return $prefix . number_format($angka, $digitKoma, ',', '.') . $suffix;
    }
}

if (!function_exists('penyebut')) {
    function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = penyebut($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai / 10) . " Puluh" . penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai / 100) . " Ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai / 1000) . " Ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai / 1000000) . " Juta" . penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai / 1000000000) . " Milyar" . penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = penyebut($nilai / 1000000000000) . " Trilyun" . penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }
}

if (!function_exists('penyebutKoma')) {
    function penyebutKoma($nilai)
    {
        $ex = str_split($nilai);
        $temp = '';
        foreach ($ex as $value) {
            $temp .= empty($value) ? ' Nol' : ' ' . penyebut($value);
        }
        return $temp;
    }
}

if (!function_exists('terbilang')) {
    function terbilang($nilai)
    {
        $ex = explode('.', floatval($nilai), 2);
        $nilaiBulat = $ex[0] ?? 0;
        $nilaiKoma = $ex[1] ?? null;
        $hasilBulat = empty($nilaiBulat) ? ' Nol' : penyebut($nilaiBulat);
        $hasilKoma = empty($nilaiKoma) ? '' :  penyebutKoma($nilaiKoma);
        $hasil = $hasilBulat . exist($hasilKoma, prefix: ' Koma ');
        if ($nilai < 0) $hasil = 'Minus ' . $hasil;

        return trim(removeMultiSpaces($hasil));
    }
}

if (!function_exists('printDate')) {
    function printDate($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("d F Y") . $suffix;
    }
}

if (!function_exists('printDateDay')) {
    function printDateDay($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("l, d F Y") . $suffix;
    }
}

if (!function_exists('printDateDayHourMinute')) {
    function printDateDayHourMinute($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("l, d F Y | H:i") . $suffix;
    }
}

if (!function_exists('printDateTime')) {
    function printDateTime($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("d F Y | H:i:s") . $suffix;
    }
}

if (!function_exists('printDateTimeDay')) {
    function printDateTimeDay($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("l, d F Y | H:i:s") . $suffix;
    }
}

if (!function_exists('printTime')) {
    function printTime($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("H:i:s") . $suffix;
    }
}

if (!function_exists('printMinute')) {
    function printMinute($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("i") . $suffix;
    }
}

if (!function_exists('printHour')) {
    function printHour($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("H") . $suffix;
    }
}

if (!function_exists('printHourMinute')) {
    function printHourMinute($date, $back = '', $prefix = '', $suffix = '')
    {
        if (empty($date)) return $back;
        try {
            $hourMinute = Date::parse($date)->format("H:i");
        } catch (Exception $ex) {
            $hourMinute = hourMinuteFromTime($date, null);
        }
        return empty($hourMinute) ? $back : $prefix . $hourMinute . $suffix;
    }
}

if (!function_exists('printDateHourMinute')) {
    function printDateHourMinute($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("d F Y | H:i") . $suffix;
    }
}

if (!function_exists('printYear')) {
    function printYear($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("Y") . $suffix;
    }
}

if (!function_exists('printYear2')) {
    function printYear2($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . substr(printYear($date), -2) . $suffix;
    }
}

if (!function_exists('printDateOnly')) {
    function printDateOnly($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("d") . $suffix;
    }
}

if (!function_exists('printDateOnly1')) {
    function printDateOnly1($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("j") . $suffix;
    }
}

if (!function_exists('printDay')) {
    function printDay($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("l") . $suffix;
    }
}

if (!function_exists('printMonth')) {
    function printMonth($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("m") . $suffix;
    }
}

if (!function_exists('printMonth1')) {
    function printMonth1($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("n") . $suffix;
    }
}

if (!function_exists('printMonthFull')) {
    function printMonthFull($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("F") . $suffix;
    }
}

if (!function_exists('printMonthShort')) {
    function printMonthShort($date, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format("M") . $suffix;
    }
}

if (!function_exists('printMonthRome')) {
    function printMonthRome($date, $back = '', $prefix = '', $suffix = '')
    {
        $bulan = Date::parse($date)->format("n");
        return empty($date) ? $back : $prefix . num2roman($bulan) . $suffix;
    }
}

if (!function_exists('printDateFormat')) {
    function printDateFormat($date, $format, $back = '', $prefix = '', $suffix = '')
    {
        return empty($date) ? $back : $prefix . Date::parse($date)->format($format) . $suffix;
    }
}

if (!function_exists('printNewLine')) {
    function printNewLine($str, $back = '')
    {
        return empty($str) ? $back : str_replace("\n", '<br>', $str);
    }
}

if (!function_exists('dateFromDateTime')) {
    function dateFromDateTime($date, $back = '')
    {
        return empty($date) ? $back : Date::parse($date)->format("Y-m-d");
    }
}

if (!function_exists('timeFromDateTime')) {
    function timeFromDateTime($date, $back = '')
    {
        return empty($date) ? $back : Date::parse($date)->format("H:i:s");
    }
}

if (!function_exists('hourMinuteFromDateTime')) {
    function hourMinuteFromDateTime($date, $back = '')
    {
        return empty($date) ? $back : hourMinuteFromTime(timeFromDateTime($date));
    }
}

if (!function_exists('hourMinuteFromTime')) {
    function hourMinuteFromTime($time, $back = '', $checkZero = false)
    {
        if (preg_match("/.*(\d{2}\:\d{2}).*/U", $time, $matches)) {
            $time = $matches[1];
            return $checkZero && $time == '00:00' ? $back : $time;
        }
        return $back;
    }
}

if (!function_exists('exist')) {
    function exist($var, $back = '', $prefix = '', $suffix = '', $strict = false)
    {
        return ($strict && isset($var) || !empty($var)) ? $prefix . $var . $suffix : $back;
    }
}

if (!function_exists('existDate')) {
    function existDate($date, $back = '')
    {
        return empty($date) || $date == '0000-00-00' ? $back : $date;
    }
}

if (!function_exists('decodeJsonArray')) {
    function decodeJsonArray($jsonArray)
    {
        return !empty($jsonArray) ? json_decode($jsonArray, true) : [];
    }
}

if (!function_exists('dateRangeFromMonth')) {
    function dateRangeInMonth($yearMonth = null): array
    {
        $yearMonth ??= date('Y-m');
        $start = "{$yearMonth}-01";
        $end = date('Y-m-t', strtotime($start));
        return [$start, $end];
    }
}

if (!function_exists('snake2Words')) {
    function snake2Words($str)
    {
        return ucwords(str_replace('_', ' ', $str));
    }
}

if (!function_exists('snake2Kebab')) {
    function snake2Kebab($str)
    {
        return str_replace('_', '-', $str);
    }
}

if (!function_exists('num2roman')) {
    function num2roman($number)
    {
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}

if (!function_exists('removeMultiSpaces')) {
    function removeMultiSpaces($string)
    {
        return preg_replace('/\s{2,}/', ' ', $string);
    }
}

if (!function_exists('randomGen')) {
    function randomGen($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}

if (!function_exists('randomGen2')) {
    function randomGen2($length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}

if (!function_exists('randomGenAlpha')) {
    function randomGenAlpha($length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}

if (!function_exists('time2minutes')) {
    function time2minutes($time)
    {
        if (strpos($time, ':') === false) throw new Exception("Error", 1);

        [$hours, $minutes] = explode(':', $time);
        return $hours * 60 + $minutes;
    }
}

if (!function_exists('minutes2time')) {
    function minutes2time($minutes)
    {
        if ($minutes < 0) throw new Exception("Error", 1);

        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}

if (!function_exists('dateTimePlusTime')) {
    function dateTimePlusTime($time, $timePlus)
    {
        return dateTimePlusMinutes($time, time2minutes($timePlus));
    }
}

if (!function_exists('dateTimePlusMinutes')) {
    function dateTimePlusMinutes($time, $minutes)
    {
        return date('Y-m-d H:i:s', strtotime("{$time} + {$minutes} minutes"));
    }
}

if (!function_exists('printBerkoma')) {
    function printBerkoma(array $array = [], $separator = ', ', $lastSeparator = ' dan ')
    {
        return collect($array)->join($separator, $lastSeparator);
    }
}

if (!function_exists('jd')) {
    function jd($data)
    {
        echo json_encode($data);
        die;
    }
}

if (!function_exists('exist')) {
    function exist($var, $back = '', $prefix = '', $suffix = '')
    {
        return !empty($var) ? "{$prefix}{$var}{$suffix}" : $back;
    }
}

if (!function_exists('fileThumbnail')) {
    function fileThumbnail($url, $default = '', $size = 150)
    {
        if (!empty(goDriveId($url))) return goDriveThumb($url, $default, $size);
        return publicThumb($url, $default, $size);
    }
}

if (!function_exists('fileDownload')) {
    function fileDownload($url)
    {
        if (!empty(goDriveId($url))) return goDriveDownload($url);
        return $url;
    }
}

if (!function_exists('fileView')) {
    function fileView($url)
    {
        if (!empty(goDriveId($url))) return goDriveView($url);
        return $url;
    }
}

if (!function_exists('publicThumb')) {
    function publicThumb($url, $default = '', $size = 150)
    {
        if (empty($url)) return $default;

        $uploadFolder = 'upload';
        if (
            !Str::startsWith($url, asset($uploadFolder)) ||
            !Str::endsWith($url, [
                // JPG
                '.jpg', '.jpeg', '.jpe', '.jif', '.jfif', '.jfi',
                // PNG
                '.png',
                // GIF
                '.gif',
                // WEBP
                '.webp',
                // BMP
                '.bmp', '.dib',
            ])
        ) return $url;

        $thumbFolder = "thumbnail/{$size}";
        $thumbFolderPath = public_path($thumbFolder);
        if (!file_exists($thumbFolderPath)) mkdir($thumbFolderPath);

        $fileName = basename($url);
        $thumbPath = "{$thumbFolderPath}/{$fileName}";

        if (file_exists($thumbPath)) return asset("{$thumbFolder}/{$fileName}");

        // make thumbnail
        $filePath = Str::remove(asset('/'), $url);
        try {
            Image::make($filePath)->resize($size, $size, function ($constraint) {
                return $constraint->aspectRatio();
            })->save($thumbPath);
        } catch (Exception $ex) {
        }
        if (file_exists($thumbPath)) return asset("{$thumbFolder}/{$fileName}");

        return $url;
    }
}

if (!function_exists('goDriveId')) {
    function goDriveId($urlGDrive)
    {
        if (strpos($urlGDrive, 'uc?id=') !== false) {
            $e1 = explode('uc?id=', $urlGDrive, 2);
            return $e1[1];
        }
        if (strpos($urlGDrive, 'file/d/') !== false) {
            $e1 = explode('file/d/', $urlGDrive, 2);
            $e2 = explode('/', $e1[1]);
            return $e2[0];
        }

        return null;
    }
}

if (!function_exists('goDriveThumb')) {
    function goDriveThumb($urlGDrive, $default = '', $size = 150, $type = 's')
    {
        if (empty($urlGDrive)) return $default;

        $id = goDriveId($urlGDrive);
        if (!empty($id)) {
            return 'https://drive.google.com/thumbnail?sz=' . $type . $size . '&id=' . $id;
        }

        return $urlGDrive;
    }
}

if (!function_exists('goDriveView')) {
    function goDriveView($urlGDrive, $default = '#')
    {
        if (empty($urlGDrive)) return $default;

        $id = goDriveId($urlGDrive);
        if (!empty($id)) return 'https://drive.google.com/file/d/' . $id . '/view';

        return $urlGDrive;
    }
}

if (!function_exists('goDriveDownload')) {
    function goDriveDownload($urlGDrive, $default = '#')
    {
        if (empty($urlGDrive)) return $default;

        $id = goDriveId($urlGDrive);
        if (!empty($id)) return 'https://drive.google.com/uc?id=' . $id . '&export=download';

        return $urlGDrive;
    }
}

if (!function_exists('urlWithQueryParams')) {
    function urlWithQueryParams($to)
    {
        if (isDefaultRequest()) return url($to);

        $query = collect(request()->query())->whereNotNull()->toArray();
        $params = count($query) > 0 ? Arr::query(['redirect_params' => base64_encode(json_encode($query))]) : '';

        return url($to) . exist($params, prefix: '?');
    }
}

if (!function_exists('isDefaultRequest')) {
    function isDefaultRequest(): bool
    {
        $default = request()->get('default_filter', []);
        foreach (request()->query() as $key => $value) {
            if (
                !isset($default[$key]) ||
                (isset($default[$key]) && $default[$key] != $value)
            ) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('arrayWithKey')) {
    function arrayWithKey(array $array, callable $function = null)
    {
        return collect($array)->mapWithKeys($function ?? fn ($item) => [$item => $item])->toArray();
    }
}

if (!function_exists('selectOptions')) {
    function selectOptions(array $array, string|array|null $selected = null)
    {
        return collect($array)->map(function ($value, $key) use ($selected) {
            if (is_array($selected)) $isSelected = in_array($key, $selected);
            else $isSelected = $key == $selected;

            return '<option value="' . $key . '" ' . ($isSelected ? 'selected' : '') . '>' . $value . '</option>';
        })->implode('');
    }
}

if (!function_exists('isCurrentRoute')) {
    function isCurrentRoute($route)
    {
        return request()->route()->uri() == $route;
    }
}

if (!function_exists('errorMessage')) {
    function errorMessage(Exception $ex)
    {
        return !empty($ex->getMessage()) || !empty($ex->getCode()) ? "{$ex->getMessage()}\nLine {$ex->getLine()} on {$ex->getFile()}" : null;
    }
}

if (!function_exists('redirectTo')) {
    function redirectTo($to = null, $status = 302, $headers = [], $secure = null, $back = false, $with = [], $withInput = [])
    {
        $redirect = $back ? redirect()->back() : redirect($to, $status, $headers, $secure);
        throw new HttpResponseException($redirect->with($with)->withInput($withInput));
    }
}

if (!function_exists('responseJson')) {
    function responseJson($data = [], $status = 200, array $headers = [], $options = 0)
    {
        response()->json($data, $status, $headers, $options)->send();
        die;
    }
}

if (!function_exists('send_push_notification')) {
    function send_push_notification(int|string|array $userIds, $message, $title = null, $url = null)
    {
        // $fields['include_external_user_ids'] = is_array($userIds) ? collect($userIds)->map(fn ($userId) => encodeId($userId))->toArray() : [encodeId($userIds)];
        // $fields['channel_for_external_user_ids'] = 'push';

        // if (!empty($title)) {
        //     $fields['headings'] = ['en' => $title];
        // }
        // if (!empty($url)) {
        //     $fields['url'] = $url;
        // }

        // $push = OneSignal::sendPush($fields, $message);
        // \Log::info('Response Push Notif', $push, $fields);
        // return $push;
    }
}

// get instance id from session
if (!function_exists('getInstanceId')) {
    function getInstanceId()
    {
        return session()->get('instance_id');
    }
}
