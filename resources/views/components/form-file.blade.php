@php $attributes = $attributes->class(['form-control'])->merge([
    'type' => 'file',
    'placeholder' => $placeholder,
]); if($image) $attributes = $attributes->merge(['accept' => 'image/*']); @endphp

@if($horizontal)
    <div class="row">
        @isset($label)
        <label class="col-md-{{ $labelSize }} col-form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @elseif(!empty($value)) <br><small class="fst-italic">(Kosongkan jika tidak ingin mengganti)</small> @endif</label>
        @endisset
        <div class="form-group col-md-{{ $inputSize }}">
            @if(isset($prepend) || isset($append))
            <div class="input-group">
                @if(isset($prepend))
                <span class="input-group-text">{{ $prepend }}</span>
                @endif
                <input {{ $attributes }} />
                @if(isset($append))
                <span class="input-group-text">{{ $append }}</span>
                @endif
            </div>
            @else
            <input {{ $attributes }} />
            @endif
            @if(!empty($value) && ($image || $download))
            <div class="mt-1">
                @if($image)
                <a href="{{ $value }}" class="doc-preview">
                    <img src="{{ fileThumbnail($value, size: $thumbnail) }}" class="img-thumbnail thumb-150" referrerpolicy="no-referrer">
                </a>
                @endif
                @if($download)
                    <a href="{{ fileDownload($value) }}" class="btn btn-primary btn-sm waves-effect waves-light btn-file" target="_blank">
                        <i class="mdi mdi-file-download-outline d-block fs-2"></i> Download
                    </a>
                    @if(!$image && !empty(goDriveId($value)))
                    <a href="{{ goDriveView($value) }}" class="btn btn-info btn-sm waves-effect waves-light btn-file doc-preview">
                        <i class="mdi mdi-file-document-outline d-block fs-2"></i> Lihat
                    </a>
                    @endif
                @endif
            </div>
            @endif
        </div>
    </div>
@else
    <div class="form-group">
        @isset($label)
        <label class="form-label">{!! $label !!} @if(!empty($attributes['required'])) <span class="text-danger">*</span> @elseif(!empty($value)) <small class="fst-italic">(Kosongkan jika tidak ingin mengganti)</small> @endif</label>
        @endisset
        @if(!empty($value) && ($image || $download))
        <div class="mb-1">
            @if($image)
            <a href="{{ $value }}" class="doc-preview">
                <img src="{{ fileThumbnail($value, size: $thumbnail) }}" class="img-thumbnail thumb-150" referrerpolicy="no-referrer">
            </a>
            @endif
            @if($download)
                <a href="{{ fileDownload($value) }}" class="btn btn-primary btn-sm waves-effect waves-light btn-file" target="_blank">
                    <i class="mdi mdi-file-download-outline d-block fs-2"></i> Download
                </a>
                @if(!$image && !empty(goDriveId($value)))
                <a href="{{ goDriveView($value) }}" class="btn btn-info btn-sm waves-effect waves-light btn-file doc-preview">
                    <i class="mdi mdi-file-document-outline d-block fs-2"></i> Lihat
                </a>
                @endif
            @endif
        </div>
        @endif
        @if(isset($prepend) || isset($append))
        <div class="input-group">
            @if(isset($prepend))
            <span class="input-group-text">{{ $prepend }}</span>
            @endif
            <input {{ $attributes }} />
            @if(isset($append))
            <span class="input-group-text">{{ $append }}</span>
            @endif
        </div>
        @else
        <input {{ $attributes }} />
        @endif
    </div>
@endif