<x-modal id="{{ $id }}" :title=$title :size=$size />

@push('script')
<script>
$(function() {
    const modal = $('#{{ $id }}');
    const modalBody = modal.find('.modal-body');
    const modalLoader = modalBody.html();
    $(document).on('click', '.{{ $buttonClass }}', function(e) {
        e.preventDefault();
        modalBody.html(modalLoader);
        modal.modal('show');
        $.ajax({
            url: $(this).attr('href'),
        })
        .done(data => {
            modalBody.html(data);

            modalBody.find('.json').each(function() {
                $(this).jsonViewer(JSON.parse($(this).text()));
            });

            const $mapDetail = modalBody.find('#map-detail');
            if($mapDetail.length) {
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
        })
        .fail(xhr => {
            if(xhr.status == 404) {
                modalBody.html(`<center>Data tidak ditemukan.</center>`);
            } else {
                modalBody.html(`<center>Tidak dapat terhubung ke server.</center>`);
            }
        });
    });
});
</script>
@endpush