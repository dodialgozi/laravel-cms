<div class="form-floating">
    <div class="form-control h-auto bg-white fs-5 text-break m-0 {{ isset($type) && $type == 'images' ? 'd-flex flex-wrap justify-content-center align-items-center' : '' }}">
    @isset($type)
        @if($type == 'doc')
            @if(!empty($url = $value))
                <a href="{{ fileDownload($url) }}" class="btn btn-primary btn-sm waves-effect waves-light btn-file" target="_blank">
                    <i class="mdi mdi-file-download-outline d-block fs-2"></i> Download
                </a>
                @if(!empty(goDriveId($url)))
                <a href="{{ goDriveView($url) }}" class="btn btn-info btn-sm waves-effect waves-light btn-file doc-preview">
                    <i class="mdi mdi-file-document-outline d-block fs-2"></i> Lihat
                </a>
                @endif
            @else
            <i>-</i>
            @endif
        @elseif($type == 'doc-thumb')
            @if(!empty($url = $value))
                @if(!empty(goDriveId($url)))
                <a href="{{ goDriveView($url) }}" class="doc-preview">
                    <img src="{{ goDriveThumb($url) }}" class="img-thumbnail thumb-150" referrerpolicy="no-referrer">
                </a>
                @else
                <a href="{{ $url }}" class="btn btn-primary btn-sm waves-effect waves-light btn-file" target="_blank">
                    <i class="mdi mdi-file-download-outline d-block fs-2"></i> Download
                </a>
                @endif
            @else
            <i>-</i>
            @endif
        @elseif($type == 'image')
            @if(!empty($url = $value))
            <a href="{{ $url }}" class="doc-preview">
                <img src="{{ fileThumbnail($url) }}" class="img-thumbnail thumb-150" referrerpolicy="no-referrer">
            </a>
            @else
            <i>-</i>
            @endif
        @elseif($type == 'images')
            @if(!empty($values))
                @foreach($values as $url)
                <a href="{{ $url }}" class="m-1 doc-preview">
                    <img src="{{ fileThumbnail($url) }}" class="img-thumbnail thumb-150" referrerpolicy="no-referrer">
                </a>
                @endforeach
            @else
            <i>-</i>
            @endif
        @elseif($type == 'newline')
            {!! printNewLine($value) !!}
        @elseif($type == 'map')
            <div id="map-detail" class="mt-1" data-map="{{ json_encode([
                'title' => $value,
                'latlong' => [
                    'lat' => $latitude,
                    'lng' => $longitude,
                ],
            ]) }}" style="height: {{ $height ?? '450px' }};"></div>
        @elseif($type == 'html')
            {!! $value !!}
        @elseif($type == 'json')
            <div class="json">{!! $value !!}</div>
        @endif
    @else
        {{ $value }}{{ $slot }}
    @endisset
    </div>
    <label>{{ $label }}</label>
</div>