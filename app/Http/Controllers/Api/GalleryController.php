<?php

namespace App\Http\Controllers\Api;

use App\Models\Gallery;
use App\Models\Instance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryResource;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));

        $galleries = Gallery::where('instance_id', $instance->instance_id)
            ->get();

        return GalleryResource::collection($galleries);
    }

    public function show(Request $request)
    {
        $gallery = Gallery::where('gallery_slug_id', $request->route('slug'))
            ->orWhere('gallery_slug_en', $request->route('slug'))
            ->firstOrFail();

        return GalleryResource::make($gallery);
    }
}
