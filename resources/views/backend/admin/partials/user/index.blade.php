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
		'label' => 'Nick',
		'field' => 'user_nick',
		'sort' => 'nick',
		'search' => 'nick',
	],
	[
		'label' => 'Email',
		'field' => 'user_email',
		'sort' => 'email',
		'search' => 'email',
	],
	[
		'label' => 'Role',
		'field' => 'role',
		'search' => function() use ($more) {
			return '<x-input-select2 placeholder="Filter Role" name="role" :url="url(\'role/select\')" key="id" value="nama" :hashId=true :allowClear=true class="filter form-control-sm" id="role">
				' . (!empty($model = $more['role']) ? '<option value="' . encodeId($model->id) . '">' . $model->name . '</option>' : '') . '
			</x-input-select2>';
		},
	],
	[
		'label' => 'Foto',
		'field' => 'peg_foto',
		'value' => function($row) {
			if(empty($file = $row->user_photo ?? null)) return '';
			return '<a href="' . $file . '" class="doc-preview" title="Lihat Foto">
				<img src="' . fileThumbnail($file, size: 100) . '" class="img-thumbnail thumb thumb-100" referrerpolicy="no-referrer" />
			</a>';
		},
	],
];

$action = function($item) use($mainURL, $primaryKey, $title, $permission, $descKey) {
	$encId = encodeId($item->{$primaryKey});
	
	return actionButtons([
		[
			userCan("{$permission}.rincian") && 
				$item->user_id != auth()->user()->user_id,
			'Lihat',
			'fas fa-search text-info',
			url("{$mainURL}/{$encId}"),
			'class' => 'btn-detail',
		],
		[
			userCan("{$permission}.ubah") && 
				$item->user_id != auth()->user()->user_id,
			'Ubah',
			'fas fa-pencil-alt text-warning',
			urlWithQueryParams("{$mainURL}/{$encId}/edit"),
		],
		[
			userCan("{$permission}.hapus") && 
				$item->user_id != auth()->user()->user_id,
			'Hapus',
			'fas fa-trash-alt text-danger',
			'onClick' => [
				'hapus', url("{$mainURL}/{$encId}"), $title, $item->{$descKey} ?? '',
			],
		],
	]);
};
@endphp

<x-modal-detail buttonClass="btn-detail" title="Rincian {{ $title }}" />


@section('panel_content')
<x-table :columns=$tableData :models=$results mainURL="{{ $mainURL }}" :action=$action />
@endsection

@section('panel_right')
@if(userCan("{$permission}.tambah"))
<a class="btn btn-primary waves-effect btn-label waves-light btn-sm" href="{{ url("{$mainURL}/create") }}" ><i class="label-icon fas fa-plus"></i> Tambah</a>
@endif
@endsection