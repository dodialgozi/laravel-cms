@once
@if(session()->has('message') || session()->has('errorMessage'))

@php
    $message = base64_encode(session('message'));
    $errorMessage = base64_encode(session('errorMessage'));
@endphp

@push('script')
<script type="text/javascript">
$(function() {
    setTimeout(() => {
        @if(session('success'))
            swal.fire('Berhasil', atob('{{ $message }}'), 'success');
            setTimeout(() => {
                swal.close();
            }, {{ session()->has('time') ? session('time') : 1200 }});
        @else
            @if(session()->has('message'))
            swal.fire({title: 'Maaf', html: atob('{{ $message }}'), icon: 'error'});
            @else
            swal.fire('Maaf', 'Terjadi kesalahan.', 'error');
            @endif
            @if(session()->has('errorMessage'))
            console.error(atob('{{ $errorMessage }}'));
            @endif
        @endif
    }, 850);
});
</script>
@endpush

@endif
@endonce