<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BasicController as Controller;
use App\Models\Page;

class PageController extends Controller
{
    protected $title = 'Page';
    protected $view = 'frontend.page.page';

    public function show($slug)
    {
        $page = Page::where('page_slug', $slug)->firstOrFail();

        return view($this->getView('detail'), [
            'page' => $page
        ]);
    }
}
