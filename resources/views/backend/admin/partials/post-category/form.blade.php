<div class="row">
    <div class="col-md-6 mb-3">
        <x-form-input label="Nama Kategori (ID)" name="{{ $input }}[category_name_id]"
            value="{{ $result->category_name_id ?? '' }}" required />
    </div>

    <div class="col-md-6 mb-3">
        <x-form-input label="Category Name (EN)" name="{{ $input }}[category_name_en]" id="category_name_en"
            value="{{ $result->category_name_en ?? '' }}" />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-file label="Thumbnail" name="{{ $inputFile }}[category_thumbnail]" :value="$result->category_thumbnail ?? null" :image=true
            :download=false />
    </div>

    <div class="col-md-12 mb-3">
        <x-form-select2 label="Kategori Induk" name="{{ $input }}[parent_id]" :url="url('post-kategori/select')" key="id"
            value="nama" :allowClear=true id="induk_kategori">
            @if (!empty(($parent_category = $more['parent_category'] ?? null)))
                <option value="{{ $parent_category->id }}">{{ $parent_category->name }}</option>
            @endif
        </x-form-select2>
    </div>

    <div class="col-md-6 mb-3">
        <x-form-switch label="Status Aktif" name="{{ $input }}[category_active]" :checked="!empty($result->category_active ?? 1)" labelOn="Ya"
            labelOff="Tdk" />
    </div>

    <div class="col-md-6 mb-3">
        <x-form-switch label="Auto Translate" name="{{ $input }}[category_auto_translate]" :checked="true"
            id="category_auto_translate" labelOn="Ya" labelOff="Tdk" />
    </div>
</div>
@push('script')
    <script>
        // hide category name en
        $('#category_name_en').closest('.mb-3').hide();
        // show category name en when auto translate is off
        $('#category_auto_translate').change(function() {
            // this element is not input, so we need to find the child input
            const input = $(this).find('input');
            if (input.prop('checked')) {
                $('#category_name_en').closest('.mb-3').hide();
            } else {
                $('#category_name_en').closest('.mb-3').show();
            }
        });
    </script>
@endpush
