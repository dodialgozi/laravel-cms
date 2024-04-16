<div class="header-container container z-index-2">
    <div class="header-row">
        <div class="header-column">
            <div class="header-row">
                <div class="header-logo">
                    <a href="{{ url('/') }}">
                        <img alt="Logo {{ !empty($setting['sistem_app']) ? $setting['sistem_app'] : env('APP_NAME') }}"
                            width="95" class="img-fluid"
                            src="{{ !empty($setting['sistem_logo']) ? $setting['sistem_logo'] : '#' }}">
                    </a>
                </div>
            </div>
        </div>
        <div class="header-column justify-content-end">
            <div class="header-row h-100">
                <ul class="header-extra-info d-flex h-100 align-items-center">

                    @isset($setting['sistem_company_email'])
                    <li class="align-items-center d-none d-lg-block h-100 py-4">
                        <div class="header-extra-info-text h-100 py-2">
                            <div class="feature-box feature-box-style-2 align-items-center">
                                <div class="feature-box-icon">
                                    <img width="34" height="28"
                                        src="{{ asset('frontend/assets/img/icons') }}/envelope.svg" alt="" data-icon
                                        data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-fill-color-primary'}"
                                        style="width: 34px; height: 28px;" />
                                </div>
                                <div class="feature-box-info ps-1">
                                    <label>SEND US AN EMAIL</label>
                                    <strong>
                                        <a href="mailto:{{ $setting['sistem_company_email'] }}">{{
                                            Str::upper($setting['sistem_company_email']) }}</a>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endisset

                    @isset($setting['sistem_company_telp'])
                    <li class="align-items-center d-none d-lg-block h-100 py-4">
                        <div class="header-extra-info-text h-100 py-2">
                            <div class="feature-box feature-box-style-2 align-items-center">
                                <div class="feature-box-icon">
                                    <img width="30" height="30" src="{{ asset('frontend/assets/img/icons') }}/phone.svg"
                                        alt="" data-icon
                                        data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-stroke-color-primary p-relative left-3'}"
                                        style="width: 30px; height: 30px;" />
                                </div>
                                <div class="feature-box-info ps-1">
                                    <label>CALL US NOW</label>
                                    <strong><a href="tel:{{ $setting['sistem_company_telp'] }}">{{
                                            $setting['sistem_company_telp'] }}</a></strong>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endisset

                    <li class="align-items-center d-none d-sm-block h-100 py-4">
                        <div class="header-extra-info-text h-100 py-2">
                            <div class="feature-box feature-box-style-2 align-items-center">
                                <div class="feature-box-icon">
                                    <img width="33" height="31"
                                        src="{{ asset('frontend/assets/img/icons') }}/comment.svg" alt="" data-icon
                                        data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-fill-color-primary'}"
                                        style="width: 33px; height: 31px;" />
                                </div>
                                <div class="feature-box-info ps-1">
                                    <label class="p-relative top-4">GET STARTED</label>
                                    <strong class="text-uppercase"><a href="#">Request Consultation
                                            <img width="27" height="27"
                                                src="{{ asset('frontend/assets/img/icons') }}/arrow-right.svg" alt=""
                                                data-icon
                                                data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-fill-color-primary ms-2 d-inline'}" />
                                        </a></strong>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse"
                    data-bs-target=".header-nav-main nav">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</div>