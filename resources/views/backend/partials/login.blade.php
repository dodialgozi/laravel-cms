@extends('backend.layouts.master')

@section('title', 'Masuk')

@section('body_attribute', new Illuminate\View\ComponentAttributeBag(['data-sidebar' => 'gci']))

@section('body')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft theme">
                            <div class="row">
                                <div class="col-12">
                                    <br>
                                    <div class="d-flex justify-content-center">
                                        <img src="{!! fileThumbnail(getSetting('sistem_logo', asset('backend/assets/images/logo-light.svg')), '', 300) !!}" height="100" referrerpolicy="no-referrer">
                                        <hr>
                                    </div>

                                    <div class="text-white p-4">
                                        <h5 class="text-white text-center">{{ getSetting('sistem_company', '') }}</h5>
                                        @if (!empty(($address = getSetting('sistem_company_address', null))))
                                            <p class="text-center">{{ $address }}.</p>
                                        @endif
                                        <p class="text-center">Silahkan login menggunakan akses Anda untuk masuk ke
                                            dashboard.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <br>
                            <div class="p-2">
                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session()->get('error') }}
                                    </div>
                                @endif
                                <form class="form-horizontal needs-validation" novalidate action="{{ url()->current() }}"
                                    method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Email</label>
                                        <input required name="username" type="text" class="form-control" id="username"
                                            placeholder="Enter username" autofocus>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input required name="password" type="password" class="form-control"
                                                placeholder="Enter password" aria-label="Password"
                                                aria-describedby="password-addon">
                                            <button class="btn btn-light " type="button" id="password-addon"><i
                                                    class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                    </div>

                                    <div class="form-group mt-4 mb-4">
                                        <div class="captcha">
                                            <span>{!! captcha_img() !!}</span>
                                            <button type="button" class="btn btn-danger" class="reload" id="reload">
                                                &#x21bb;
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="captcha" class="form-label">Captcha</label>
                                        <input required name="captcha" type="text" class="form-control" id="captcha"
                                            placeholder="Enter captcha">
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember-check" value="1"
                                            name="ingat">
                                        <label class="form-check-label" for="remember-check">
                                            Ingat Saya
                                        </label>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary theme waves-effect waves-light" type="submit">Log
                                            In</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <div class="mt-5 text-center">

                        <div>
                            <p>©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> {{ env('APP_NAME') }}. Crafted with <i
                                    class="mdi mdi-heart text-danger"></i> by {{ env('APP_COMPANY') }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('#reload').click(function() {
            $.ajax({
                type: 'GET',
                url: 'reload-captcha',
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
@endpush
