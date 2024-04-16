@extends('backend.layouts.panel')

@section('title', $title)

@php
    $tableData = [
        [
            'label' => 'Nama Instansi',
            'field' => 'instance_name',
            'sort' => 'name',
            'search' => 'name',
        ],
        [
            'label' => 'Status',
            'field' => 'instance_active',
            'value' => function ($row) {
                return $row->instance_active == 1
                    ? '<span class="badge bg-success py-1 px-2">Aktif</span>'
                    : '<span class="badge bg-danger py-1 px-2">Tidak Aktif</span>';
            },
            'sort' => 'status',
            'search' => false,
        ],
        [
            'label' => 'Logo',
            'field' => 'instance_thumbnail',
            'value' => function ($row) {
                return $row->instance_thumbnail
                    ? '<a href="' .
                            $row->instance_thumbnail .
                            '" class="doc-preview" title="Lihat Foto"><img src="' .
                            fileThumbnail($row->instance_thumbnail, size: 40) .
                            '" class="img-thumbnail thumb" style="max-width: 40px; max-height: 40px" referrerpolicy="no-referrer" /></a>'
                    : '<img src="' .
                            asset('backend/assets/images/no-image.jpg') .
                            '" class="img-thumbnail thumb" style="max-width: 40px; max-height: 40px" referrerpolicy="no-referrer" />';
            },
        ],
        [
            'label' => 'Domain',
            'field' => 'instance_domain',
            'sort' => 'domain',
            'search' => 'domain',
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
