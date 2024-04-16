<?php

namespace App\Http\Controllers\Api;

use App\Models\Partner;
use App\Models\Instance;
use App\Models\Lecturer;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\InstanceResource;
use App\Http\Resources\LecturerResource;
use App\Http\Resources\SiteSettingResource;
use App\Models\Alumnus;


class InstanceController extends Controller
{
    public function index(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        return InstanceResource::make($instance);
    }

    public function partners(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $partners = Partner::where('instance_id', $instance->id)->get();
        return PartnerResource::collection($partners);
    }

    public function settings(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $siteSettings = SiteSetting::where('instance_id', $instance->id)->get();
        return SiteSettingResource::collection($siteSettings);
    }

    public function lecturers(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $lectures = Lecturer::where('instance_id', $instance->id)->get();

        return LecturerResource::collection($lectures);
    }

    public function alumni(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $alumni = Alumnus::where('instance_id', $instance->id)->get();

        return response()->json($alumni);
    }

    public function contact(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $contacts = $instance->contacts;

        return response()->json($contacts);
    }
}
