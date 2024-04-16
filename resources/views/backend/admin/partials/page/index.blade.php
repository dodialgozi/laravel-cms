@extends('backend.layouts.panel')

@section('title', $title)

@php
    $tableData = [
        [
            'label' => 'Judul (ID)',
            'field' => 'page_title_id',
            'sort' => 'page_title_id',
            'search' => 'page_title_id',
        ],
        [
            'label' => 'Judul (EN)',
            'field' => 'page_title_en',
            'sort' => 'page_title_en',
            'search' => 'page_title_en',
        ],
    ];
    $action = function ($item) use ($mainURL, $primaryKey, $title, $permission, $descKey) {
        $encId = encodeId($item->{$primaryKey});

        return actionButtons([
            [
                userCan("{$permission}.rincian") && $item->user_id == auth()->user()->user_id,
                'Lihat',
                'fas fa-search text-info',
                url("https://{$item->instance->instance_domain}/halaman/{$item->page_slug_id}"),
                'class' => 'btn-detail',
            ],
            [
                userCan("{$permission}.ubah") && $item->user_id == auth()->user()->user_id,
                'Ubah',
                'fas fa-pencil-alt text-warning',
                urlWithQueryParams("{$mainURL}/{$encId}/edit"),
            ],
            [
                userCan("{$permission}.hapus") && $item->user_id == auth()->user()->user_id,
                'Hapus',
                'fas fa-trash-alt text-danger',
                'onClick' => ['hapus', url("{$mainURL}/{$encId}"), $title, $item->{$descKey} ?? ''],
            ],
        ]);
    };
@endphp

{{-- <x-modal-detail buttonClass="btn-detail" title="Rincian {{ $title }}" /> --}}


@section('panel_content')
    <x-table :columns=$tableData :models=$results mainURL="{{ $mainURL }}" :action=$action />
@endsection

@section('panel_right')
    @if (userCan("{$permission}.tambah"))
        <a class="btn btn-primary waves-effect btn-label waves-light btn-sm" href="{{ url("{$mainURL}/create") }}"><i
                class="label-icon fas fa-plus"></i> Tambah</a>
    @endif
@endsection
