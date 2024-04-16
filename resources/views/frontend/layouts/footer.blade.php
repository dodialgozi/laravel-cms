<footer id="footer" class="border-top-0 mt-0">
    <div class="container py-4">
        <div class="row py-5">
            <div class="col-md-6 mb-4 mb-lg-0">
                <a href="{{ url('/') }}" class="logo pe-0 pe-lg-3 pb-4">
                    <img alt="Logo {{ !empty($setting['sistem_app']) ? $setting['sistem_app'] : env('APP_NAME') }}"
                        width="95" class="img-fluid"
                        src="{{ !empty($setting['sistem_logo_appbar']) ? $setting['sistem_logo_appbar'] : '#' }}">
                </a>
                <p class="pt-3 mb-2 w-75">
                    {{ !empty($setting['sistem_deskripsi']) ? $setting['sistem_deskripsi'] : '' }}
                </p>
                <p class="mb-0">
                </p>
            </div>
            <div class="col-md-3 mb-4 mb-lg-0">
                <h5 class="text-4-5 text-transform-none custom-font-primary mb-3">Contact Us</h5>

                @isset($setting['sistem_company_email'])
                <div class="row pb-3">
                    <div class="col">
                        <div class="feature-box feature-box-style-2 align-items-center">
                            <div class="feature-box-icon">
                                <img width="34" height="28" src="{{ asset('frontend/assets/img/icons') }}/envelope.svg"
                                    alt="" data-icon
                                    data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-fill-color-primary'}"
                                    style="width: 34px; height: 28px;" />
                            </div>
                            <div class="feature-box-info ps-1">
                                <label class="custom-footer-label-1">SEND US AN EMAIL</label>
                                <strong class="custom-footer-strong-1"><a
                                        href="mailto:{{ $setting['sistem_company_email'] }}" class="text-color-light">{{
                                        Str::upper($setting['sistem_company_email']) }}</a></strong>
                            </div>
                        </div>
                    </div>
                </div>
                @endisset

                @isset($setting['sistem_company_telp'])
                <div class="row pb-3">
                    <div class="col">
                        <div class="feature-box feature-box-style-2 align-items-center">
                            <div class="feature-box-icon">
                                <img width="30" height="30" src="{{ asset('frontend/assets/img/icons') }}/phone.svg"
                                    alt="" data-icon
                                    data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-stroke-color-primary p-relative left-3'}"
                                    style="width: 30px; height: 30px;" />
                            </div>
                            <div class="feature-box-info ps-1">
                                <label class="custom-footer-label-1">CALL US NOW</label>
                                <strong class="custom-footer-strong-1"><a
                                        href="tel:{{ $setting['sistem_company_telp'] }}" class="text-color-light">{{
                                        $setting['sistem_company_telp'] }}</a></strong>
                            </div>
                        </div>
                    </div>
                </div>
                @endisset

                @isset($setting['sistem_company_address'])
                <div class="row">
                    <div class="col">
                        <div class="feature-box feature-box-style-2 align-items-center">
                            <div class="feature-box-icon">
                                <img width="30" height="30" src="{{ asset('frontend/assets/img/icons') }}/location.svg"
                                    alt="" data-icon
                                    data-plugin-options="{'onlySVG': true, 'extraClass': 'svg-stroke-color-primary p-relative left-3'}"
                                    style="width: 30px; height: 30px;" />
                            </div>
                            <div class="feature-box-info ps-1">
                                <label class="custom-footer-label-1">LOCATION</label>
                                <strong class="custom-footer-strong-1"><a
                                        href="{{ url($setting['sistem_company_maps']) }}" target="_blank"
                                        class="text-color-light">{{ $setting['sistem_company_address'] }}</a></strong>
                            </div>
                        </div>
                    </div>
                </div>
                @endisset
            </div>
            <div class="col-md-3">
                <h5 class="text-4-5 text-transform-none custom-font-primary mb-3">Follow Us</h5>

                <ul class="custom-social-icons-style-1 social-icons social-icons-clean d-flex gap-3">
                    @isset($setting['sosmed_instagram'])
                    <li class="social-icons-instagram">
                        <a href="{{ $setting['sosmed_instagram'] }}" class="no-footer-css" target="_blank"
                            title="Instagram"><i class="text-primary fab fa-instagram"></i></a>
                    </li>
                    @endisset

                    @isset($setting['sosmed_tiktok'])
                    <li class="social-icons-twitter">
                        <a href="{{ $setting['sosmed_tiktok'] }}" class="no-footer-css" target="_blank"
                            title="Tiktok"><i class="text-primary fab fa-tiktok"></i></a>
                    </li>
                    @endisset


                    @isset($setting['sosmed_facebook'])
                    <li class="social-icons-facebook">
                        <a href="{{ $setting['sosmed_facebook'] }}" class="no-footer-css" target="_blank"
                            title="Facebook"><i class="text-primary fab fa-facebook-f"></i></a>
                    </li>
                    @endisset

                    @isset($setting['sosmed_youtube'])
                    <li class="social-icons-facebook">
                        <a href="{{ $setting['sosmed_youtube'] }}" class="no-footer-css" target="_blank"
                            title="Youtube"><i class="text-primary fab fa-youtube"></i></a>
                    </li>
                    @endisset
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright footer-copyright-style-2">
        <div class="container py-2">
            <div class="row py-4">
                <div class="col d-flex align-items-center justify-content-center">
                    <p>© Copyright <script>
                            document.write(new Date().getFullYear())
                        </script>. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>