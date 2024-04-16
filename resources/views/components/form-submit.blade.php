<div class="mt-4 d-flex flex-row justify-content-end">
    @if(empty($hideBack))
    <div class="p-2">
        <a href="{{ url()->previous() == url()->current() && isset($mainURL) ? url($mainURL) : url()->previous() }}" class="btn btn-outline-secondary waves-effect btn-label waves-light"><i class="label-icon fas fa-backspace"></i> {{ $back }}</a>
    </div>
    @endif
    @if(empty($hideSubmit))
    <div class="p-2">
        <button type="submit" class="btn btn-{{ $type }} waves-effect btn-label waves-light" {{ $attributes }}><i class="label-icon {{ $icon }}"></i> {{ $submit }}</button>
    </div>
    @endif
</div>