<div class="navbar-header">
    <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="{{ url('/') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{!! fileThumbnail(getSetting('sistem_icon', asset('backend/assets/images/logo-light.svg')), '') !!}" alt="" height="22" referrerpolicy="no-referrer">
                </span>
                <span class="logo-lg">
                    <img src="{!! fileThumbnail(getSetting('sistem_logo_appbar', asset('backend/assets/images/logo-light.png')), '') !!}" alt="" height="17" referrerpolicy="no-referrer">
                </span>
            </a>

            <a href="{{ url('/') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{!! fileThumbnail(getSetting('sistem_icon', asset('backend/assets/images/logo-light.svg')), '') !!}" alt="" height="22" referrerpolicy="no-referrer">
                </span>
                <span class="logo-lg">
                    <img src="{!! fileThumbnail(getSetting('sistem_logo_appbar', asset('backend/assets/images/logo-light.png')), '') !!}" alt="" height="30" referrerpolicy="no-referrer">
                </span>
            </a>
        </div>

        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
            <i class="fa fa-fw fa-bars"></i>
        </button>
    </div>

    <div class="d-flex">

        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item noti-icon waves-effect"
                id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-bell notification-bell"></i>
                <span class="badge bg-danger rounded-pill notification-number"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                aria-labelledby="page-header-notifications-dropdown">
                <div class="p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-0" key="t-notifications">Notifikasi dan Pengingat</h6>
                        </div>
                    </div>
                </div>
                <div data-simplebar class="notification-body"></div>
                <div class="p-2 border-top d-grid">
                    <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ url('notifikasi') }}">
                        <i class="mdi mdi-arrow-right-circle me-1"></i>
                        <span key="t-view-more">Lihat Semua..</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user"
                    src="{{ fileThumbnail(auth()->user()->user_photo, null) ?? Avatar::create(auth()->user()->user_name)->toBase64() }}"
                    alt="Header Avatar" referrerpolicy="no-referrer">
                <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ auth()->user()->user_name }}</span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ url('profil') }}"><i
                        class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profil
                        Saya</span></a>
                <div class="dropdown-divider"></div>

                @if (getLevel() != 'administrator')
                    @foreach (auth()->user()->instances as $instance)
                        <a class="dropdown-item" href="{{ url('instance/set/' . encodeId($instance->instance_id)) }}">
                            <i class="bx bx-building-house font-size-16 align-middle me-1"></i>
                            <span>{{ $instance->instance_name }}</span>
                        </a>
                    @endforeach
                @endif

                <a class="dropdown-item text-danger" href="{{ url('logout') }}"><i
                        class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span
                        key="t-logout">Logout</span></a>
            </div>
        </div>

    </div>
</div>
