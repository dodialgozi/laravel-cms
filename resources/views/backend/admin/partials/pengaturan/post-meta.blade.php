@extends('backend.layouts.panel')

@section('title', "{$title}")

@php
$meta_type = $meta_type ?? [];
@endphp

@section('panel_content')
<form id="form" action="{{ url("{$mainURL}/save") }}" method="POST">
    @csrf

    <div id="customField">
        @if(!empty($data ?? null))
        @forelse ($data as $item)
        @php
        $rand = randomGen(12);
        @endphp
        <div id="customField-{{ $rand }}">
            <div class="row mb-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="button" class="btn-clear btn-remove text-danger"><i
                            class="bx bx-x bx-sm"></i></button>
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input label="Key" class="key" name="{{ $input }}[{{ $rand }}][setting_meta_code]"
                        value="{{ $item->setting_meta_code ?? '' }}" id="setting_meta_code" required />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-select2-option label="Tipe" class="type" name="{{ $input }}[{{ $rand }}][setting_meta_type]"
                        :options=$meta_type :disableSearch=true value="{{ $item->setting_meta_type ?? 'field' }}"
                        id="setting_meta_type_{{ $rand }}" />
                </div>
                <div id="valueField_{{ $rand }}" class="col-md-4 mb-3">
                    @if ($item->setting_meta_type == 'field')
                    <x-form-input label="Value" class="value" name="{{ $input }}[{{ $rand }}][setting_meta_value]"
                        value="{{ $item->setting_meta_value ?? '' }}" id="setting_meta_value" required />
                    @elseif ($item->setting_meta_type == 'boolean')
                    <x-form-switch label="Value" class="value" name="{{ $input }}[{{ $rand }}][setting_meta_value]"
                        :checked="!empty($item->setting_meta_value ?? 0)" :square=true labelOn="Ya" labelOff="Tdk" />
                    @endif
                </div>
            </div>
        </div>
        @empty
        @php
        $rand = randomGen(12);
        @endphp
        <div id="customField-{{ $rand }}">
            <div class="row mb-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="button" class="btn-clear btn-remove text-danger"><i
                            class="bx bx-x bx-sm"></i></button>
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-input label="Key" class="key" name="{{ $input }}[{{ $rand }}][setting_meta_code]" value=""
                        id="setting_meta_code" required />
                </div>
                <div class="col-md-4 mb-3">
                    <x-form-select2-option label="Tipe" class="type" name="{{ $input }}[{{ $rand }}][setting_meta_type]"
                        :options=$meta_type :disableSearch=true value="field" id="setting_meta_type_{{ $rand }}" />
                </div>
                <div id="valueField_{{ $rand }}" class="col-md-4 mb-3">
                    <x-form-input label="Value" class="value" name="{{ $input }}[{{ $rand }}][setting_meta_value]"
                        value="" id="setting_meta_value" required />
                </div>
            </div>
        </div>
        @endforelse
        @endif
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary" id="addCustomField">
        <i class="fas fa-plus"></i> Tambah Field
    </button>

    <x-form-submit :hideBack=true />
</form>

<script id="template" type="text/template">
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" class="btn-clear btn-remove text-danger"><i class="bx bx-x bx-sm"></i></button>
        </div>
        <div class="col-md-4 mb-3">
            <x-form-input label="Key" class="key" name="" value="" id="setting_meta_code" required />
        </div>
        <div class="col-md-4 mb-3">
            <x-form-select2-option label="Tipe" class="type" name="" :options=$meta_type :disableSearch=true value="field" id="setting_meta_type_" />
        </div>
        <div id="valueField_" class="col-md-4 mb-3">
            <x-form-input label="Value" class="value" name="" value="" id="setting_meta_value" required />
        </div>
    </div>
</script>
@endsection

<x-validation selector="#form" />
<x-swal-message />

@push('style')
<style>
    .btn-clear {
        border: 0;
        background: none;
        padding: 0;
    }
</style>
@endpush

@push('script')
<script>
    var template;
    $(function() {
        template = $('#template').html();
    
        $('#addCustomField').click(function() {
            const rand = randomGen(12);
            $('#customField').append(`<div id="customField-${rand}">${template}</div>`);

            $(`#customField-${rand} .key`).attr('name', '{{ $input }}[' + rand + '][setting_meta_code]');
            $(`#customField-${rand} .type`).attr('name', '{{ $input }}[' + rand + '][setting_meta_type]');
            $(`#customField-${rand} .value`).attr('name', '{{ $input }}[' + rand + '][setting_meta_value]');

            $(`#customField-${rand} [id^="setting_meta_type_"]`).attr('id', 'setting_meta_type_' + rand);
            $(`#customField-${rand} #valueField_`).attr('id', 'valueField_' + rand);

            // refresh select2
            $(`#customField-${rand} [id^="setting_meta_type_"]`).select2({
                placeholder: 'Pilih Tipe',
                minimumResultsForSearch: -1
            });
        });
    
        $(document).on('click', '.btn-remove', function(e) {
            $(this).closest('.row').remove();
        });
    });

    // change meta_value depend on meta_type
    $(document).on('change', '[id^="setting_meta_type_"]', function(e) {
        const rand = $(this).attr('id').split('_')[3];
        const value = $(this).val();
        const parent = $(this).closest('.row');
        const input = $(`#customField-${rand} .value`);

        $('#valueField_' + rand).html('');

        if (value == 'field') {
            $('#valueField_' + rand).append(`<x-form-input label="Value" class="value" name="{{ $input }}[${rand}][setting_meta_value]" value="" id="setting_meta_value" required />`);
        } else if (value == 'boolean') {
            $('#valueField_' + rand).append(`<x-form-switch label="Value" class="value" name="{{ $input }}[${rand}][setting_meta_value]" :checked="!empty($item->setting_meta_value ?? 0)" :square=true labelOn="Ya" labelOff="Tdk" />`);
        }
    });
</script>
@endpush