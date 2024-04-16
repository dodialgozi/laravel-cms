<div class="row">
    <div class="col-md-12 mb-3">
        <x-form-file label="Image" name="{{ $inputFile }}[alumnus_image]" :value="$result->alumnus_image ?? null" :image=true
            :download=false />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="NIM" name="{{ $input }}[alumnus_nim]" value="{{ $result->alumnus_nim ?? '' }}"
            required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="NIRM" name="{{ $input }}[alumnus_nirm]" value="{{ $result->alumnus_nirm ?? '' }}"
            required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="NIRL" name="{{ $input }}[alumnus_nirl]" value="{{ $result->alumnus_nirl ?? '' }}"
            required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="Email" name="{{ $input }}[alumnus_email]"
            value="{{ $result->alumnus_email ?? '' }}" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="Name" name="{{ $input }}[alumnus_name]" value="{{ $result->alumnus_name ?? '' }}"
            required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="Profession" name="{{ $input }}[alumnus_profession]"
            value="{{ $result->alumnus_profession ?? '' }}" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="Statement (ID)" name="{{ $input }}[alumnus_statement_id]"
            value="{{ $result->alumnus_statement_id ?? '' }}" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="Statement (EN)" name="{{ $input }}[alumnus_statement_en]" id="alumnus_statement_en"
            value="{{ $result->alumnus_statement_en ?? '' }}" required />
    </div>

    <div class="col-md-6 mb-3">
        <x-form-switch label="Auto Translate" name="{{ $input }}[alumnus_auto_translate]" :checked="!empty($result->alumnus_statement_en ?? 1)"
            id="alumnus_auto_translate" labelOn="Ya" labelOff="Tdk" />
    </div>
    <!-- radio for publish or draft -->
    <div class="col-md-12 mb-3">
        <x-form-radio label="Status" name="{{ $input }}[alumnus_status]" :options="['publish' => 'Publish', 'draft' => 'Draft']" :checked="$result->alumnus_status ?? 'draft'"
            required />
    </div>
</div>
@push('script')
    <script>
        $('#alumnus_statement_en').closest('.mb-3').hide();

        $('#alumnus_auto_translate').change(function() {
            const input = $(this).find('input');
            if (input.prop('checked')) {
                $('#alumnus_statement_en').closest('.mb-3').hide();
            } else {
                $('#alumnus_statement_en').closest('.mb-3').show();
            }
        });
    </script>
@endpush
