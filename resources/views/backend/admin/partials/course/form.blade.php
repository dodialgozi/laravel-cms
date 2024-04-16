<div class="row">
    <div class="col-md-4 mb-3">
        <x-form-input class="coursenameid" label="Tag (ID)" name="{{ $input }}[course_name_id]"
            value="{{ $result->course_name_id ?? '' }}" required />
    </div>
    <div class="col-md-4 mb-3">
        <x-form-input class="coursenameen" label="Tag (EN)" name="{{ $input }}[course_name_en]" id="course_name_en"
            value="{{ $result->course_name_en ?? '' }}" />
    </div>
    <div class="col-md-4 mb-3">
        <x-form-switch class="courseauto" label="Auto Translate" name="{{ $input }}[course_auto_translate]"
            :checked="true" id="course_auto_translate" labelOn="Ya" labelOff="Tdk" />
    </div>
</div>
@push('script')
    <script>
        // hide course name en
        $('#course_name_en').closest('.mb-3').hide();
        // show course name en when auto translate is off
        $('#course_auto_translate').change(function() {
            // this element is not input, so we need to find the child input
            const input = $(this).find('input');
            if (input.prop('checked')) {
                $('#course_name_en').closest('.mb-3').hide();
            } else {
                $('#course_name_en').closest('.mb-3').show();
            }
        });
    </script>
@endpush
