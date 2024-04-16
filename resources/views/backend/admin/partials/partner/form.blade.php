<div class="row">
    <div class="col-md-12 mb-3">
        <x-form-input label="Nama Instansi" name="{{ $input }}[partner_name]"
            value="{{ $result->partner_name ?? '' }}" required />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-file label="Logo Instansi" name="{{ $inputFile }}[partner_logo]" :value="$result->partner_logo ?? null" :image=true
            :download=false />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-input label="Domain Instansi" name="{{ $input }}[partner_url]"
            value="{{ $result->partner_url ?? '' }}" required />
    </div>
</div>
