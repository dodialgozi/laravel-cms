@extends('frontend.layouts.app')

@section('title', $post->post_title)

@section('content')
<section class="page-header page-header-modern bg-color-quaternary page-header-lg border-0 m-0">
    <div class="container position-relative z-index-2">
        <div class="row text-center text-md-start py-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1 class="font-weight-bold text-color-dark text-10 mb-0">{{ $post->post_title }}</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb breadcrumb-dark font-weight-bold d-block text-md-end text-4 mb-0">
                    <li><a href="{{ url('/') }}" class="text-decoration-none text-dark">Beranda</a></li>
                    <li><a href="{{ url('artikel') }}" class="text-decoration-none text-dark">Artikel</a></li>
                    <li class="text-upeercase active text-color-primary">{{ $post->post_title }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="container py-5 my-3">
    <div class="row">
        <div class="col-lg-8 mb-5 mb-lg-0">

            <article>
                <div class="card border-0">
                    <div class="card-body z-index-1 p-0">
                        <p class="text-uppercase text-color-default text-1 mb-1 pt-1">
                            <time pubdate datetime="{{ printDateFormat($post->post_date, 'd-m-y') }}">{{
                                printDate($post->post_date) }}</time>
                            <span class="opacity-3 d-inline-block px-2">|</span>
                            {{ $post->user->user_name }}
                            <span class="opacity-3 d-inline-block px-2">|</span>
                            {{ $post->post_view ?? 0 }} <i class="fas fa-eye ms-1"></i>
                        </p>

                        @if ($post->first_image)
                        <div class="post-image pb-4">
                            <img class="card-img-top custom-border-radius-1" src="{{ $post->first_image }}"
                                alt="{{ $post->post_title }}">
                        </div>
                        @endif

                        <div class="card-body p-0">
                            {!! $post->post_content !!}
                        </div>
                    </div>
                </div>
            </article>

        </div>
        <div class="blog-sidebar col-lg-4 pt-4 pt-lg-0">
            <aside class="sidebar">
                @include('frontend.components.sidebar-search')
                
                @include('frontend.components.sidebar-artikel-terbaru', ['newest_post' => $newest_post])

                @include('frontend.components.sidebar-kategori')
            </aside>
        </div>
    </div>
</div>
@endsection