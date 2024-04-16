<div class="input-daterange input-group" id="{{ $id }}" @isset($id) data-date-container="#{{ $id }}" @endisset data-date-format="{{ $format }}" data-date-autoclose="true" data-date-today-highlight="true" data-date-language="id" data-provide="datepicker" autocomplete="off">
    <input type="text" class="form-control date-start" name="{{ $nameStart }}" value="{{ $valueStart }}" placeholder="{{ $placeholderStart }}" />
    <input type="text" class="form-control date-end" name="{{ $nameEnd }}" value="{{ $valueEnd }}" placeholder="{{ $placeholderEnd }}" />
</div>