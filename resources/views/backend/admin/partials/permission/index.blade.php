@extends('backend.layouts.panel')

@section('title', $title)

@php
$tableData = [
	[
		'label' => 'Permission',
		'field' => 'name',
		'search' => 'nama',
	],
	[
		'label' => 'Group',
		'field' => 'group',
		'search' => function() use ($more) {
			return '<x-input-select2 placeholder="Filter Group" name="group" :url="url(\'permission-group/select\')" key="id" value="nama" :hashId=true :allowClear=true class="filter form-control-sm" id="group">
				' . (!empty($model = $more['group']) ? '<option value="' . encodeId($model->id) . '">' . $model->name . '</option>' : '') . '
			</x-input-select2>';
		},
	],
	[
		'label' => 'Description',
		'field' => 'description',
	],
];

$action = function($item) use($mainURL, $primaryKey, $title, $permission, $descKey) {
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
			'onClick' => [
				'hapus', url("{$mainURL}/{$encId}"), $title, $item->{$descKey} ?? '',
			],
		],
	]);
};
@endphp

@section('panel_content')
<x-table :columns=$tableData :models=$results mainURL="{{ $mainURL }}" :action=$action :primaryKey=$primaryKey :useCheckbox='userCan("{$permission}.hapus")'>
	<div class="floating-action" style="display: none;">
		<button type="button" class="btn btn-danger btn-delete-all">
			<i class="fas fa-trash-alt"></i> Hapus Ditandai
		</button>
	</div>
</x-table>
@endsection

@section('panel_right')
@if(userCan("{$permission}.tambah"))
<a class="btn btn-primary waves-effect btn-label waves-light btn-sm" href="{{ url("{$mainURL}/create") }}" ><i class="label-icon fas fa-plus"></i> Tambah</a>
@endif
@endsection

@if(userCan("{$permission}.hapus"))
@push('script')
<script>
$(function() {
	$('.btn-delete-all').click(function() {
		let deleteIds = [];
		let deleteNames = [];
		$('.check-one').each(function() {
			if($(this).is(':checked')) {
				deleteIds.push($(this).closest('tr').data('id'));
				deleteNames.push($(this).closest('tr').find('td:nth-child(3)').text());
			}
		});
		if(deleteIds.length > 0) {
			hapusSemua('{{ url("{$mainURL}/hapus-semua") }}', deleteIds, '{{ $title }}', deleteNames);
		}
	});
	$('.check-one,.check-all').click(function() {
		floatingAction();
	});
});

function floatingAction() {
	if(isOneCheckboxChecked()) {
		$('.floating-action').fadeIn();
	} else {
		$('.floating-action').fadeOut();
	}
}
</script>
@endpush
@endif