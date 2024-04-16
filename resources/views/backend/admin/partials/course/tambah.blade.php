@extends('backend.layouts.panel')

@section('title', "Tambah {$title}")

@section('panel_content')
    <form id="formcreate" action="{{ url($mainURL) }}" method="POST">
        @csrf

        @php
            $rand = randomGen(12);
        @endphp

        <div class="row">
            <div class="col-md-4 mb-3">
                <x-form-input class="coursenameid" label="Mata Kuliah (ID)"
                    name="{{ $input }}[{{ $rand }}][course_name_id]" required />
            </div>
            <div class="col-md-4 mb-3">
                <x-form-input class="coursenameen" label="Mata Kuliah (EN)"
                    name="{{ $input }}[{{ $rand }}][course_name_en]" />
            </div>
            <div class="col-md-4 mb-3">
                <x-form-switch class="courseauto" label="Auto Translate"
                    name="{{ $input }}[{{ $rand }}][course_auto_translate]" :checked="true" labelOn="Ya"
                    labelOff="Tdk" onchange="showTagEn('{{ $rand }}')" />
            </div>
        </div>

        <div id="course" class="col-md-12"></div>

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
            <div class="col-md-4 mb-3">
                <x-form-input class="coursenameid" label="Mata Kuliah (ID)" name="" required />
            </div>
            <div class="col-md-4 mb-3">
                <x-form-input class="coursenameen" label="Mata Kuliah (EN)" name="" required />
            </div>
            <div class="col-md-4 mb-3">
                <x-form-switch class="courseauto" label="Auto Translate" name="" labelOn="Ya" labelOff="Tdk" />
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

        #course>.row {
            border: 1px solid #eaeaea;
            border-radius: 5px;
        }

        #course>.row~.row {
            margin-top: .75rem;
        }
    </style>
@endpush

@push('script')
    <script>
        // hide course name en
        $('.coursenameen').closest('.mb-3').hide();
        var template;
        $(function() {
            template = $('#template').html();

            $('.btn-add').click(function() {
                const rand = randomGen(12);
                $('#course').append(`<div id="course-${rand}">${template}</div>`);
                $(`#course-${rand} .coursenameid`).prop('required', true);

                $(`#course-${rand} .coursenameid`).attr('name', '{{ $input }}[' + rand +
                    '][course_name_id]');

                $(`#course-${rand} .coursenameen`).attr('name', '{{ $input }}[' + rand +
                    '][course_name_en]');
                $(`#course-${rand} .courseauto`).attr('name', '{{ $input }}[' + rand +
                    '][course_auto_translate]');
                $(`#course-${rand} .courseauto`).attr('onchange', 'showTagEn("' + rand + '")');
                // set checked
                $(`#course-${rand} .courseauto`).prop('checked', true);
                // hide course name en
                showTagEn(rand);
            });

            $(document).on('click', '.btn-remove', function(e) {
                $(this).closest('.row').remove();
            });
        });

        function showTagEn(rand) {
            const input = $(`[name="{{ $input }}[${rand}][course_auto_translate]"]`);
            if (input.prop('checked')) {
                $(`[name="{{ $input }}[${rand}][course_name_en]"]`).closest('.mb-3').hide();
            } else {
                $(`[name="{{ $input }}[${rand}][course_name_en]"]`).closest('.mb-3').show();
            }
        }
    </script>
@endpush
