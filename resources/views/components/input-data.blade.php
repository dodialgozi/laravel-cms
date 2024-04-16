@if(isset($prepend) || isset($append))
<div class="input-group">
    @if(isset($prepend))
    @if($prependWrap)
    <span class="input-group-text">{{ $prepend }}</span>
    @else
    {{ $prepend }}
    @endif
    @endif
    <div {{ $attributes }}>{{ $value }}{{ $slot }}</div>
    @if(isset($append))
    @if($appendWrap)
    <span class="input-group-text">{{ $append }}</span>
    @else
    {{ $append }}
    @endif
    @endif
</div>
@else
<div {{ $attributes }}>{{ $value }}{{ $slot }}</div>
@endif