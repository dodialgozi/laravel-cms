<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BasicController as Controller;
use App\Models\CustomPost;
use App\Models\CustomPostType;
use App\Models\Post;

class HomeController extends Controller
{
    protected $title = 'Klinik Kecantikan Pekanbaru';
    protected $view = 'frontend.page.home';

    public function index()
    {
        $posts = Post::with('user')
            ->latest()
            ->where('post_status', 'publish')
            ->take(4)
            ->get();

        $slider = CustomPost::with('custom_post_type')
            ->whereHas('custom_post_type', function ($query) {
                $query->where('post_type_code', 'slider');
            })
            ->where('post_status', 'publish')
            ->get();

        $layanan_unggulan = CustomPost::with('custom_post_type')
            ->whereHas('custom_post_type', function ($query) {
                $query->where('post_type_code', 'layanan-unggulan');
            })
            ->where('post_status', 'publish')
            ->get();

        $counter = CustomPost::with('custom_post_type')
            ->whereHas('custom_post_type', function ($query) {
                $query->where('post_type_code', 'counter');
            })
            ->where('post_status', 'publish')
            ->get();

        $testimonials = CustomPost::with('custom_post_type')
            ->whereHas('custom_post_type', function ($query) {
                $query->where('post_type_code', 'testimoni');
            })
            ->latest()
            ->where('post_status', 'publish')
            ->take(5)
            ->get();

        $gallery = CustomPost::with('custom_post_type')
            ->whereHas('custom_post_type', function ($query) {
                $query->where('post_type_code', 'gallery');
            })
            ->latest()
            ->where('post_status', 'publish')
            ->take(10)
            ->get();

        return view($this->getView('index'), [
            'posts' => $posts,
            'slider' => $slider,
            'layanan_unggulan' => $layanan_unggulan,
            'counter' => $counter,
            'testimonials' => $testimonials,
            'gallery' => $gallery
        ]);
    }
}
