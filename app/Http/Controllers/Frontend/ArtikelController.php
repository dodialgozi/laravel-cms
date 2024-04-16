<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BasicController as Controller;
use App\Models\Category;
use App\Models\Post;

class ArtikelController extends Controller
{
    protected $title = 'Artikel';
    protected $view = 'frontend.page.artikel';

    public function index()
    {
        $search = request()->query('cari');

        $posts = Post::with('user')
            ->latest()
            ->where('post_status', 'publish')
            ->when($search, function ($query) use ($search) {
                $query->where('post_title', 'like', '%' . $search . '%');
            })
            ->paginate(5);

        $newest_post = Post::with('user')
            ->latest()
            ->where('post_status', 'publish')
            ->limit(5)
            ->get();

        $kategori = Category::with(['children', 'children.children'])
            ->where('parent_id', null)
            ->orderBy('category_name_id', 'asc')
            ->where('category_active', 1)
            ->get();

        return view($this->getView('index'), [
            'posts' => $posts,
            'newest_post' => $newest_post,
            'kategori' => $kategori
        ]);
    }

    public function show($slug)
    {
        $post = Post::with('user')
            ->where('post_status', 'publish')
            ->where('post_slug', $slug)
            ->firstOrFail();

        $post->update([
            'post_view' => $post->post_view + 1
        ]);


        $newest_post = Post::with('user')
            ->latest()
            ->where('post_status', 'publish')
            ->limit(5)
            ->get();

        $kategori = Category::with(['children', 'children.children'])
            ->where('parent_id', null)
            ->orderBy('category_name_id', 'asc')
            ->where('category_active', 1)
            ->get();

        return view($this->getView('detail'), [
            'post' => $post,
            'newest_post' => $newest_post,
            'kategori' => $kategori
        ]);
    }
}
