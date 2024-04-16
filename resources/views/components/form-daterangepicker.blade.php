@if($horizontal)
    <div class="row">
        @isset($label)
        <label class="col-md-{{ $labelSize }} col-form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <div class="form-group col-md-{{ $inputSize }}">
            <x-input-daterangepicker :id=$id :format=$format :placeholderStart=$placeholderStart :placeholderEnd=$placeholderEnd :nameStart=$nameStart :nameEnd=$nameEnd :valueStart=$valueStart :valueEnd=$valueEnd />
        </div>
    </div>
@else
    <div class="form-group">
        @isset($label)
        <label class="form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <x-input-daterangepicker :id=$id :format=$format :placeholderStart=$placeholderStart :placeholderEnd=$placeholderEnd :nameStart=$nameStart :nameEnd=$nameEnd :valueStart=$valueStart :valueEnd=$valueEnd />
    </div>
@endif