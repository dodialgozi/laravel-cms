@extends('frontend.layouts.app')

@section('title', $title)

@section('content')
<div class="owl-carousel owl-carousel-light owl-carousel-light-init-fadeIn owl-theme manual dots-inside dots-modern dots-modern-lg dots-horizontal-center show-dots-hover show-dots-xs nav-style-1 nav-inside nav-inside-plus nav-dark nav-lg nav-font-size-lg show-nav-hover mb-0"
    data-plugin-options="{'autoplayTimeout': 7000, 'loop': true}"
    data-dynamic-height="['700px','700px','700px','550px','500px']" style="height: 700px;">
    <div class="owl-stage-outer">
        <div class="owl-stage">

            @foreach ($slider as $item)
            <div class="owl-item position-relative"
                style="background-image: url({{ $item->first_image }}); background-size: cover; background-position: center;">
                <div class="container h-100">
                    <div class="row h-100">
                        <div class="col-lg-6">
                            <div class="d-flex flex-column justify-content-center h-100">
                                <h2 class="custom-font-slider-1 mb-0 font-weight-bold appear-animation"
                                    data-appear-animation="blurIn" data-appear-animation-delay="500"
                                    data-plugin-options="{'minWindowWidth': 0}">{{ $item->post_title }}</h2>
                                <div class="divider divider-primary divider-small text-start mt-2 mb-4 mx-0 appear-animation"
                                    data-appear-animation="fadeInUpShorter" data-appear-animation-delay="750">
                                    <hr class="my-0">
                                </div>
                                <p class="text-3-5 line-height-9 appear-animation"
                                    data-appear-animation="fadeInUpShorter" data-appear-animation-delay="1000">{{
                                    strip_tags($item->post_content) }}</p>

                                {{-- <div class="appear-animation" data-appear-animation="fadeInUpShorter"
                                    data-appear-animation-delay="1250">
                                    <div class="d-flex align-items-center mt-2">
                                        <!-- <a href="#" class="btn btn-dark btn-modern text-uppercase font-weight-bold text-2 py-3 btn-px-4">Learn More</a> -->
                                        <a href="#"
                                            class="btn btn-primary btn-modern text-uppercase font-weight-bold text-2 py-3 btn-px-4">Book
                                            Now</a>
                                    </div>
                                </div> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="owl-nav">
        <button type="button" role="presentation" class="owl-prev" aria-label="Previous"></button>
        <button type="button" role="presentation" class="owl-next" aria-label="Next"></button>
    </div>
    <div class="owl-dots mb-5">
        <button role="button" class="owl-dot active"><span></span></button>
        <button role="button" class="owl-dot"><span></span></button>
    </div>
</div>

<section class="section bg-transparent section-no-border my-0">
    <div class="container pt-3 pb-4">
        <div class="row">
            <div class="col text-center">
                <div class="appear-animation" data-appear-animation="blurIn" data-appear-animation-delay="0">
                    <h2 class="mb-0 font-weight-bold">Layanan Unggulan Sirri Clinic</h2>
                    <div class="divider divider-primary divider-small mt-2 mb-4 text-center">
                        <hr class="my-0 mx-auto">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            @forelse ($layanan_unggulan as $item)
            <div class="col-lg-4">
                <div class="feature-box feature-box-style-2 mb-4 appear-animation" data-appear-animation="fadeInUp"
                    data-appear-animation-delay="0">
                    <div class="feature-box-icon mt-3">
                        @if (!empty($item->first_image))
                        <img width="50" height="50" src="{{ $item->first_image }}" alt="{{ $item->post_title }}" />
                        @endif
                    </div>
                    <div class="feature-box-info ms-3">
                        <h4 class="mb-2">{{ $item->post_title }}</h4>
                        {!! $item->post_content !!}
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">Belum ada layanan unggulan</div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="parallax section section-text-light section-parallax section-center my-0" data-plugin-parallax
    data-plugin-options="{'speed': 1.5, 'parallaxHeight': '200%'}"
    data-image-src="{{ asset('frontend/assets/img/patterns/counter.jpeg') }}">
    <div class="container position-relative">
        <div class="row py-5 counters counters-text-light">
            @foreach ($counter as $item)
            <div class="col-sm-6 col-lg-3 mb-4 mb-lg-0">
                <div class="counter">
                    <img width="44" height="50" src="{{ $item->first_image }}" alt="{{ $item->post_title }}" class="img-fluid" />
                    <strong class="pt-3 custom-font-secondary font-weight-bold" data-to="{{ strip_tags($item->post_content) }}" data-append="+">0</strong>
                    <label class="pt-2 text-4 opacity-7">{{ $item->post_title }}</label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section bg-transparent section-no-border my-0">
    <div class="container pt-3 pb-4">
        <div class="row">
            <div class="col text-center">
                <div class="appear-animation" data-appear-animation="blurIn" data-appear-animation-delay="0">
                    <h2 class="mb-0 font-weight-bold">Dokter Professional Sirri Clinic</h2>
                    <div class="divider divider-primary divider-small mt-2 mb-4 text-center">
                        <hr class="my-0 mx-auto">
                    </div>
                </div>

                <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="300">

                    <div class="row">

                        @if (!empty($setting['dokter_nama_1']) || !empty($setting['dokter_foto_1']))
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col">
                                    <a href="javascript:void(0)">
                                        <img src="{{ $setting['dokter_foto_1'] }}" class="img-fluid"
                                            alt="{{ $setting['dokter_nama_1'] }}" />
                                    </a>
                                </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col pt-3">
                                    <p class="text-color-dark text-4-5 font-weight-bold mb-1">{{
                                        $setting['dokter_nama_1']
                                        }}</p>
                                    <p
                                        class="d-block text-color-default font-weight-semibold line-height-1 positive-ls-2 text-2 text-uppercase mb-3">
                                        Dokter</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (!empty($setting['dokter_nama_2']) || !empty($setting['dokter_foto_2']))
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col">
                                    <a href="javascript:void(0)">
                                        <img src="{{ $setting['dokter_foto_2'] }}" class="img-fluid"
                                            alt="{{ $setting['dokter_nama_2'] }}" />
                                    </a>
                                </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col pt-3">
                                    <p class="text-color-dark text-4-5 font-weight-bold mb-1">{{
                                        $setting['dokter_nama_2']
                                        }}</p>
                                    <p
                                        class="d-block text-color-default font-weight-semibold line-height-1 positive-ls-2 text-2 text-uppercase mb-3">
                                        Dokter</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (!empty($setting['dokter_nama_3']) || !empty($setting['dokter_foto_3']))
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col">
                                    <a href="javascript:void(0)">
                                        <img src="{{ $setting['dokter_foto_3'] }}" class="img-fluid"
                                            alt="{{ $setting['dokter_nama_3'] }}" />
                                    </a>
                                </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col pt-3">
                                    <p class="text-color-dark text-4-5 font-weight-bold mb-1">{{
                                        $setting['dokter_nama_3']
                                        }}</p>
                                    <p
                                        class="d-block text-color-default font-weight-semibold line-height-1 positive-ls-2 text-2 text-uppercase mb-3">
                                        Dokter</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 bg-primary order-2 order-lg-1 p-0"
            style="background: url({{ asset('frontend/assets/img/patterns/fancy.jpg') }}); background-size: cover; background-position: center;">
            <div class="h-100 m-0">
                <div class="row m-0">
                    <div class="col-half-section col-half-section-right text-color-light py-5 ms-auto">
                        <div class="p-3">
                            <div class="appear-animation" data-appear-animation="fadeInRightShorter"
                                data-appear-animation-delay="200">
                                <h2 class="mb-0 font-weight-bold text-light">Testimonials</h2>
                                <div class="divider divider-dark divider-small mt-2 mb-4">
                                    <hr class="my-0 me-auto">
                                </div>

                                <div class="owl-carousel owl-theme dots-align-left dots-light dots-modern custom-dots-modern-1 dots-modern-lg pt-3"
                                    data-plugin-options="{'responsive': {'0': {'items': 1}, '479': {'items': 1}, '768': {'items': 1}, '979': {'items': 1}, '1199': {'items': 1}}, 'loop': true, 'autoHeight': true}">

                                    @forelse ($testimonials as $item)
                                    <div>
                                        <div
                                            class="testimonial testimonial-style-2 testimonial-with-quotes testimonial-quotes-light testimonial-remove-right-quote mb-0">
                                            <blockquote class="opacity-7 pb-3">
                                                <p
                                                    class="text-start text-color-light custom-font-secondary text-3 line-height-10 fst-italic pb-0 mb-0">
                                                    {{ strip_tags($item->post_content) }}
                                                </p>
                                            </blockquote>
                                            <div class="testimonial-author text-start ps-5 ms-3">
                                                <strong class="text-color-light">{{ $item->post_title }}</strong>
                                                <p class="text-color-light mb-0 text-start">Sirri Clinic
                                                    Client</p>
                                            </div>
                                        </div>
                                    </div>
                                    @empty

                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section bg-transparent section-no-border my-0">
    <div class="container pt-3 pb-4">
        <div class="row">
            <div class="col text-center">
                <div class="appear-animation" data-appear-animation="blurIn" data-appear-animation-delay="0">
                    <h2 class="mb-0 font-weight-bold">Artikel Terbaru</h2>
                    <div class="divider divider-primary divider-small mt-2 mb-4 text-center">
                        <hr class="my-0 mx-auto">
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-3 mt-1 appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="300">
            @forelse ($posts as $post)
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 p-relative">
                        <a href="{{ url('artikel') }}/{{ $post->post_slug }}" class="text-decoration-none text-light">
                            <span
                                class="position-absolute bottom-10 right-0 d-flex justify-content-end w-100 py-3 px-4 z-index-3">
                                <span
                                    class="text-center bg-primary border-radius text-color-light font-weight-semibold line-height-2 px-3 py-2">
                                    <span class="position-relative z-index-2">
                                        <span class="text-8">{{ printDateOnly($post->post_date) }}</span>
                                        <span class="custom-font-secondary d-block text-1 positive-ls-2 px-1">{{
                                            Str::upper(printMonthShort($post->post_date)) }}</span>
                                    </span>
                                </span>
                            </span>
                            <img src="{{ $post->medium_thumbnail ?? $post->first_image ?? 'https://via.placeholder.com/261x196.png?text=No+Image' }}"
                                class="img-fluid" alt="Grand Opening Sirri Clinic" />
                        </a>
                    </div>
                    <div class="col-md-6">
                        <span class="d-block text-color-grey font-weight-semibold positive-ls-2 text-2">BY
                            <a href="javascript:void(0);">{{ $post->user->user_name }}</a></span>
                        <h3 class="custom-font-primary mb-2">
                            <a href="{{ url('artikel') }}/{{ $post->post_slug }}"
                                class="text-dark text-transform-none font-weight-bold text-1 line-height-3 text-color-hover-primary text-decoration-none">
                                {{ $post->post_title }}
                        </h3>
                        </a>
                        </h3>
                        <p class="mb-2">{{ Str::limit($post->post_excerpt, 85, '...') }}</p>
                        <a href="{{ url('artikel') }}/{{ $post->post_slug }}"
                            class="custom-view-more d-inline-flex font-weight-medium text-color-primary">
                            Baca Selengkapnya
                            <img width="27" height="27" src="{{ asset('frontend/assets/img/icons') }}/arrow-right.svg"
                                alt="" data-icon
                                data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-fill-color-primary ms-2'}"
                                style="width: 27px;" />
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">Belum ada artikel terbaru</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section border-0 lazyload my-0 mt-5 p-0">
    <div class="owl-carousel owl-theme m-0">
        @foreach ($gallery as $item)
            <div>
                <img src="{{ $item->first_image }}" alt="{{ $item->post_title }}" class="img-fluid">
            </div>
        @endforeach
    </div>
</section>
@endsection