@php
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\ComponentAttributeBag;

$sortOrder = request()->has("sort_type") && strtolower(request()->input('sort_type')) == 'desc' ? 'DESC' : 'ASC';
$upOrDown = str_replace(['ASC','DESC'], ['up','down'], $sortOrder);
$sortType = $sortOrder == 'DESC' ? 'asc' : 'desc';
@endphp

@if($globalSearch)
<form method="GET" action="{{ url()->current() }}">
	<div class="row g-3">
		<div class="col-xxl-5 col-sm-12">
			<div class="search-box">
				<input name="q" type="text" class="form-control search bg-light border-light"
					placeholder="Ketikkan pencarian lakukan tekan enter..">
				<i class="ri-search-line search-icon"></i>
			</div>
		</div>
	</div>
</form>
<hr>
@endif

@if(session()->has('message') || session()->has('errorMessage'))
    @if(session('success'))
    <x-alert type="success" :message="session('message') ?? session('errorMessage')" />
    @else
    <x-alert type="danger" :message="session('message') ?? session('errorMessage')" />
    @endif
@endif

<div class="table-responsive" style="min-height: 380px;">
	<table id="formfilter" class="table align-middle mb-0 {{ $noWrap ? 'table-nowrap' : '' }}">
		<thead class="table-light">
            <tr>
                @if($useCheckbox)
                <th width="30" class="align-middle p-0">
                    @if(count($models) > 0)
                    <div class="form-check form-checkbox-outline form-check-secondary m-0 p-0">
                        <label class="m-0 p-2" role="button">
                            <input class="form-check-input m-0 check-all" type="checkbox">
                        </label>
                    </div>
                    @endif
                </th>
                @endif
                @if($useNumber)
                <th width="40" class="align-middle">#</th>
                @endif
                @foreach($columns as $h)
                    @if(empty($h['hide']))
                    <th scope="col" class="align-middle"
                        @if(!empty($h['width'])) width="{{ $h['width'] }}" @endif
                        @if(!empty($h['minWidth']) || !empty($h['maxWidth']))
                            style="{{ exist($h['minWidth'] ?? null, prefix: 'min-width: ', suffix: ';') . exist($h['maxWidth'] ?? null, prefix: 'max-width: ', suffix: ';') }}"
                        @endif
                    >
                        @php $label = $h['label']; @endphp
                        @if(!empty($h['sort']))
                            @php $sorting = is_string($h['sort']) ? $h['sort'] : $h['field']; @endphp
                            <a href="{{ url("{$mainURL}?" . mergeSortRequest(request()->all(), ['sort_by' => $sorting, 'sort_type' => request()->sort_by == $sorting ? $sortType : 'asc'])) }}">
                                {!! $label !!} <i class="fas fa-sort{{ request()->sort_by == $sorting ? '-'.$upOrDown : ''; }}"></i>
                            </a>
                        @else
                            {!! $label !!}
                        @endif
                    </th>
                    @endif
                @endforeach
                @if(!empty($action))
                <th scope="col" class="align-middle">
                    Aksi
                </th>
                @endif
			</tr>
		</thead>
		<tbody>
            @if(in_array(true, array_column($columns, 'search')))
            <form action="{{ url()->current() }}" method="get">
            <tr>
                @if($useCheckbox)
                <td></td>
                @endif
                @if($useNumber)
                <td></td>
                @endif

                @foreach($columns as $h)
                    @if(empty($h['hide']))
                    <td>
                        @if(!empty($h['search']))
                            @if(is_callable($h['search']))
                            {!! Blade::render($h['search']()) !!}
                            @else
                            @php $search = is_string($h['search']) ? $h['search'] : $h['field']; @endphp
                            <input type="search" class="form-control form-control-sm" autocomplete="off" name="{{ $search }}" value="{{ request()->{$search} }}">
                            @endif
                        @endif
                    </td>
                    @endif
                @endforeach

                @if(!empty($action))
                <th scope="col">
                    @if(!isDefaultRequest())
                    <button type="button" class="btn btn-sm btn-outline-light clear-filter" title="Bersihkan Filter"><i class="fas fa-eraser text-secondary"></i></button>
                    @endif
                </th>
                @endif
            </tr>
            </form>
            @endif

            @foreach($models as $key => $item)
                @if(empty($exclude) || (is_callable($exclude) && $exclude($item) !== true))
                <tr
                    @if(is_callable($rowAttributes) && is_array($rowAttributes($item))) {{ new ComponentAttributeBag($rowAttributes($item)) }} @endif
                    @if(!empty($primaryKey)) data-id="{{ encodeId($item->{$primaryKey}) }}" @endif
                >
                    @if($useCheckbox)
                    <td class="p-0">
                        <div class="form-check form-checkbox-outline form-check-secondary m-0 p-0">
                            <label class="m-0 p-2" role="button">
                                <input class="form-check-input m-0 check-one" type="checkbox">
                            </label>
                        </div>
                    </td>
                    @endif
                    @if($useNumber)
                    <td>{{ ($models instanceof LengthAwarePaginator ? $models->firstItem() : 1) + $key }}</td>
                    @endif
                    @foreach($columns as $h)
                        @if(empty($h['hide']))
                            @if(!empty($h['value']) && is_callable($h['value']))
                            <td @if(!empty($h['class'])) class="{{ $h['class'] }}" @endif>{!! $h['value']($item) !!}</td>
                            @else
                            <td @if(!empty($h['class'])) class="{{ $h['class'] }}" @endif>{{ $item->{$h['field']} ?? $h['empty'] ?? '-' }}</td>
                            @endif
                        @endif
                    @endforeach

                    @if(!empty($action) && is_callable($action))
                    <td>{!! Blade::render($action($item)) !!}</td>
                    @endif
                </tr>
                @endif
            @endforeach
		</tbody>
	</table>
</div>

{{ $slot }}

@if($models instanceof LengthAwarePaginator)
{!! $models->links() !!}
@endif

@push('script')
<script>
$(function() {
    $('#formfilter .filter').change(function() {
        $('#formfilter form').submit();
    });
    $('#formfilter input[type=search]').on('search', function(e) {
        $('#formfilter form').submit();
    });
    $('#formfilter form').submit(function(e) {
        $('#formfilter .filter,#formfilter input[type=search]').each(function() {
            if(!$(this).val()) $(this).removeAttr('name');
        });
    });
    $('.clear-filter').click(function() {
        location.href = '{{ url($mainURL) }}';
    });

    let filterSelect2Selected = [];
    $('#formfilter .filter-select2').on('select2:open', function() {
        filterSelect2Selected = $(this).val();
    });
    $('#formfilter .filter-select2').on('select2:close', function() {
        const selected = $(this).val();
        if(!filterSelect2Selected.equal(selected)) $('#formfilter form').submit();
    });

    @if($useCheckbox)
    $('[type=checkbox].check-all').prop('checked', isAllCheckboxChecked());
    $('[type=checkbox].check-all').click(function(e) {
        var checked = $(this).is(":checked");
        $('[type=checkbox].check-one').each(function() {
            $(this).prop('checked', checked);
        });
    });
    $('[type=checkbox].check-one').click(function() {
        $('[type=checkbox].check-all').prop('checked', isAllCheckboxChecked());
    });
    @endif
});

@if($useCheckbox)
function isAllCheckboxChecked() {
    let cek = true;
    $('[type=checkbox].check-one').each(function() {
        if(!$(this).is(":checked")) {
            cek = false;
        }
    });
    return cek;
}
function isOneCheckboxChecked() {
    let cek = false;
    $('[type=checkbox].check-one').each(function() {
        if($(this).is(":checked")) {
            cek = true;
        }
    });
    return cek;
}
@endif
</script>
@endpush