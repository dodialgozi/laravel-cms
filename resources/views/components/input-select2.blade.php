<select class="form-control {{ $class ?? '' }}" @if(!empty($id)) id="{{ $id }}" @endif {{ $attributes }}>
    {{ $slot }}
</select>

@if(empty($noScript))
@push('script')
<script>
function {{ $function }}({{ $useSelector ? 'selector' : '' }}) {
    loadSelect2PerPage({!! $useSelector ? 'selector' : (!empty($id) ? "'#{$id}'" : "'.{$class}'") !!}, {
        url: `{!! $url !!}`,
        placeholder: '{{ $placeholder }}',
        @if(!empty($objectValue))
            @if(!empty($key))
            key: '{{ $key }}',
            @endif
        objectValue: {!! $objectValue !!},
        @else
        key: '{{ $key }}',
        value: '{{ $value }}',
        @endif
        @if(!empty($hashId))
        hashId: {{ $hashId ? 'true' : 'false' }},
        @endif
        disableSearch: {{ $disableSearch ? 'true' : 'false' }},
        allowClear: {{ $allowClear ? 'true' : 'false' }},
        tags: {{ $tags ? 'true' : 'false' }},
        closeOnSelect: {{ $closeOnSelect ? 'true' : 'false' }}
    });
}
@if($autoInit)
$(function() {
    {{ $function }}();
});
@endif
</script>
@endpush
@endif