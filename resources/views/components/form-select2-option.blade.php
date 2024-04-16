@if($horizontal)
    <div class="row">
        @isset($label)
        <label class="col-md-{{ $labelSize }} col-form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <div class="form-group col-md-{{ $inputSize }}">
            <x-input-select2-option :options=$options :value=$value :disableSearch=$disableSearch :allowClear=$allowClear :closeOnSelect=$closeOnSelect :withoutEmpty=$withoutEmpty :placeholder=$placeholder :attributes=$attributes>{{ $slot }}</x-input-select2>
        </div>
    </div>
@else
    <div class="form-group">
        @isset($label)
        <label class="form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <x-input-select2-option :options=$options :value=$value :disableSearch=$disableSearch :allowClear=$allowClear :closeOnSelect=$closeOnSelect :withoutEmpty=$withoutEmpty :placeholder=$placeholder :attributes=$attributes>{{ $slot }}</x-input-select2>
    </div>
@endif