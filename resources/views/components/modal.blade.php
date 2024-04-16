@push('modal')
<div @if(!empty($id)) id="{{ $id }}" @endif class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }}">
        <div class="modal-content">
            @if(isset($form))
            <form {{ $form }}>
            @endif

            @if($isContent && $slot->isNotEmpty())
                {{ $slot }}
            @else
                @if(!$withoutHeader)
                <div class="modal-header">
                    @isset($header)
                    {{ $header }}
                    @else
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    @endisset
                </div>
                @endif
                <div class="modal-body">
                    @if($slot->isNotEmpty())
                        {{ $slot }}
                    @else
                    <div class="loader-center">
                        <div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </div>
                    @endif
                </div>
                @if(!$withoutFooter)
                <div class="modal-footer">
                    @isset($footer)
                    {{ $footer }}
                    @else
                    <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Tutup</button>
                    @endisset
                </div>
                @endif
            @endif

            @if(isset($form))
            </form>
            @endif
        </div>
    </div>
</div>
@endpush