<div class="row">
    <div class="col-md-12 mb-3">
        <x-form-input label="Kontak" name="{{ $input }}[key]" value="{{ $result->key ?? '' }}" required />
    </div>
    <div class="col-md-12 mb-3">
        <x-form-input label="Value" name="{{ $input }}[value]" value="{{ $result->value ?? '' }}" required />
    </div>
    <div class="col-md-12 mb-3">
        <x-form-file label="Icon" name="{{ $inputFile }}[icon]" :value="$result->icon ?? null" :image=true :download=false />
    </div>
</div>
