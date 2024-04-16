@extends('backend.layouts.panel')

@section('title', $title)

@php
    $tableData = [
        [
            'label' => 'Nama (ID)',
            'field' => 'tag_name_id',
            'sort' => 'nama',
            'search' => 'nama',
        ],
        [
            'label' => 'Name (EN)',
            'field' => 'tag_name_en',
            'sort' => 'name',
            'search' => 'name',
        ],
        [
            'label' => 'Status',
            'field' => 'tag_popular',
            'value' => function ($row) {
                $popular = $row->tag_popular ? 'Popular' : 'Tidak Popular';
                $html = "<span class='px-2 py-1 badge bg-";
                $html .= $row->tag_popular ? 'danger' : 'secondary';
                $html .= "'>";
                $html .= $row->tag_popular ? '<i class="fas fa-fire me-1"></i>' : '<i></i>';
                $html .= $popular;
                $html .= '</span>';

                return $html;
            },
            'sort' => 'popular',
            'search' => function () use ($more) {
                $input =
                    '<x-input-select2-option name="popular" class="form-control-sm filter" :allowClear=true :disableSearch=true placeholder="Filter Status">';
                foreach ($more['status'] as $key => $value) {
                    $selected = '';
                    if (!empty(($model = request()->query('popular') ?? null))) {
                        $selected = $model == $key ? 'selected' : '';
                    }
                    $input .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                }
                $input .= '</x-input-select2-option>';

                return $input;
            },
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
