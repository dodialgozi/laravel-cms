<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@hasSection('title') @yield('title') - @endif {{ getSetting('sistem_app', env('APP_NAME')) }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="SISTEM HRM" name="description" />
        <meta content="{{ getSetting('sistem_company', env('APP_COMPANY')) }}" name="author" />
        
        <!-- App favicon -->
        <link rel="shortcut icon" href="{!! fileThumbnail(getSetting('sistem_icon', asset('backend/assets/images/favicon.ico')), '') !!}">

        <!-- Bootstrap Css -->
        <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mb-5">
                            <h1 class="display-2 fw-medium">
                                @php
                                    $code = app()->view->getSections()['code'];
                                    $codes = str_split($code);
                                @endphp
                                {!! $codes[0] . ($codes[1] == 0 ? '<i class="bx bx-buoy bx-spin text-primary display-3"></i>' : '0') . $codes[2] !!}
                            </h1>
                            <h4 class="text-uppercase">@yield('message')</h4>
                            <div class="mt-5 text-center">
                                @if($code == 401)
                                <a class="btn btn-primary waves-effect waves-light" href="{{ url('login') }}">Halaman Login</a>
                                @else
                                <a class="btn btn-primary waves-effect waves-light" href="{{ url('index') }}">Kembali ke Dashboard</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-xl-6">
                        <div>
                            <img src="{{ asset('backend/assets/images/error-img.png') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
