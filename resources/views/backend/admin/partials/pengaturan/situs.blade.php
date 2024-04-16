@extends('backend.layouts.panel')

@section('title', "{$title}")

@section('panel_content')
    <form id="form" action="{{ url("{$mainURL}/save") }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-form-textarea label="Promotion Text" name="{{ $input }}[sistem_deskripsi]"
                    value="{{ $data['sistem_deskripsi'] ?? '' }}" />
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label class="form-label">Bahasa</label>
                    <select class="form-control" placeholder="Pilih Bahasa" name="{{ $input }}[lang_id]">
                        <option value="id" {{ !empty($data['lang_id']) && $data['lang_id'] == 'id' ? 'selected' : '' }}>
                            Indonesia</option>
                        <option value="en" {{ !empty($data['lang_id']) && $data['lang_id'] == 'en' ? 'selected' : '' }}>
                            English</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12"></div>
            <div class="col-md-6 mb-3">
                <x-form-textarea label="Marque Text (ID)" name="{{ $input }}[marque_text_id]"
                    value="{{ $data['marque_text_id'] ?? '' }}" />
            </div>
            <div class="col-md-6 mb-3">
                <x-form-textarea label="Marque Text (EN)" name="{{ $input }}[marque_text_en]"
                    value="{{ $data['marque_text_en'] ?? '' }}" />
            </div>
            <div class="col-md-12"></div>
            <!-- color picker -->
            <div class="col-md-3 mb-3">
                <div class="form-group">
                    <label class="form-label d-block">Primary Color</label>
                    <div class="input-group" id="primary_color">
                        <input type="text" class="form-control input-lg" name="{{ $input }}[primary_color]"
                            value="{{ $data['primary_color'] ?? '' }}" />
                        @if (!empty($data['primary_color']))
                            <span class="input-group-append">
                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="form-group">
                    <label class="form-label d-block">Secondary Color</label>
                    <div class="input-group" id="secondary_color">
                        <input type="text" class="form-control input-lg" name="{{ $input }}[secondary_color]"
                            value="{{ $data['secondary_color'] ?? '' }}" />
                        @if (!empty($data['secondary_color']))
                            <span class="input-group-append">
                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="form-group">
                    <label class="form-label d-block">Link Color</label>
                    <div class="input-group" id="link_color">
                        <input type="text" class="form-control input-lg" name="{{ $input }}[link_color]"
                            value="{{ $data['link_color'] ?? '' }}" />
                        @if (!empty($data['link_color']))
                            <span class="input-group-append">
                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="form-group">
                    <label class="form-label d-block">Background Color</label>
                    <div class="input-group" id="background_color">
                        <input type="text" class="form-control input-lg" name="{{ $input }}[background_color]"
                            value="{{ $data['background_color'] ?? '' }}" />
                        @if (!empty($data['background_color']))
                            <span class="input-group-append">
                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-12"></div>
            <!-- custom button -->
            <div class="col-md-4 mb-3">
                <x-form-input label="Custom Button Text" name="{{ $input }}[custom_button_text]"
                    value="{{ $data['custom_button_text'] ?? '' }}" />
            </div>
            <div class="col-md-4 mb-3">
                <x-form-input label="Custom Button Link" name="{{ $input }}[custom_button_link]"
                    value="{{ $data['custom_button_link'] ?? '' }}" />
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label class="form-label d-block">Custom Button Color</label>
                    <div class="input-group" id="custom_button_color">
                        <input type="text" class="form-control input-lg" name="{{ $input }}[custom_button_color]"
                            value="{{ $data['custom_button_color'] ?? '' }}" />
                        @if (!empty($data['custom_button_color']))
                            <span class="input-group-append">
                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <x-form-submit :hideBack=true />
    </form>
@endsection
@push('style')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-colorpicker@3.4.0/dist/css/bootstrap-colorpicker.min.css">
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-colorpicker@3.4.0/dist/js/bootstrap-colorpicker.min.js"></script>

    <script>
        $(document).ready(function() {
            // color picker
            $('#primary_color, #secondary_color, #link_color, #background_color, #custom_button_color')
                .colorpicker({
                    format: 'hex',
                });


        });
    </script>
@endpush
<x-validation selector="#form" />
<x-swal-message />
