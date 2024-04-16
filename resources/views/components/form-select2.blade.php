@if($horizontal)
    <div class="row">
        @isset($label)
        <label class="col-md-{{ $labelSize }} col-form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <div class="form-group col-md-{{ $inputSize }}">
            <x-input-select2 :url=$url :key=$key :value=$value :objectValue=$objectValue :function=$function :autoInit=$autoInit :useSelector=$useSelector :noScript=$noScript :id=$id :class=$class :hashId=$hashId :disableSearch=$disableSearch :allowClear=$allowClear :tags=$tags :closeOnSelect=$closeOnSelect :placeholder=$placeholder :disabled=$disabled :attributes=$attributes>{{ $slot }}</x-input-select2>
        </div>
    </div>
@else
    <div class="form-group">
        @isset($label)
        <label class="form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @endif</label>
        @endisset
        <x-input-select2 :url=$url :key=$key :value=$value :objectValue=$objectValue :function=$function :autoInit=$autoInit :useSelector=$useSelector :noScript=$noScript :id=$id :class=$class :hashId=$hashId :disableSearch=$disableSearch :allowClear=$allowClear :tags=$tags :closeOnSelect=$closeOnSelect :placeholder=$placeholder :disabled=$disabled :attributes=$attributes>{{ $slot }}</x-input-select2>
    </div>
@endif