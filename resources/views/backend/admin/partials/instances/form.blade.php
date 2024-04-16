<div class="row">
    <div class="col-md-12 mb-3">
        <x-form-input label="Nama Instansi" name="{{ $input }}[instance_name]"
            value="{{ $result->instance_name ?? '' }}" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-file label="Logo Instansi" name="{{ $inputFile }}[instance_thumbnail]" :value="$result->instance_thumbnail ?? null" :image=true
            :download=false />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="Domain Instansi" name="{{ $input }}[instance_domain]"
            value="{{ $result->instance_domain ?? '' }}" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-switch label="Status Aktif" name="{{ $input }}[instance_active]" :checked="!empty($result->instance_active ?? 1)" labelOn="Ya"
            labelOff="Tdk" />
    </div>
</div>
