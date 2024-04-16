<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Instance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\DashboardTrendingPost;
use App\Http\Resources\PostCollection;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');

        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));

        $posts = Post::with('user')
            ->latest()
            ->where('instance_id', $instance->instance_id)
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->when($search, function ($query) use ($search) {
                $query->where('post_title_id', 'like', '%' . $search . '%')
                    ->orWhere('post_title_en', 'like', '%' . $search . '%');
            })
            ->paginate(5);

        return PostResource::collection($posts);
    }

    public function show(Request $request)
    {
        $post = Post::with('user')
            ->where('post_slug_id', $request->route('slug'))
            ->orWhere('post_slug_en', $request->route('slug'))
            ->firstOrFail();

        return PostResource::make($post);
    }

    public function popular()
    {
        $trendingPost = DashboardTrendingPost::with('post')
            ->where('month', date('m'))
            ->where('year', date('Y'))
            ->orderBy('summary', 'desc')
            ->limit(5)
            ->get();

        $posts = $trendingPost->map(function ($trending) {
            return $trending->post;
        });

        return PostCollection::make($posts);
    }

    public function recent(Request $request)
    {
        $instance = Instance::firstWhere('instance_domain', $request->route('domain'));

        $posts = Post::with('user')
            ->latest()
            ->where('instance_id', $instance->instance_id)
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->limit(5)
            ->get();

        return PostCollection::make($posts);
    }
}
