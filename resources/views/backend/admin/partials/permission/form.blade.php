<div class="row">
    <div class="col-md-6 mb-3">
        <x-form-select2 label="Group" name="{{ $input }}[group_id]" :url="url('permission-group/select')" key="id" value="nama" :allowClear=true id="group">
        @if(!empty($group = $more['group'] ?? null))
            <option value="{{ $group->id }}">{{ $group->name }}</option>
        @endif
        </x-form-select2>
    </div>
    <div class="col-md-12"></div>
    <div class="col-md-6 mb-3">
        <x-form-input label="Permission" name="{{ $input }}[name]" value="{{ $result->name ?? '' }}" required />
    </div>
    <div class="col-md-6 mb-3">
        <x-form-input label="Description" name="{{ $input }}[description]" value="{{ $result->description ?? '' }}" />
    </div>
</div>
