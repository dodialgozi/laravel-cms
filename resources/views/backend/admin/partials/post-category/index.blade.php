@extends('backend.layouts.panel')

@section('title', $title)

@section('panel_content')
    @if (session()->has('message') || session()->has('errorMessage'))
        @if (session('success'))
            <x-alert type="success" :message="session('message') ?? session('errorMessage')" />
        @else
            <x-alert type="danger" :message="session('message') ?? session('errorMessage')" />
        @endif
    @endif
    <div class="row">
        <div class="col-12">
            <ul class="list-unstyled">
                @include('backend.admin.partials.post-category._nested-category', [
                    'results' => $results,
                    'indent' => 0,
                ])
            </ul>
        </div>
    </div>
@endsection

@section('panel_right')
    @if (userCan("{$permission}.tambah"))
        <a class="btn btn-primary waves-effect btn-label waves-light btn-sm" href="{{ url("{$mainURL}/create") }}"><i
                class="label-icon fas fa-plus"></i> Tambah</a>
    @endif
@endsection

@push('style')
    <style>
        .child-content {
            clear: both;
            line-height: 1.5;
            position: relative;
            margin: 10px 0 0;
        }

        .branch-path {
            display: block;
            position: absolute;
            width: 30px;
            height: 55px;
            bottom: 50%;
            left: -12px;
            border: 2px solid #565656;
            border-top: 0;
            border-right: 0;
            padding: 4px 0 0;
            padding-top: 3px;
            border-bottom-left-radius: 6px;
            z-index: 0;
        }
    </style>
@endpush
