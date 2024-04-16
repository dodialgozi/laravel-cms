<div class="form-check form-check-{{ $type }}">
    <input type="checkbox" class="form-check-input" id="{{ $id }}" {{ $attributes }} @if($checked) checked @endif @if($required) required @endif>
    <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
</div>