@if($horizontal)
    <div class="row">
        @isset($label)
        <label class="col-md-{{ $labelSize }} col-form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <div class="form-group col-md-{{ $inputSize }}">
            <div class="input-group" id="{{ $groupId }}">
                <x-input-datepicker :container=$groupId placeholder="{{ $placeholder }}" :container=$container :format=$format :viewMode=$viewMode :attributes=$attributes />

                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
            </div>
        </div>
    </div>
@else
    <div class="form-group">
        @isset($label)
        <label class="form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <div class="input-group" id="{{ $groupId }}">
            <x-input-datepicker :container=$groupId placeholder="{{ $placeholder }}" :container=$container :format=$format :viewMode=$viewMode :attributes=$attributes />

            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
        </div>
    </div>
@endif