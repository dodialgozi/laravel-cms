@extends('backend.layouts.panel')

@section('title', "Tambah {$title}")

@section('panel_content')
    <form id="formcreate" action="{{ url($mainURL) }}" method="POST">
        @csrf

        @php
            $rand = randomGen(12);
        @endphp

        <div class="row">
            <div class="col-md-6 mb-3">
                <x-form-input class="tagnameid" label="Tag" name="{{ $input }}[{{ $rand }}][tag_name_id]"
                    required />
            </div>
            <div class="col-md-6 mb-3">
                <x-form-input class="tagnameen" label="Tag" name="{{ $input }}[{{ $rand }}][tag_name_en]"
                    required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <x-form-switch class="tagpopular" label="Popular"
                    name="{{ $input }}[{{ $rand }}][tag_popular]" labelOn="Ya" labelOff="Tdk" />
            </div>
            <div class="col-md-6 mb-3">
                <x-form-switch class="tagauto" label="Auto Translate"
                    name="{{ $input }}[{{ $rand }}][tag_auto_translate]" id="tag_auto_translate"
                    :checked="true" labelOn="Ya" labelOff="Tdk" onchange="showTagEn('{{ $rand }}')" />
            </div>
        </div>

        <div id="tag" class="col-md-12"></div>

        <div class="col-md-12">
            <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm mt-2 btn-add"><i
                    class="bx bx-plus"></i> Tambah</button>
        </div>

        <x-form-submit />
    </form>
    <script id="template" type="text/template">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-end mt-2">
            <button type="button" class="btn-clear btn-remove text-danger"><i class="bx bx-x bx-sm"></i></button>
        </div>
        <div class="row mt-2">
            <div class="col-md-6 mb-3">
                <x-form-input class="tagnameid" label="Tag" name="" required />
            </div>
            <div class="col-md-6 mb-3">
                <x-form-input class="tagnameen" label="Tag" name="" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <x-form-switch class="tagpopular" label="Popular" name="" labelOn="Ya" labelOff="Tdk" />
            </div>

            <div class="col-md-6 mb-3">
                <x-form-switch class="tagauto" label="Auto Translate" name="" labelOn="Ya" labelOff="Tdk" />
            </div>
        </div>
    </div>
</script>
@endsection

<x-validation selector="#formcreate" />

@push('style')
    <style>
        .btn-clear {
            border: 0;
            background: none;
            padding: 0;
        }

        #tag>.row {
            border: 1px solid #eaeaea;
            border-radius: 5px;
        }

        #tag>.row~.row {
            margin-top: .75rem;
        }
    </style>
@endpush

@push('script')
    <script>
        // hide tag name en
        $('.tagnameen').closest('.mb-3').hide();
        var template;
        $(function() {
            template = $('#template').html();

            $('.btn-add').click(function() {
                const rand = randomGen(12);
                $('#tag').append(`<div id="tag-${rand}">${template}</div>`);
                $(`#tag-${rand} .tagnameid`).prop('required', true);

                $(`#tag-${rand} .tagnameid`).attr('name', '{{ $input }}[' + rand +
                    '][tag_name_id]');
                $(`#tag-${rand} .tagpopular`).attr('name', '{{ $input }}[' + rand +
                    '][tag_popular]');

                $(`#tag-${rand} .tagnameen`).attr('name', '{{ $input }}[' + rand +
                    '][tag_name_en]');
                $(`#tag-${rand} .tagauto`).attr('name', '{{ $input }}[' + rand +
                    '][tag_auto_translate]');
                $(`#tag-${rand} .tagauto`).attr('onchange', 'showTagEn("' + rand + '")');
                // set checked
                $(`#tag-${rand} .tagauto`).prop('checked', true);
                // hide tag name en
                showTagEn(rand);
            });

            $(document).on('click', '.btn-remove', function(e) {
                $(this).closest('.row').remove();
            });
        });

        function showTagEn(rand) {
            const input = $(`[name="{{ $input }}[${rand}][tag_auto_translate]"]`);
            if (input.prop('checked')) {
                $(`[name="{{ $input }}[${rand}][tag_name_en]"]`).closest('.mb-3').hide();
            } else {
                $(`[name="{{ $input }}[${rand}][tag_name_en]"]`).closest('.mb-3').show();
            }
        }
    </script>
@endpush
