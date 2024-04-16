@extends('backend.layouts.panel')

@section('title', $title)

@php
$tableData = [
	[
		'label' => 'Role',
		'field' => 'name',
		'sort' => 'nama',
	],
];

$action = function($item) use($mainURL, $primaryKey, $title, $permission, $descKey) {
	$encId = encodeId($item->{$primaryKey});
	
	return actionButtons([
		[
			(auth()->user()->hasRole('root') || !in_array($item->name, ['Super Admin'])) &&
			userCan("{$permission}.permission") && !auth()->user()->hasRole($item->name),
			'Permission',
			'fas fa-user-shield text-success',
			url("{$mainURL}/{$encId}/permission"),
		],
		[
			!in_array($item->name, ['Super Admin']) && userCan("{$permission}.ubah") && !auth()->user()->hasRole($item->name),
			'Ubah',
			'fas fa-pencil-alt text-warning',
			urlWithQueryParams("{$mainURL}/{$encId}/edit"),
		],
		[
			!in_array($item->name, ['Super Admin']) && userCan("{$permission}.hapus") && !auth()->user()->hasRole($item->name),
			'Hapus',
			'fas fa-trash-alt text-danger',
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