@extends('backend.layouts.panel')

@section('title', "Tambah {$title}")

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ url($mainURL) }}">{{ $title }}</a></li>
@endsection

@section('panel_content')
    <form id="formcreate" action="{{ url($mainURL) }}" method="POST"
        @if ($upload) enctype="multipart/form-data" @endif>
        @csrf
        @include($form, ['formType' => 'create'])
        <x-form-submit />
    </form>
@endsection

<x-validation selector="#formcreate" />
