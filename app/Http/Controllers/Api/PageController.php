<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\Instance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));

        $pages = Page::where('instance_id', $instance->instance_id)
            ->get();

        return PageResource::collection($pages);
    }

    public function show(Request $request)
    {
        $page = Page::where('page_slug_id', $request->route('slug'))
            ->orWhere('page_slug_en', $request->route('slug'))
            ->firstOrFail();

        return PageResource::make($page);
    }
}
