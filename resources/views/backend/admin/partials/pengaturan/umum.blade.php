@extends('backend.layouts.panel')

@section('title', "{$title}")

@section('panel_content')
<form id="form" action="{{ url("{$mainURL}/save") }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-form-input label="Nama Aplikasi" name="{{ $input }}[sistem_app]" value="{{ $data['sistem_app'] ?? '' }}"
                required />
        </div>
        <div class="col-md-6 mb-3">
            <x-form-textarea label="Deskripsi Aplikasi" name="{{ $input }}[sistem_deskripsi]"
                value="{{ $data['sistem_deskripsi'] ?? '' }}" />
        </div>
        <div class="col-md-12"></div>
        <div class="col-md-6 mb-3">
            <div class="col-md-6 mb-3">
                <x-form-file label="Logo" name="{{ $inputFile }}[sistem_logo]"
                    value="{{ $data['sistem_logo'] ?? null }}" :image=true :download=false thumbnail="100" />
            </div>
            <x-form-file label="Logo Appbar" name="{{ $inputFile }}[sistem_logo_appbar]"
                value="{{ $data['sistem_logo_appbar'] ?? null }}" :image=true :download=false thumbnail="100" />
        </div>
        <div class="col-md-6 mb-3">
            <x-form-file label="Logo Icon <small>(Persegi, maksimal 100x100 pixel)</small>"
                name="{{ $inputFile }}[sistem_icon]" value="{{ $data['sistem_icon'] ?? null }}" :image=true
                :download=false thumbnail="100" />
        </div>

        <div class="col-md-12 mb-4"></div>

        <div class="col-md-6 mb-3">
            <x-form-input label="Nama Perusahaan" name="{{ $input }}[sistem_company]"
                value="{{ $data['sistem_company'] ?? '' }}" required />
        </div>
        <div class="col-md-6 mb-3">
            <x-form-input label="Telp. Perusahaan" name="{{ $input }}[sistem_company_telp]"
                value="{{ $data['sistem_company_telp'] ?? '' }}" />
        </div>
        <div class="col-md-6 mb-3">
            <x-form-textarea label="Alamat Perusahaan" name="{{ $input }}[sistem_company_address]"
                value="{{ $data['sistem_company_address'] ?? '' }}" />
        </div>
        <div class="col-md-6 mb-3">
            <x-form-input label="URL Alamat Google Maps Perusahaan" name="{{ $input }}[sistem_company_maps]"
                value="{{ $data['sistem_company_maps'] ?? '' }}" />
        </div>
        <div class="col-md-6 mb-3">
            <x-form-input type="email" label="Email Perusahaan" name="{{ $input }}[sistem_company_email]"
                value="{{ $data['sistem_company_email'] ?? '' }}" />
        </div>

        <div class="col-md-12 mb-4"></div>

        <div class="col-md-6 mb-3">
            <x-form-input label="URL Instagram" name="{{ $input }}[sosmed_instagram]"
                value="{{ $data['sosmed_instagram'] ?? '' }}" />
        </div>

        <div class="col-md-6 mb-3">
            <x-form-input label="URL Tiktok" name="{{ $input }}[sosmed_tiktok]"
                value="{{ $data['sosmed_tiktok'] ?? '' }}" />
        </div>

        <div class="col-md-6 mb-3">
            <x-form-input label="URL Facebook" name="{{ $input }}[sosmed_facebook]"
                value="{{ $data['sosmed_facebook'] ?? '' }}" />
        </div>

        <div class="col-md-6 mb-3">
            <x-form-input label="URL Youtube" name="{{ $input }}[sosmed_youtube]"
                value="{{ $data['sosmed_youtube'] ?? '' }}" />
        </div>
    </div>

    <x-form-submit :hideBack=true />
</form>
@endsection

<x-validation selector="#form" />
<x-swal-message />