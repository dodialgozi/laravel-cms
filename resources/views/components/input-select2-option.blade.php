<select {{ $attributes->merge(['class' => 'form-control select2']) }} placeholder="{{ $placeholder }}" disable-search="{{ $disableSearch ? 'true' : 'false' }}" allow-clear="{{ $allowClear ? 'true' : 'false' }}" close-on-select="{{ $closeOnSelect ? 'true' : 'false' }}">
    @if(!$attributes->has('multiple') && !$withoutEmpty)
    <option value=""></option>
    @endif
    {!! selectOptions($options, $value) !!}
    {{ $slot }}
</select>