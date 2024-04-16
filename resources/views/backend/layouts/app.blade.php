@extends('backend.layouts.master')

@section('body_attribute', new Illuminate\View\ComponentAttributeBag(['data-sidebar' => 'lc']))

@section('body')
<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner-chase">
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
        </div>
    </div>
</div>
<div id="layout-wrapper">
    <header id="page-topbar">
        @include('backend.layouts.topbar')
    </header>

    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">
        <div data-simplebar class="h-100">
            <!--- Sidemenu -->
            <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                @include('backend.layouts.sidebar')
            </div>
            <!-- Sidebar -->
        </div>
    </div>
    <!-- Left Sidebar End -->



    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">@yield('title')</h4>

                            @if(empty(view()->yieldContent('hide_breadcrumb', false)))
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    @if(url()->current() != url('index'))
                                    <li class="breadcrumb-item"><a href="{{ url('index') }}">Dashboards</a></li>
                                    @endif
                                    @yield('breadcrumbs')
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>

                @yield('content')

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        @yield('modal')

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>document.write(new Date().getFullYear())</script> © {{ env('APP_NAME') }}.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Design & Develop by {{ env('APP_COMPANY') }}
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->
</div>
@endsection

<x-modal id="modal-doc-preview" size="xl" :withoutHeader=true :withoutFooter=true />
<x-modal id="modal-image-preview" size="lg" :withoutHeader=true :withoutFooter=true />
<x-modal id="basic-modal" size="xl" :withoutHeader=false :withoutFooter=false />