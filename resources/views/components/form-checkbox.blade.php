@php if(empty($listing)) $attributes = $attributes->class(['form-check-group']); @endphp

@if($horizontal)
    <div class="row" @if(!empty($id)) id="{{ $id }}" @endif>
        @isset($label)
        <label class="col-md-{{ $labelSize }} col-form-label">{!! $label !!} @if(!empty($required)) <span class="text-danger">*</span> @endif</label>
        @endisset
        <div class="form-group col-md-{{ $inputSize }}">
            <div {{ $attributes }}>
                @foreach ($options as $key => $option)
                <x-input-checkbox label="{{ $label }}" name="{{ $name }}" value="{{ $key }}" :checked="$strictValue ? $key===$value : $key==$value" :required="!empty($required)" />
                @endforeach
                {{ $slot }}
            </div>
        </div>
    </div>
@else
    <div class="form-group" @if(!empty($id)) id="{{ $id }}" @endif>
        @isset($label)
        <label class="form-label">{!! $label !!} @if(!empty($required)) <span class="text-danger">*</span> @endif</label>
        @endisset
        <div {{ $attributes }}>
            @foreach ($options as $key => $label)
            <x-input-checkbox label="{{ $label }}" name="{{ $name }}" value="{{ $key }}" :checked="$strictValue ? $key===$value : $key==$value" :required="!empty($required)" />
            @endforeach
            {{ $slot }}
        </div>
    </div>
@endif