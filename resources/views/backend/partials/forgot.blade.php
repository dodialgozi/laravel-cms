@extends('backend.layouts.master')


@section('title', 'Lupa Password')

@section('body')
<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary bg-soft">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">Reset Password</h5>
                                    <p>Masukkan email anda lalu intsuksi akan dikirim melalui email.</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ url('backend/assets/images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="auth-logo">
                            <a href="index.html" class="auth-logo-light">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="{!! fileThumbnail(getSetting('sistem_icon', asset('backend/assets/images/logo-light.svg')), '') !!}" alt="" class="rounded-circle" height="34" referrerpolicy="no-referrer">
                                    </span>
                                </div>
                            </a>

                            <a href="index.html" class="auth-logo-dark">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="{!! fileThumbnail(getSetting('sistem_icon', asset('backend/assets/images/logo-light.svg')), '') !!}" alt="" class="rounded-circle" height="34" referrerpolicy="no-referrer">
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="p-2">
                            @if(session()->has("error"))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session()->get("error") }}
                            </div>
                            @endif
                            <form class="form-horizontal needs-validation" novalidate action="" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input required name="email" type="text" class="form-control" id="email" placeholder="Enter email">
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                    <div class="invalid-feedback">
                                        Please input a valid email.
                                    </div>
                                </div>

                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                </div>

                                <div class="mt-4 text-center">
                                    <a href="{{ url('admin/login') }}" class="text-muted"><i class="mdi mdi-lock me-1"></i> Login disini !</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="mt-5 text-center">

                    <div>
                        <p>© <script>document.write(new Date().getFullYear())</script> {{ env("APP_NAME") }}. Crafted with <i class="mdi mdi-heart text-danger"></i> by {{ env('APP_COMPANY') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection