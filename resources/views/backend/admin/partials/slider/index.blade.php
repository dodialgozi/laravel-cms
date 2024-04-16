@extends('backend.layouts.panel')

@section('title', $title)

@php
    $tableData = [
        [
            'label' => 'Judul',
            'field' => 'slider_title',
            'sort' => 'title',
            'search' => 'title',
        ],
        [
            'label' => 'Deskripsi',
            'field' => 'slider_description',
            'sort' => 'description',
            'search' => 'description',
        ],
        [
            'label' => 'Foto',
            'field' => 'slider_image',
            'value' => function ($row) {
                return $row->slider_image
                    ? '<a href="' .
                            $row->slider_image .
                            '" class="doc-preview" title="Lihat Foto"><img src="' .
                            fileThumbnail($row->slider_image, size: 40) .
                            '" class="img-thumbnail thumb" style="max-width: 40px; max-height: 40px" referrerpolicy="no-referrer" /></a>'
                    : '<img src="' .
                            asset('backend/assets/images/no-image.jpg') .
                            '" class="img-thumbnail thumb" style="max-width: 40px; max-height: 40px" referrerpolicy="no-referrer" />';
            },
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
