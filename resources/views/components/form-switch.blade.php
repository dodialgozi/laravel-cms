@if ($horizontal)
    <div class="row" @if (!empty($id)) id="{{ $id }}" @endif>
        @isset($label)
            <label class="col-md-{{ $labelSize }} col-form-label">{!! $label !!} @if (!empty($required))
                    <span class="text-danger">*</span>
                @endif
            </label>
        @endisset
        <div class="form-group col-md-{{ $inputSize }}">
            <x-input-switch :name=$name :labelOn=$labelOn :labelOff=$labelOff :square=$square :type=$type
                :checked=$checked :title=$title :titlePosition=$titlePosition :attributes=$attributes
                :required=$required />
        </div>
    </div>
@else
    <div class="form-group" @if (!empty($id)) id="{{ $id }}" @endif>
        @isset($label)
            <label class="form-label">{!! $label !!} @if (!empty($required))
                    <span class="text-danger">*</span>
                @endif
            </label>
        @endisset
        <div>
            <x-input-switch :name=$name :labelOn=$labelOn :labelOff=$labelOff :square=$square :type=$type
                :checked=$checked :title=$title :titlePosition=$titlePosition :attributes=$attributes
                :required=$required />
        </div>
    </div>
@endif
