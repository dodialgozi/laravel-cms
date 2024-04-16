@php if(!empty($id)) $attributes = $attributes->merge(['id' => $id]); @endphp

<div class="input-group" @if(!empty($groupId) && empty($noScript)) id="{{ $groupId }}" @endif>
    <input {{ $attributes->class(['form-control'])->merge([
        'type' => 'text',
        'data-provide' => 'timepicker',
    ]) }} autocomplete="off" />
    <span class="input-group-text"><i class="far fa-clock"></i></span>
</div>

@if(empty($noScript))
@push('script')
<script>
function {{ $function }}({{ $useSelector ? 'selector' : '' }}) {
    $({!! $useSelector ? 'selector' : "'#{$id}'" !!}).timepicker({
        minuteStep: 5,
        showMeridian: false,
        icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
        },
        appendWidgetTo: @if($useSelector || !empty($id)) $({!! $useSelector ? 'selector' : "'#{$id}'" !!}).closest('.input-group') @else '#{{ $groupId }}' @endif,
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