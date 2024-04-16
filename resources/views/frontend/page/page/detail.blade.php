@extends('frontend.layouts.app')

@section('title', $page->page_title)

@section('content')
<section class="page-header page-header-modern bg-color-quaternary page-header-lg border-0 m-0">
    <div class="container position-relative z-index-2">
        <div class="row text-center text-md-start py-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1 class="font-weight-bold text-color-dark text-10 mb-0">{{ $page->page_title }}</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb breadcrumb-dark font-weight-bold d-block text-md-end text-4 mb-0">
                    <li><a href="{{ url('/') }}" class="text-decoration-none text-dark">Beranda</a></li>
                    <li class="text-upeercase active text-color-primary">{{ $page->page_title }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="container py-5 my-3">
    <div class="row">
        <div class="col-lg-12 mb-5 mb-lg-0">

            <article>
                <div class="card border-0">
                    <div class="card-body z-index-1 p-0">
                        <div class="card-body p-0">
                            {!! $page->page_content !!}
                        </div>
                    </div>
                </div>
            </article>

        </div>
    </div>
</div>
@endsection