<?php

namespace App\Http\Helper;

class Location
{
    public static function distance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $miles = $distance * 60 * 1.1515;
        $meters = $miles * 1609.344;
        return $meters;
    }
}
