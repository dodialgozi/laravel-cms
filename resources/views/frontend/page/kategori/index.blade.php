@extends('frontend.layouts.app')

@section('title', $title . ' ' . $category->category_name)

@section('content')
<section class="page-header page-header-modern bg-color-quaternary page-header-lg border-0 m-0">
    <div class="container position-relative z-index-2">
        <div class="row text-center text-md-start py-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1 class="font-weight-bold text-color-dark text-10 mb-0">{{ $title }}: {{ $category->category_name }}</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb breadcrumb-dark font-weight-bold d-block text-md-end text-4 mb-0">
                    <li><a href="{{ url('/') }}" class="text-decoration-none text-dark">Beranda</a></li>
                    <li class="text-upeercase active text-color-primary">{{ $title }}: {{ $category->category_name }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="container py-5 my-3">
    <div class="row">
        <div class="col-lg-8 mb-5 mb-lg-0">

            @forelse ($posts as $item)
            <article class="mb-5">
                <div class="card border-0 border-radius-0 custom-box-shadow-1">
                    <div class="card-img-top">
                        <a href="{{ url('artikel') }}/{{ $item->posts->post_slug }}">
                            <img class="card-img-top border-radius-0 hover-effect-2"
                                src="{{ $item->posts->medium_thumbnail ?? $item->posts->first_image ?? 'https://via.placeholder.com/261x196.png?text=No+Image' }}"
                                alt="Grand Opening Sirri Clinic">
                        </a>
                    </div>
                    <div class="card-body bg-light px-0 py-4 z-index-1">
                        <p class="text-uppercase text-color-default text-1 mb-1 pt-1">
                            <time pubdate datetime="{{ printDateFormat($item->posts->post_date, 'd-m-y') }}">{{
                                printDate($item->posts->post_date) }}</time>
                            <span class="opacity-3 d-inline-block px-2">|</span>
                            {{ $item->posts->user->user_name }}
                            <span class="opacity-3 d-inline-block px-2">|</span>
                            {{ $item->posts->post_view ?? 0 }} <i class="fas fa-eye ms-1"></i>
                        </p>
                        <div class="card-body p-0">
                            <h4 class="card-title alternative-font-4 font-weight-semibold text-5 mb-3"><a
                                    class="text-color-dark text-color-hover-primary text-decoration-none font-weight-bold text-3"
                                    href="{{ url('artikel') }}/{{ $item->posts->post_slug }}">{{ $item->posts->post_title }}</a></h4>
                            <p class="card-text mb-2 post-excertp">{{ $item->posts->post_excerpt }}</p>
                            <a href="{{ url('artikel') }}/{{ $item->posts->post_slug }}"
                                class="custom-view-more d-inline-flex font-weight-medium text-color-primary">
                                Baca Selengkapnya
                                <img width="27" height="27"
                                    src="{{ asset('frontend/assets/img/icons') }}/arrow-right.svg" alt="" data-icon
                                    data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-fill-color-primary ms-2'}"
                                    style="width: 27px;" />
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="text-muted text-center">
                    Artikel belum tersedia!
            </div>
            @endforelse

            <div class="row">
                <div class="col">
                    {{ $posts->links() }}
                </div>
            </div>

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

@push('script')
<script>
    $(document).ready(function () {
        $('.post-excertp').each(function () {
            var text = $(this).text();
            if (text.slice(-1) == '.' || text.slice(-1) == ',' || text.slice(-1) == '?' || text.slice(-1) == '!') {
                $(this).text(text.slice(0, -1));
            }

            $(this).append('...');
        });
    });
</script>
@endpush