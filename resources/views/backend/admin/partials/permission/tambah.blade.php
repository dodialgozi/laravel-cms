@extends('backend.layouts.panel')

@section('title', "Tambah {$title}")

@section('panel_content')
<form id="formcreate" action="{{ url($mainURL) }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-3 mb-3">
            <x-form-select2 label="Group" name="{{ $input }}[group_id]" :url="url('permission-group/select')" key="id" value="nama" :allowClear=true id="group">
            @if(!empty($group = $more['group'] ?? null))
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endif
            </x-form-select2>
        </div>

        <div class="col-md-2 mb-3">
            <x-form-input label="AutoFill Permission" placeholder="" id="autoname" />
        </div>

        <div class="col-md-7 mb-3">
            <x-form-checkbox label="Permissions" id="permissions">
                @foreach ($more['defaultPermission'] as $key => $value)
                <x-input-checkbox label="{{ $value }}" value="{{ $key }}" id="cbx-{{ $key }}" />
                @endforeach
            </x-form-checkbox>
        </div>

        <div id="permission" class="col-md-12"></div>

        <div class="col-md-12">
            <button type="button" class="btn btn-outline-primary waves-effect waves-light btn-sm mt-2 btn-add"><i class="bx bx-plus"></i> Tambah</button>
        </div>
    </div>    

    <x-form-submit />
</form>

<script id="template" type="text/template">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-end mt-2">
            <button type="button" class="btn-clear btn-remove text-danger"><i class="bx bx-x bx-sm"></i></button>
        </div>
        <div class="col-md-6 mb-3">
            <x-form-input class="permname" label="Permission" name="{{ $input }}[name][]" required />
        </div>
        <div class="col-md-6 mb-3">
            <x-form-input class="permdesc" label="Description" name="{{ $input }}[description][]" />
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
#permission>.row {
    border: 1px solid #eaeaea;
    border-radius: 5px;
}
#permission>.row~.row {
    margin-top: .75rem;
}
</style>
@endpush

@push('script')
<script>
var template;
$(function() {
    template = $('#template').html();

    $('#group').change(function() {
        const group = $(this).val();
        if(!group) return;

        $.ajax({
            url: '{{ url("{$mainURL}/permission") }}',
            method: 'POST',
            data: {
                group: group,
            }
        }).done(data => {
            data = data.data;
            if(data && data.length > 0) {
                const autoname = data[0].name.split('.')[0];
                $('#autoname').val(autoname).trigger('change');
            } else {
                $('#autoname').val('').trigger('change');
            }
        });
    });

    $('.btn-add').click(function() {
        const autoname = $('#autoname').val();
        const rand = randomGen(12);
        $('#permission').append(`<div id="perm-${rand}">${template}</div>`);
        $(`#perm-${rand} .permname`).val(`${autoname != '' ? `${autoname}.` : ''}`);
        $(`#perm-${rand} .permdesc`).prop('required', true);
    });

    $('#permissions input[type=checkbox]').prop('disabled', true);

    $('#autoname').on('keyup change', function() {
        if($(this).val() != '') {
            $('#permissions input[type=checkbox]').prop('disabled', false);
        } else {
            $('#permissions input[type=checkbox]').prop('disabled', true);
        }
    });

    $('#permissions input[type=checkbox]').change(function() {
        const permission = $(this).val();
        if(this.checked) {
            const templatePerm = `<div id="perm-${permission}">${template}</div>`;
            $('#permission').prepend(templatePerm);
            $permission = $(`#perm-${permission}`);
            const autoname = $('#autoname').val();
            $permission.find('.permname').val(`${autoname != '' ? `${autoname}.` : ''}${permission}`)
                .prop('readonly', true);
            $permission.find('.permdesc').prop('readonly', true);
            $permission.find('.btn-remove').remove();
        } else {
            $(`#perm-${permission}`).remove();
        }
    });

    $(document).on('click', '.btn-remove', function(e) {
        $(this).closest('.row').remove();
    });
});
</script>
@endpush