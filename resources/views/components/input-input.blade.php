@php $attributes = $attributes->class(['form-control'])->merge([
    'type' => $attributes['type'] ?? 'text',
    'autocomplete' => 'off',
]); @endphp

@if(isset($prepend) || isset($append))
<div class="input-group">
    @if(isset($prepend))
    @if($prependWrap)
    <span class="input-group-text">{{ $prepend }}</span>
    @else
    {{ $prepend }}
    @endif
    @endif
    <input {{ $attributes }} />
    @if(isset($append))
    @if($appendWrap)
    <span class="input-group-text">{{ $append }}</span>
    @else
    {{ $append }}
    @endif
    @endif
</div>
@else
<input {{ $attributes }} />
@endif