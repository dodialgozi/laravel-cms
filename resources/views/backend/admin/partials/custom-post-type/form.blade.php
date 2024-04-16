@php
$existingData = $result->post_type_field ?? json_encode([]);
$fields = $more['fields'] ?? [];
$rand = randomGen(12);
@endphp
<div class="row">
    <div class="col-md-12 mb-3">
        <x-form-input label="Nama Tipe" name="{{ $input }}[post_type_name]" value="{{ $result->post_type_name ?? '' }}" id="post_type_name" required />
    </div>
</div>

<div class="col-md-12 mb-3">
    <x-form-switch label="Status Aktif" name="{{ $input }}[post_type_status]"
        :checked="!empty($result->post_type_status ?? 1)" labelOn="Ya" labelOff="Tdk" />
</div>

<div class="row">
    @if ($formType == 'create')
    <div class="col-md-3 mb-3">
        <x-form-input class="field_name" label="Nama Field" name="{{ $input }}[field][{{$rand}}][field_name]" required />
    </div>
    <div class="col-md-3 mb-3">
        <x-form-input class="field_description" label="Deskripsi" name="{{ $input }}[field][{{$rand}}][field_description]" required />
    </div>
    <div class="col-md-3 mb-3">
        <x-form-select2-option class="field_type" label="Tipe Field" name="{{ $input }}[field][{{$rand}}][field_type]" :options=$fields :allowClear=true :disableSearch=true />
    </div>
    <div class="col-md-3 mb-3">
        <x-form-input class="field_column" label="Kolom/Data" name="{{ $input }}[field][{{$rand}}][field_column]" />
    </div>
    @endif

    <div id="post" class="col-md-12">

    </div>

    <div class="col-md-12">
        <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm mt-2 btn-add"><i class="bx bx-plus"></i> Tambah</button>
    </div>
</div>

@push('script')
<script id="template" type="text/template">
    <div class="row">
        <div class="col-md-3 mb-3">
            <x-form-input class="field_name" label="Nama Field" name="" required />
        </div>
        <div class="col-md-3 mb-3">
            <x-form-input class="field_description" label="Deskripsi" name="" required />
        </div>
        <div class="col-md-3 mb-3">
            <x-form-select2-option class="field_type" label="Tipe Field" name="" :options=$fields :allowClear=true :disableSearch=true />
        </div>
        <div class="col-md-2 mb-3">
            <x-form-input class="field_column" label="Kolom/Data" name="" />
        </div>
        <div class="col-md-1 mt-2">
            <button type="button" class="btn btn-outline-danger waves-effect waves-light btn-sm mt-3 btn-remove"><i class="bx bx-x bx-sm"></i></button>
        </div>
    </div>
</script>
<script>
    var template;
    $(function() {
        template = $('#template').html();

        var existingData = {!! $existingData !!};
        existingData.forEach(function(data) {
            addExistingData(data);
        });

        $('.btn-add').click(function() {
            const rand = randomGen(12);
            $('#post').append(`<div id="post-${rand}">${template}</div>`);
            $(`#post-${rand} .field_name`).prop('required', true);
            $(`#post-${rand} .field_name`).attr('name', '{{ $input }}[field][' + rand + '][field_name]');
            $(`#post-${rand} .field_description`).attr('name', '{{ $input }}[field][' + rand + '][field_description]');
            $(`#post-${rand} .field_type`).attr('name', '{{ $input }}[field][' + rand + '][field_type]');
            $(`#post-${rand} .field_column`).attr('name', '{{ $input }}[field][' + rand + '][field_column]');
        });

        $(document).on('click', '.btn-remove', function(e) {
            $(this).closest('.row').remove();
        });

        function addExistingData(data) {
            var rand = randomGen(12);
            var newTemplate = document.createElement('div');
            newTemplate.innerHTML = `<div id="post-${rand}">${template}</div>`;

            var nameInput = newTemplate.querySelector('.field_name');
            nameInput.value = data.field_name;
            nameInput.name = '{{ $input }}[field][' + rand + '][field_name]';

            var descriptionInput = newTemplate.querySelector('.field_description');
            descriptionInput.value = data.field_description;
            descriptionInput.name = '{{ $input }}[field][' + rand + '][field_description]';

            var fieldInput = newTemplate.querySelector('.field_type');
            fieldInput.value = data.field_type;
            fieldInput.name = '{{ $input }}[field][' + rand + '][field_type]';

            var columnInput = newTemplate.querySelector('.field_column');
            columnInput.value = data.field_column;
            columnInput.name = '{{ $input }}[field][' + rand + '][field_column]';

            document.getElementById('post').appendChild(newTemplate.firstChild);
        }
    });
</script>
@endpush

@push('style')
<style>
    .btn-clear {
        border: 0;
        background: none;
        padding: 0;
    }

    #post>.row {
        border: 1px solid #eaeaea;
        border-radius: 5px;
    }

    #post>.row~.row {
        margin-top: .75rem;
    }
</style>
@endpush