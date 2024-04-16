<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3" id="page_title_id">
                        <x-form-input label="Title (ID)" name="{{ $input }}[page_title_id]"
                            value="{{ $result->page_title_id ?? '' }}" required />
                    </div>
                    <div class="col-md-12 mb-3 d-none" id="page_title_en">
                        <x-form-input label="Title (EN)" name="{{ $input }}[page_title_en]"
                            value="{{ $result->page_title_en ?? '' }}" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="position-relative w-100">
                            <x-form-rich-text label="Isi Konten (ID)"
                                name="{{ $input }}[page_content_id]">{!! $result->page_content_id ?? '' !!}</x-form-textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 d-none">
                        <div class="position-relative w-100">
                            <x-form-rich-text label="Isi Konten (EN)"
                                name="{{ $input }}[page_content_en]">{!! $result->page_content_en ?? '' !!}</x-form-textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header" style="background-color: #eef1fd; color: #4d63cf">
                <strong>Translate</strong>
            </div>
            <div class="card-body">
                <div class="col-md-12 mb-3">
                    <x-form-switch label="Auto Translate" name="{{ $input }}[page_auto_translate]"
                        :checked="true" id="page_auto_translate" labelOn="Ya" labelOff="Tdk" />
                    <small class="text-danger">*Auto Translate akan menggantikan data yang sudah ada</small>
                    <small class="text-danger">*Pastikan data sudah disimpan sebelum menggunakan fitur ini</small>
                </div>

                <div class="col-md-12 mb-3 d-none" id="page_language">
                    <x-form-select2-option label="Bahasa" name="{{ $input }}[page_language]" :options="['id' => 'Indonesia', 'en' => 'English']"
                        :value="'id'" :disableSearch=true required />
                </div>
                <div class="col-md-12 mb-3">
                    <x-form-select2-option label="Status" name="{{ $input }}[page_status]" :options="['publish' => 'Publish', 'draft' => 'Draft']"
                        :value="'publish'" :disableSearch=true required />
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 mb-3">
                    <x-form-input label="Meta Title" name="{{ $input }}[meta_title]"
                        value="{{ $result->meta_title ?? '' }}" id="meta_title" required />
                </div>
                <div class="col-md-12 mb-3">
                    <x-form-textarea label="Meta Desctiption"
                        name="{{ $input }}[meta_description]">{!! $result->meta_description ?? '' !!}</x-form-textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <x-form-input label="Meta Keyword" name="{{ $input }}[meta_keyword]"
                        value="{{ $result->meta_keyword ?? '' }}" id="meta_keyword" required />
                </div>
                <div class="col-md-12 mb-3">
                    <x-form-file label="Thumbnail" name="{{ $inputFile }}[page_thumbnail]" :value="$result->page_thumbnail ?? null"
                        :image=true :download=false />
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        // auto translate show select language
        $('#page_auto_translate').on('change', function() {
            const input = $(this).find('input');
            if (input.prop('checked')) {
                // check if current language is english, then set to indonesia
                if ($('#page_language').find('select').val() === 'en') {
                    $('#page_language').find('select').val('id').trigger('change');
                }
                $('#page_language').addClass('d-none');
            } else {
                $('#page_language').removeClass('d-none');
            }
        });

        // if language is english, then show title and content english
        $('#page_language').on('change', function() {
            const input = $(this).find('select');
            if (input.val() == 'en') {
                $('#page_title_en').removeClass('d-none').find('input').attr('required', true);
                $('#page_content_en').removeClass('d-none');
                $('#page_title_id').addClass('d-none').find('input').removeAttr('required');
                $('#page_content_id').addClass('d-none');
            } else {
                $('#page_title_en').addClass('d-none').find('input').removeAttr('required');
                $('#page_content_en').addClass('d-none');
                $('#page_title_id').removeClass('d-none').find('input').attr('required', true);
                $('#page_content_id').removeClass('d-none');
            }
        });
    </script>
@endpush
