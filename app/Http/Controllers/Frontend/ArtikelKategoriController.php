<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BasicController as Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostCategory;

class ArtikelKategoriController extends Controller
{
    protected $title = 'Kategori';
    protected $view = 'frontend.page.kategori';

    public function show($slug)
    {
        $category = Category::where('category_slug', $slug)->firstOrFail();

        $posts = PostCategory::with('posts')
            ->where('category_id', $category->category_id)
            ->whereHas('posts', function ($query) {
                $query->where('post_status', 'publish');
            })
            ->orderBy('cat_id', 'desc')
            ->paginate(5);

        $newest_post = Post::with('user')
            ->latest()
            ->where('post_status', 'publish')
            ->limit(5)
            ->get();

        $kategori = Category::with(['children', 'children.children'])
            ->where('parent_id', null)
            ->orderBy('category_name', 'asc')
            ->where('category_active', 1)
            ->get();

        return view($this->getView('index'), [
            'category' => $category,
            'posts' => $posts,
            'newest_post' => $newest_post,
            'kategori' => $kategori,
        ]);
    }
}
