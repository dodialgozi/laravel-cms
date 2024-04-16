<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Basic -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>
		@hasSection ('title')
		@yield('title') | {{ !empty($setting['sistem_app']) ? $setting['sistem_app'] : env('APP_NAME') }}
		@else
		{{ !empty($setting['sistem_app']) ? $setting['sistem_app'] : env('APP_NAME') }}
		@endif
	</title>

	<meta name="keywords" content="Klnik Kecantikan, Sirri, Sirri Clinic" />
	<meta name="description" content="Klinik kecantikan terbesar dan terlengkap di kota Pekanbaru. Yuk tampil cantik dengan perawatan terbaik dari klinik kami!">
	<meta name="author" content="PT. Garuda Cyber Indonesia">

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ !empty($setting['sistem_icon']) ? $setting['sistem_icon'] : '#' }}" type="image/x-icon" />
	<link rel="apple-touch-icon" href="{{ !empty($setting['sistem_icon']) ? $setting['sistem_icon'] : '#' }}">

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

	@include('frontend.layouts.style')

</head>

<body>

	<div class="body">
		<header id="header"
			data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyStartAt': 107, 'stickySetTop': '-107px', 'stickyChangeLogo': true}">
			<div class="header-body border-color-primary border-top-0 box-shadow-none">

				@include('frontend.layouts.topbar')
				@include('frontend.layouts.navbar')

			</div>
		</header>

		<div role="main" class="main">
			@yield('content')
		</div>

		@include('frontend.layouts.footer')
	</div>

	@include('frontend.layouts.script')

</body>

</html>