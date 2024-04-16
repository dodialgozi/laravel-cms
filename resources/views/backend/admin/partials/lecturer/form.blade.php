<div class="row">
    <div class="col-md-12 mb-3">
        <x-form-input label="Nama Dosen" name="{{ $input }}[lecturer_name]"
            value="{{ $result->lecturer_name ?? '' }}" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="Email Dosen" name="{{ $input }}[lecturer_email]"
            value="{{ $result->lecturer_email ?? '' }}" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="NIDN Dosen" name="{{ $input }}[lecturer_nidn]"
            value="{{ $result->lecturer_nidn ?? '' }}" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-switch label="Auto Translate" name="{{ $input }}[lecturer_auto_translate]" :checked="true"
            id="lecturer_auto_translate" labelOn="Ya" labelOff="Tdk" />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-textarea label="Bio Dosen (ID)" name="{{ $input }}[lecturer_bio_id]"
            value="{{ $result->lecturer_bio_id ?? '' }}" id="lecturer_bio_id" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-textarea label="Bio Dosen (EN)" name="{{ $input }}[lecturer_bio_en]"
            value="{{ $result->lecturer_bio_en ?? '' }}" id="lecturer_bio_en" />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-file label="Foto Dosen" name="{{ $inputFile }}[lecturer_photo]" :value="$result->lecturer_thumbnail ?? null" :image=true
            :download=false />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-switch label="Status Aktif" name="{{ $input }}[lecturer_active]" :checked="!empty($result->lecturer_active ?? 1)"
            labelOn="Ya" labelOff="Tdk" />
    </div>
</div>

@push('script')
    <script>
        // hide lecturer bio en
        $('#lecturer_bio_en').closest('.mb-3').hide();
        // show lecturer bio en when auto translate is off
        $('#lecturer_auto_translate').change(function() {
            // this element is not input, so we need to find the child input
            const input = $(this).find('input');
            if (input.prop('checked')) {
                $('#lecturer_bio_en').closest('.mb-3').hide();
            } else {
                $('#lecturer_bio_en').closest('.mb-3').show();
            }
        });
    </script>
@endpush
