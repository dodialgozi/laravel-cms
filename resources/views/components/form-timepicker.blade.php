@php $attributes = $attributes->merge([
    'placeholder' => $placeholder,
]); @endphp

@if($horizontal)
    <div class="row">
        @isset($label)
        <label class="col-md-{{ $labelSize }} col-form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <div class="form-group col-md-{{ $inputSize }}">
            <x-input-timepicker :id=$id :groupId=$groupId :function=$function :autoInit=$autoInit :useSelector=$useSelector :noScript=$noScript {{ $attributes }} />
        </div>
    </div>
@else
    <div class="form-group">
        @isset($label)
        <label class="form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <x-input-timepicker :id=$id :groupId=$groupId :function=$function :autoInit=$autoInit :useSelector=$useSelector :noScript=$noScript {{ $attributes }} />
    </div>
@endif