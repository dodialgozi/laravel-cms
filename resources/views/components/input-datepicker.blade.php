<input {{ $attributes->merge(['type' => 'text', 'class' => 'form-control']) }} @isset($container) data-date-container="#{{ $container }}" @endisset data-date-format="{{ $format }}" data-date-autoclose="true" data-date-today-highlight="true" data-date-language="id" @isset($viewMode) data-date-min-view-mode="{{ $viewMode }}" @endisset data-provide="datepicker" autocomplete="off" />