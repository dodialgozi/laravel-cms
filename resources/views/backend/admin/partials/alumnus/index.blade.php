@extends('backend.layouts.panel')

@section('title', $title)

@php
    $tableData = [
        [
            'label' => 'Name',
            'field' => 'alumnus_name',
            'sort' => 'name',
            'search' => 'name',
        ],
        [
            'label' => 'NIM',
            'field' => 'alumnus_nim',
            'sort' => 'nim',
            'search' => 'nim',
        ],
        [
            'label' => 'Email',
            'field' => 'alumnus_email',
            'sort' => 'email',
            'search' => 'email',
        ],
    ];
    $action = function ($item) use ($mainURL, $primaryKey, $title, $permission, $descKey) {
        $encId = encodeId($item->{$primaryKey});

        return actionButtons([
            [
                userCan("{$permission}.ubah"),
                'Ubah',
                'fas fa-pencil-alt text-warning',
                urlWithQueryParams("{$mainURL}/{$encId}/edit"),
            ],
            [
                userCan("{$permission}.hapus"),
                'Hapus',
                'fas fa-trash-alt text-danger',
                'onClick' => ['hapus', url("{$mainURL}/{$encId}"), $title, $item->{$descKey} ?? ''],
            ],
        ]);
    };
@endphp

@section('panel_content')
    <x-table :columns=$tableData :models=$results mainURL="{{ $mainURL }}" :action=$action />
@endsection

@section('panel_right')
    @if (userCan("{$permission}.tambah"))
        <a class="btn btn-primary waves-effect btn-label waves-light btn-sm" href="{{ url("{$mainURL}/create") }}"><i
                class="label-icon fas fa-plus"></i> Tambah</a>
    @endif
@endsection
