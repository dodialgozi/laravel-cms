@extends('backend.layouts.panel')

@section('title', 'Profil Saya')

@section('panel_content')

@if(session()->has('message') || session()->has('errorMessage'))
    @if(session('success'))
    <x-alert type="success" message="{{ session('message') ?? session('errorMessage') }}" />
    @else
    <x-alert type="danger" message="{{ session('message') ?? session('errorMessage') }}" />
    @endif
@endif

@php
    $levels = $more['levels'] ?? [];
@endphp
<form class="card" id="formdata" action="{{ url()->current() }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <x-form-data label="Role" value="{{ $more['role']->name ?? '-' }}" />
    </div>
    <div class="col-md-6 mb-3">
        <x-form-data label="Role" value="{{ $user->user_level == 'administrator' ? 'Super Admin' : 'User' }}" />
    </div>

    <div class="col-md-12"></div>
    <div class="col-md-6 mb-3">
        <x-form-input label="Email" name="{{ $input }}[user_email]" value="{{ $user->user_email ?? '' }}" id="email" required/>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-data label="Password" class="bg-white">
            <button type="button" class="btn btn-sm btn-warning" id="ubahpass"><i class="bx bx-shield-quarter"></i> Ganti Password</button>
        </x-form-data>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-input label="Nama" name="{{ $input }}[user_name]" value="{{ $user->user_name ?? '' }}" required/>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-input label="Panggilan" name="{{ $input }}[user_nick]" value="{{ $user->user_nick ?? '' }}"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-textarea label="Bio" name="{{ $input }}[user_bio]">{!! $user->user_bio ?? '' !!}</x-form-textarea>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-file label="Foto" name="{{ $inputFile }}[user_photo]" :value="$user->user_photo ?? null" :image=true :download=false />
    </div>
    <x-form-submit :hideBack="true" type="success" />
    
</div>
</form>

@endsection

<x-validation selector="#modal-ubahpass form" />

<x-modal id="modal-ubahpass" title="Ganti Password" :form="[
    'action' => url('profil/ganti-password'),
    'method' => 'POST',
]">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-form-input label="Password Lama" name="password[pass_lama]" type="password" required/>
        </div>
        <div class="col-md-12 mb-3">
            <x-form-input label="Password Baru" name="password[pass_baru]" type="password" id="passwordbaru" required/>
        </div>
        <div class="col-md-12 mb-3">
            <x-form-input label="Ulangi Password Baru" name="password[pass_ulangi]" type="password" data-parsley-equalto="#passwordbaru" data-parsley-equalto-message="Password baru dan ulangi password harus sama" required/>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-warning waves-effect">Ganti Password</button>
    </x-slot>
</x-modal>


@push('script')
<script>
$(function() {
    $('#ubahpass').click(function() {
        $('#modal-ubahpass').modal('show');
    });
});
</script>
@endpush