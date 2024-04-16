@extends('backend.layouts.panel')

@section('title', "Ubah {$title}")

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ url($mainURL) }}">{{ $title }}</a></li>
@endsection

@section('panel_content')
<form id="formupdate" action="{{ url("{$mainURL}/" . encodeId($result->{$primaryKey})) }}" method="POST" @if($upload) enctype="multipart/form-data" @endif>
    @csrf
    @method('PUT')
    @if(!empty(request()->redirect_params))
    <input type="hidden" name="redirect_params" value="{{ request()->redirect_params }}">
    @endif
    @include($form, ['formType' => 'update'])
    <x-form-submit type="warning" />
</form>
@endsection

<x-validation selector="#formupdate" />