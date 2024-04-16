@php
$defaultSize = $defaultSize ?? 'col-md-6';
$typeSize = $typeSize ?? [
    'doc' => 'col-md-6 col-lg-4',
    'doc-thumb' => 'col-md-6 col-lg-4',
    'image' => 'col-md-6 col-lg-4',
];
@endphp

<div class="row">
    @foreach ($result as $key => $value)
        @if(is_numeric($key))
            @if(empty($value))
            <div class="col-md-12"></div>
            @else
            <hr class="my-4">
            <div class="col-md-12">
                <h3 class="mb-3">{{ $value }}</h3>
            </div>
            @endif
        @elseif(empty($value['hide']))
            @php
                $class = $defaultSize;
                $class = !empty($value['type']) && !empty($typeSize[$value['type']]) ? $typeSize[$value['type']] : $class;
                $class = $value['class'] ?? $class;
            @endphp
        <div class="{{ $class }} {{ $key !== array_key_last($result) ? 'mb-3' : '' }}">
            @php $value = is_array($value) ? $value : ['value' => $value]; @endphp
            <x-show-data :type="$value['type'] ?? null" :label=$key :value="$value['value'] ?? null" :values="$value['values'] ?? []" :latitude="$value['latitude'] ?? null" :longitude="$value['longitude'] ?? null" :height="$value['height'] ?? null" />
        </div>
        @endif
    @endforeach
</div>

@if(!request()->ajax())
@push('script')
<script>
$(function() {
    $('.json').each(function() {
        $(this).jsonViewer(JSON.parse($(this).text()));
    });

    const $mapDetail = $('#map-detail');
    if($mapDetail.length) {
        const $mapDetail = $('#map-detail');
        const data = $mapDetail.data('map');
        
        const map = L.map('map-detail', {
            center: data.latlong,
            zoom: 15
        });

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: data.title,
            maxZoom: 18
        }).addTo(map);
        
        L.marker(data.latlong).addTo(map);
    }
});
</script>
@endpush
@endif