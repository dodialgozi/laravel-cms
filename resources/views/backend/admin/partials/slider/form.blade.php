<div class="row">
    <div class="col-md-12 mb-3">
        <x-form-input label="Title" name="{{ $input }}[slider_title]" value="{{ $result->slider_title ?? '' }}"
            required />
    </div>
    <div class="col-md-12 mb-3">
        <x-form-textarea label="Deskripsi"
            name="{{ $input }}[slider_description]">{!! $result->slider_description ?? '' !!}</x-form-textarea>
    </div>
    <div class="col-md-12 mb-3">
        <x-form-file label="Image" name="{{ $inputFile }}[slider_image]" :value="$result->slider_image ?? null" :image=true
            :download=false required />
    </div>
</div>
</div>
