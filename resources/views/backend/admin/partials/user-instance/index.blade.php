@extends('backend.layouts.panel')

@section('title', $title)

@php
    $tableData = [
        [
            'label' => 'Nama',
            'field' => 'user_name',
            'sort' => 'nama',
            'search' => 'nama',
        ],
        [
            'label' => 'Instansi',
            'field' => 'instance_id',
            'value' => function ($row) {
                $instances = $row->instances;

                $span = $instances->map(function ($item) {
                    return "<span class='badge bg-secondary px-2 py-1'>{$item->instance_name}</span>";
                });

                return implode(' ', $span->toArray());
            },
            'sort' => false,
            'search' => false,
        ],
    ];
    $action = function ($item) use ($mainURL, $primaryKey, $title, $permission, $descKey) {
        $encId = encodeId($item->{$primaryKey});

        return actionButtons([
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
