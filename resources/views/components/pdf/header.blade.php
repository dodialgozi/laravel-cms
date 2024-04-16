<htmlpageheader name="page-header">
	<div style="height: 70px;">
		<table class="w-100">
			<tr>
				<td class="logo" rowspan="2">
					<img src="{{ $logo }}" class="logo">
				</td>
				<td class="text-center h1">
					{{ $company }}
				</td>
				<td class="logo" rowspan="2"></td>
			</tr>
			<tr>
				<td class="text-center h4">
					{{ $companyAddress }}
					@if(!empty($companyTelp))
					<br/>Telp. {{ $companyTelp }}
					@endif
				</td>
			</tr>
		</table>
	</div>

	<div class="header-line"></div>
</htmlpageheader>

@push('style')
<style>
@page {
	header: page-header;
	margin-top: 115px;
}
.header-line {
	border-top: 2px solid black;
	border-bottom: 1px solid black;
	height: 4px;
}
</style>
@endpush