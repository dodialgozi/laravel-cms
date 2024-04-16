<div class="row">
    <div class="col-md-6 mb-3">
        <x-form-input class="tagnameid" label="Tag (ID)" name="{{ $input }}[tag_name_id]"
            value="{{ $result->tag_name_id ?? '' }}" required />
    </div>
    <div class="col-md-6 mb-3">
        <x-form-input class="tagnameen" label="Tag (EN)" name="{{ $input }}[tag_name_en]" id="tag_name_en"
            value="{{ $result->tag_name_en ?? '' }}" />
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <x-form-switch class="tagpopular" label="Popular" name="{{ $input }}[tag_popular]" labelOn="Ya"
            labelOff="Tdk" :checked="!empty($result->tag_popular ?? 0)" />
    </div>
    <div class="col-md-6 mb-3">
        <x-form-switch class="tagauto" label="Auto Translate" name="{{ $input }}[tag_auto_translate]"
            :checked="true" id="tag_auto_translate" labelOn="Ya" labelOff="Tdk" />
    </div>
</div>
@push('script')
    <script>
        // hide tag name en
        $('#tag_name_en').closest('.mb-3').hide();
        // show tag name en when auto translate is off
        $('#tag_auto_translate').change(function() {
            // this element is not input, so we need to find the child input
            const input = $(this).find('input');
            if (input.prop('checked')) {
                $('#tag_name_en').closest('.mb-3').hide();
            } else {
                $('#tag_name_en').closest('.mb-3').show();
            }
        });
    </script>
@endpush
