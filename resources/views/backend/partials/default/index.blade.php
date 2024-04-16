@extends('backend.layouts.panel')

@section('title', $title)

@php
$tableData = [
    /**
     * label => String; required
     * field => String; required
     * value => Function($row); optional; must return some sort of String
     * sort => Boolean; optional
     * search => Boolean or String or Function; optional; Function must return some sort of String
     */
    // Example:
	// [
	// 	'label' => 'Contoh 1',
	// 	'field' => 'contoh_1',
	// 	'format' => 'raw',
	// 	'value' => function($row) {
	// 		return printDate($row->libur_tanggal);
	// 	},
	// 	'sort' => true,
	// 	'search' => function() {
	// 		return '<x-input-datepicker class="form-control-sm filter" view-mode="2" format="yyyy" name="tahun" :value="request()->tahun" />';
	// 	},
	// ],
	// [
	// 	'label' => 'Contoh 2',
	// 	'field' => 'contoh_2',
	// 	'sort' => true,
	// 	'search' => true,
	// 	'search' => function() {
	// 		return '<x-input-select2 placeholder="Filter" class="form-control-sm filter" id="contoh2" :url="url(\'/tes\')" key="contoh_id" value="contoh_nama" placeholder="Filter Contoh" :allowClear="true">
	// 			<option value="1">Uji Coba</option>
	// 		</x-input-select2>';
	// 	},
	// ]
];

$action = function($item) use($mainURL, $primaryKey, $title, $permission, $descKey) {
	$encId = encodeId($item->{$primaryKey});
	
	return actionButtons([
		[
			userCan("{$permission}.rincian"),
			'Lihat',
			'fa-search text-info',
			url("{$mainURL}/{$encId}"),
		],
		[
			userCan("{$permission}.ubah"),
			'Ubah',
			'fa-pencil-alt text-warning',
			urlWithQueryParams("{$mainURL}/{$encId}/edit"),
		],
		[
			userCan("{$permission}.hapus"),
			'Hapus',
			'fa-trash-alt text-danger',
			'onClick' => [
				'hapus', url("{$mainURL}/{$encId}"), $title, $item->{$descKey} ?? '',
			],
		],
	]);
};
@endphp

@section('panel_content')
<x-table :columns=$tableData :models=$results mainURL="{{ $mainURL }}" :action=$action />
@endsection

@section('panel_right')
@if(userCan("{$permission}.tambah"))
<a class="btn btn-primary waves-effect btn-label waves-light btn-sm" href="{{ url("{$mainURL}/create") }}" ><i class="label-icon fas fa-plus"></i> Tambah</a>
@endif
@endsection