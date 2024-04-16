<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Instance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));

        $categories = Category::with(['children', 'children.children'])
            ->where('instance_id', $instance->instance_id)
            ->where('parent_id', null)
            ->orderBy('category_name_id', 'asc')
            ->where('category_active', 1)
            ->get();

        return CategoryResource::collection($categories);
    }

    public function show(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));

        $category = Category::with(['children', 'children.children'])
            ->where('instance_id', $instance->instance_id)
            ->where('category_slug_id', $request->route('slug'))
            ->orWhere('category_slug_en', $request->route('slug'))
            ->firstOrFail();

        return CategoryResource::make($category);
    }
}
