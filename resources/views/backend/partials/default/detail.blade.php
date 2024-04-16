@extends('backend.layouts.panel')

@section('title', "Rincian {$title}")

@section('panel_content')

@include($detailView, [
    'typeSize' => [
        'doc' => 'col-sm-6 col-md-4 col-lg-3',
        'doc-thumb' => 'col-sm-6 col-md-4 col-lg-3',
        'image' => 'col-sm-6 col-md-4 col-lg-3',
    ]
])

<x-form-submit back="Kembali" :hideSubmit=true />

@endsection