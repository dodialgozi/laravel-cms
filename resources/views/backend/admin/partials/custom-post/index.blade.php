@extends('backend.layouts.panel')

@section('title', $title)

@php
    $tableData = [
        [
            'label' => 'Judul (ID)',
            'field' => 'post_title_id',
            'sort' => 'title_id',
            'search' => 'title_id',
        ],
        [
            'label' => 'Judul (EN)',
            'field' => 'post_title_en',
            'sort' => 'title_en',
            'search' => 'title_en',
        ],
        [
            'label' => 'Author',
            'field' => 'user_id',
            'value' => function ($row) {
                return $row->user->user_name ?? '';
            },
            'sort' => 'author',
            'search' => false,
        ],
        [
            'label' => 'Tanggal',
            'field' => 'post_date',
            'value' => function ($row) {
                return $row->post_date ? printDateDayHourMinute($row->post_date) : '-';
            },
            'sort' => 'date',
            'search' => function () {
                $value = '';
                if (!empty(($model = request()->query('date') ?? null))) {
                    $value = $model;
                }
                return '<x-input-datepicker name="date" class="form-control-sm filter" placeholder="Filter Tanggal" value="' .
                    $value .
                    '" />';
            },
        ],
        [
            'label' => 'Status',
            'field' => 'post_status',
            'value' => function ($row) {
                if ($row->post_status == 'submit') {
                    $value = '<span class="badge bg-info py-1 px-2">Submit</span>';
                } elseif ($row->post_status == 'publish') {
                    $value = '<span class="badge bg-success py-1 px-2">Publish</span>';
                } elseif ($row->post_status == 'schedule') {
                    $value =
                        '<span class="badge bg-warning py-1 px-2"><i class="fas fa-clock mr-2"></i> Terjadwal</span>';
                } else {
                    $value = '<span class="badge bg-secondary">Draft</span>';
                }

                return $value;
            },
            'sort' => 'status',
            'search' => function () use ($more) {
                $input =
                    '<x-input-select2-option name="status" class="form-control-sm filter" :allowClear=true :disableSearch=true placeholder="Filter Status">';
                foreach ($more['status'] as $key => $value) {
                    $selected = '';
                    if (!empty(($model = request()->query('status') ?? null))) {
                        $selected = $model == $key ? 'selected' : '';
                    }
                    $input .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                }
                $input .= '</x-input-select2-option>';

                return $input;
            },
        ],
    ];
    $action = function ($item) use ($mainURL, $primaryKey, $title, $permission, $descKey, $type) {
        $encId = encodeId($item->{$primaryKey});
        return actionButtons([
            [
                (userCan("{$permission}.{$type}.rincian") && $item->user_id == auth()->user()->user_id) ||
                (userCan("{$permission}.rincian") && userCan("{$permission}.editor")),
                'Lihat',
                'fas fa-search text-info',
                url("{$mainURL}/{$encId}"),
                'class' => 'btn-detail',
            ],
            [
                (userCan("{$permission}.{$type}.ubah") && $item->user_id == auth()->user()->user_id) ||
                (userCan("{$permission}.ubah") && userCan("{$permission}.editor")),
                'Ubah',
                'fas fa-pencil-alt text-warning',
                urlWithQueryParams("{$mainURL}/{$encId}/edit"),
            ],
            [
                (userCan("{$permission}.{$type}.hapus") && $item->user_id == auth()->user()->user_id) ||
                (userCan("{$permission}.hapus") && userCan("{$permission}.editor")),
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
    @if (userCan("{$permission}.{$type}.tambah"))
        <a class="btn btn-primary waves-effect btn-label waves-light btn-sm" href="{{ url("{$mainURL}/create") }}"><i
                class="label-icon fas fa-plus"></i> Tambah</a>
    @endif
@endsection
