<div class="m-0 {{ $square ? 'square-switch' : '' }}"
    @if (!empty($title)) title="{{ $title }}" data-bs-toggle="tooltip" data-bs-placement="{{ $titlePosition ?? 'top' }}" @endif>
    <input type="checkbox" id="{{ $id }}" switch="{{ $type }}" {{ $attributes }}
        @if ($checked) checked @endif>
    <label class="m-0" for="{{ $id }}" data-on-label="{{ $labelOn }}"
        data-off-label="{{ $labelOff }}"></label>
</div>
