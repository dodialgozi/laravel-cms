@php $rand = 'plTrgp68WcjbjKP2bqHA'; @endphp
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@hasSection('title') @yield('title') - @endif {{ getSetting('sistem_app', env('APP_NAME')) }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="SISTEM HRM" name="description" />
        <meta content="{{ getSetting('sistem_company', env('APP_COMPANY')) }}" name="author" />

        <meta name="theme-color" content="#990000">

		<meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{!! fileThumbnail(getSetting('sistem_icon', asset('backend/assets/images/favicon.ico')), '') !!}">
        <link rel="icon" sizes="192x192" href="{!! fileThumbnail(getSetting('sistem_icon', asset('backend/assets/images/favicon.ico')), '') !!}">

        <link href="{{ asset('backend/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('backend/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('backend/assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('backend/assets/libs/@chenfengyuan/datepicker/datepicker.min.css') }}">
        <link href="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('backend/assets/libs/json-viewer/jquery.json-viewer.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('backend/assets/libs/leaflet/leaflet.css') }}">

        <!-- Bootstrap Css -->
        <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        <link href="{{ asset('backend/assets/css/custom.css?v=' . $rand) }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('backend/assets/css/gcis-theme.css?v=' . $rand) }}" rel="stylesheet" type="text/css" />

        @yield('page_style')
        @stack('style')

        <script>
            const assetsUrl = "{{ asset('backend') }}";
            const baseUrl = "{{ url('/') }}";
            const preloaderDelay = 100;
        </script>

        @yield('head_script')
    </head>

    <body @yield('body_attribute')>
        <!-- Begin page -->
        @yield('body')
        <!-- END layout-wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Modals -->
        @stack('modal')

        <!-- JAVASCRIPT -->
        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>

        <script src="{{ asset('backend/assets/libs/moment/min/moment-with-locales.min.js') }}"></script>
        <script>moment.locale('id');</script>

        <script src="{{ asset('backend/assets/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

        <script src="{{ asset('backend/assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('backend/assets/js/accounting.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>

        <script src="{{ asset('backend/assets/js/app.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/parsleyjs/parsley.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/parsleyjs/i18n/id.js') }}"></script>
        <script src="{{ asset('backend/assets/js/pages/form-validation.init.js') }}"></script>
        <script src="{{ asset('backend/assets/js/jquery.serializejson.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/json-viewer/jquery.json-viewer.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/leaflet/leaflet.js') }}"></script>

        <script src="{{ asset('backend/assets/js/basic.js?v=' . $rand) }}"></script>

        @stack('script')
    </body>
</html>