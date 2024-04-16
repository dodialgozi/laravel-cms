@php if(!$clean) $attributes = $attributes->class(['form-control', 'bg-light']); @endphp

@if($horizontal)
    <div class="row">
        @isset($label)
        <label class="col-md-{{ $labelSize }} col-form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <div class="form-group col-md-{{ $inputSize }}">
            <x-input-data :value=$value :prepend=$prepend :prependWrap=$prependWrap :append=$append :appendWrap=$appendWrap {{ $attributes }}>{{ $slot }}</x-input-data>
        </div>
    </div>
@else
    <div class="form-group">
        @isset($label)
        <label class="form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <x-input-data :value=$value :prepend=$prepend :prependWrap=$prependWrap :append=$append :appendWrap=$appendWrap {{ $attributes }}>{{ $slot }}</x-input-data>
    </div>
@endif