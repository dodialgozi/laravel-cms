<?php

namespace App\Http\Controllers\Api;

use App\Models\Instance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\AlumnusResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\InstanceResource;
use App\Http\Resources\LecturerResource;
use App\Http\Resources\SiteSettingResource;


class InstanceController extends Controller
{
    public function index(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        return InstanceResource::make($instance);
    }

    public function menu(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $menus = $instance->menus;
        return MenuResource::collection($menus);
    }

    public function partners(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $partners = $instance->partners;
        return PartnerResource::collection($partners);
    }

    public function settings(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $siteSettings = $instance->siteSettings;
        return SiteSettingResource::collection($siteSettings);
    }

    public function lecturers(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $lectures = $instance->lecturers;

        return LecturerResource::collection($lectures);
    }

    public function alumni(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $alumni = $instance->alumni;

        return AlumnusResource::collection($alumni);
    }

    public function contacts(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $contacts = $instance->contacts;

        return ContactResource::collection($contacts);
    }

    public function courses(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $courses = $instance->courses;

        return CourseResource::collection($courses);
    }

    public function sliders(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));
        $sliders = $instance->sliders;

        return SliderResource::collection($sliders);
    }
}
