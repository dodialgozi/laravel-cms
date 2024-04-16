@extends('backend.layouts.panel')

@section('title', $title)

@php
    $tableData = [
        [
            'label' => 'Nama',
            'field' => 'post_type_name',
            'sort' => 'post_type_name',
            'search' => 'post_type_name',
        ],
        [
            'label' => 'Kode',
            'field' => 'post_type_code',
            'sort' => 'post_type_code',
            'search' => 'post_type_code',
        ],
    ];
    $action = function ($item) use ($mainURL, $primaryKey, $title, $permission, $descKey) {
        $encId = encodeId($item->{$primaryKey});

        return actionButtons([
            [
                userCan("{$permission}.rincian") && $item->user_id != auth()->user()->user_id,
                'Lihat',
                'fas fa-search text-info',
                url("{$mainURL}/{$encId}"),
                'class' => 'btn-detail',
            ],
            [
                userCan("{$permission}.ubah") && $item->user_id != auth()->user()->user_id,
                'Ubah',
                'fas fa-pencil-alt text-warning',
                urlWithQueryParams("{$mainURL}/{$encId}/edit"),
            ],
            [
                userCan("{$permission}.hapus") && $item->user_id != auth()->user()->user_id,
                'Hapus',
                'fas fa-trash-alt text-danger',
                'onClick' => ['hapus', url("{$mainURL}/{$encId}"), $title, $item->{$descKey} ?? ''],
            ],
        ]);
    };
@endphp

<x-modal-detail buttonClass="btn-detail" title="Rincian {{ $title }}" />


@section('panel_content')
    <x-table :columns=$tableData :models=$results mainURL="{{ $mainURL }}" :action=$action />
@endsection

@section('panel_right')
    @if (userCan("{$permission}.tambah"))
        <a class="btn btn-primary waves-effect btn-label waves-light btn-sm" href="{{ url("{$mainURL}/create") }}"><i
                class="label-icon fas fa-plus"></i> Tambah</a>
    @endif
@endsection
