@php
    $status = $more['status'] ?? [];
    $post_type = $more['post_type'] ?? [];
    $meta_type = $more['meta_type'] ?? [];

    $default_meta_code = [];
    if (!empty($more['post_meta_default'] ?? null)) {
        foreach ($more['post_meta_default'] as $item) {
            $default_meta_code[] = $item->setting_meta_code;
        }
    }
@endphp

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 mb-3" id="post_title_id">
                    <x-form-input label="Title (ID)" name="{{ $input }}[post_title_id]"
                        value="{{ $result->post_title_id ?? '' }}" required />
                </div>
                <div class="col-md-12 mb-3 d-none" id="post_title_en">
                    <x-form-input label="Title (EN)" name="{{ $input }}[post_title_en]"
                        value="{{ $result->post_title_en ?? '' }}" />
                </div>
                <div class="col-md-12 mb-3">
                    <x-form-file label="Gambar Utama" name="{{ $inputFile }}[first_image]" :value="$result->first_image ?? null"
                        :image=true :download=false />
                </div>
                <div class="col-md-12 mb-3" id="post_content_id">
                    <div class="position-relative w-100">
                        <x-form-rich-text label="Isi Konten (ID)"
                            name="{{ $input }}[post_content_id]">{!! $result->post_content_id ?? '' !!}</x-form-textarea>
                    </div>
                </div>
                <div class="col-md-12 mb-3 d-none" id="post_content_en">
                    <div class="position-relative w-100">
                        <x-form-rich-text label="Isi Konten (EN)"
                            name="{{ $input }}[post_content_en]">{!! $result->post_content_en ?? '' !!}</x-form-textarea>
                    </div>
                </div>

                <div class="col-12 mt-5">
                    <div class="mb-3">
                        <h5 class="mb-0">Post Meta</h5>
                    </div>
                    <div id="customField">
                        @if (!empty(($post_meta = $more['post_meta'] ?? $more['post_meta_default'])))
                            @foreach ($post_meta as $item)
                                @php
                                    $default_meta = false;
                                    $meta_code = $item->post_meta_code ?? $item->setting_meta_code;
                                    $meta_type_value = $item->post_meta_type ?? $item->setting_meta_type;
                                    $rand = randomGen(12);
                                    if ($formType == 'update') {
                                        if (in_array($meta_code, $default_meta_code)) {
                                            $default_meta = true;
                                        }
                                    }
                                @endphp
                                <div id="customField-{{ $rand }}">
                                    <div class="row mb-3">
                                        @if ($formType == 'update' && !$default_meta)
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <button type="button" class="btn-clear btn-remove text-danger"><i
                                                        class="bx bx-x bx-sm"></i></button>
                                            </div>
                                        @endif
                                        <div class="col-md-4 mb-3">
                                            <x-form-input label="Key" class="key"
                                                name="{{ $default_meta ? 'default_meta' : 'post_meta' }}[{{ $rand }}][post_meta_code]"
                                                value="{{ $meta_code ?? '' }}" id="post_meta_code" readonly />
                                        </div>
                                        <div class="col-md-4 mb-3 {{ $default_meta ? 'd-none' : '' }}">
                                            <x-form-select2-option label="Tipe" class="type"
                                                name="{{ $default_meta ? 'default_meta' : 'post_meta' }}[{{ $rand }}][post_meta_type]"
                                                :options=$meta_type :disableSearch=true
                                                value="{{ $meta_type_value ?? 'field' }}"
                                                id="post_meta_type_{{ $rand }}" />
                                        </div>
                                        <div id="valueField_{{ $rand }}" class="col-md-4 mb-3">
                                            @if ($meta_type_value == 'field')
                                                <x-form-input label="Value" class="value"
                                                    name="{{ $default_meta ? 'default_meta' : 'post_meta' }}[{{ $rand }}][post_meta_value]"
                                                    value="{{ $item->post_meta_value ?? '' }}" id="post_meta_value" />
                                            @elseif ($meta_type_value == 'boolean')
                                                <x-form-switch label="Value" class="value"
                                                    name="{{ $default_meta ? 'default_meta' : 'post_meta' }}[{{ $rand }}][post_meta_value]"
                                                    :checked="!empty($item->post_meta_value ?? 0)" :square=true labelOn="Ya" labelOff="Tdk" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addCustomField">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border border-2">
            <div class="card-header" style="background-color: #eef1fd; color: #4d63cf">
                <strong>Translate</strong>
            </div>
            <div class="card-body">
                <div class="col-md-12 mb-3">
                    <x-form-switch label="Auto Translate" name="{{ $input }}[post_auto_translate]"
                        :checked="true" id="post_auto_translate" labelOn="Ya" labelOff="Tdk" />
                    <small class="text-danger">*Auto Translate akan menggantikan data yang sudah ada</small>
                    <small class="text-danger">*Pastikan data sudah disimpan sebelum menggunakan fitur ini</small>
                </div>

                <div class="col-md-12 mb-3 d-none" id="post_language">
                    <x-form-select2-option label="Bahasa" name="{{ $input }}[post_language]" :options="['id' => 'Indonesia', 'en' => 'English']"
                        :value="'id'" :disableSearch=true required />
                </div>
            </div>
        </div>
        <div class="card border border-2">
            <div class="card-header" style="background-color: #eef1fd; color: #4d63cf">
                <strong>Publish</strong>
            </div>
            <div class="card-body">
                <div class="col-12 mb-3">
                    <x-form-select2-option label="Tipe Post" name="{{ $input }}[post_type]" :options=$post_type
                        :disableSearch=true value="{{ $result->post_type ?? 'post' }}" id="post_type" required />
                </div>

                <div class="col-12 mb-3">
                    <x-form-switch label="Jadikan Slider" name="{{ $input }}[post_slider]" :checked="!empty($result->post_slider ?? 0)"
                        :square=true labelOn="Ya" labelOff="Tdk" />
                </div>

                <div class="col-12 mb-3">
                    <x-form-switch label="Jadikan Hot Topic" name="{{ $input }}[post_hottopic]"
                        :checked="!empty($result->post_hottopic ?? 0)" :square=true labelOn="Ya" labelOff="Tdk" />
                </div>

                <div class="col-12 mb-3">
                    <x-form-switch label="Jadikan Trending Topic" name="{{ $input }}[post_trending_topic]"
                        :checked="!empty($result->post_trending_topic ?? 0)" :square=true labelOn="Ya" labelOff="Tdk" />
                </div>

                <div class="col-12 mb-3">
                    <x-form-select2-option label="Status" name="{{ $input }}[post_status]" :options=$status
                        :disableSearch=true value="{{ $result->post_status ?? 'draft' }}" id="post_status"
                        required />
                </div>

                <div id="schedule" class="d-none">
                    @if (userCan("{$permission}.tanggal"))
                        <div class="col-12 mb-3">
                            <x-form-datepicker name="publish_date" value="{{ $result->publish_date ?? '' }}"
                                id="publish_date" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-form-timepicker name="publish_time" value="{{ $result->publish_time ?? '' }}"
                                id="publish_time" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="accordion" id="accordionPost">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseMeta" aria-expanded="true" aria-controls="collapseMeta">
                        <strong>Pengaturan Meta</strong>
                    </button>
                </h2>
                <div id="collapseMeta" class="accordion-collapse collapse show" data-bs-parent="#accordionPost">
                    <div class="accordion-body">
                        <div class="col-md-12 mb-3">
                            <x-form-input label="Meta Title" name="{{ $input }}[meta_title]"
                                value="{{ $result->meta_title ?? '' }}" id="meta_title" required />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-textarea label="Meta Description"
                                name="{{ $input }}[meta_description]">{!! $result->meta_description ?? '' !!}</x-form-textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-input label="Meta Keyword" name="{{ $input }}[meta_keyword]"
                                value="{{ $result->meta_keyword ?? '' }}" id="meta_keyword" required />
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThumbnail" aria-expanded="false" aria-controls="collapseThumbnail">
                        <strong>Thumbnail</strong>
                    </button>
                </h2>
                <div id="collapseThumbnail" class="accordion-collapse collapse" data-bs-parent="#accordionPost">
                    <div class="accordion-body">
                        <div class="col-md-12 mb-3">
                            <x-form-file label="Medium Thumbnail <small>(Ukuran 300x180 pixel)</small>"
                                name="{{ $inputFile }}[medium_thumbnail]" :value="$result->medium_thumbnail ?? null" :image=true
                                :download=false />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-file label="Thumbnail <small>(Persegi, maksimal 150x150 pixel)</small>"
                                name="{{ $inputFile }}[thumbnail]" :value="$result->thumbnail ?? null" :image=true :download=false />
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseKategoriTag" aria-expanded="false"
                        aria-controls="collapseKategoriTag">
                        <strong>Kategori & Tag</strong>
                    </button>
                </h2>
                <div id="collapseKategoriTag" class="accordion-collapse collapse" data-bs-parent="#accordionPost">
                    <div class="accordion-body">
                        <div class="col-md-12 mb-3">
                            <x-form-select2 label="Kategori" name="categories[]" :url="url('kategori-jurnalis/select')" key="id"
                                value="nama" :allowClear=true id="categories" multiple>
                                @if (!empty(($categories = $more['post_categories'] ?? null)))
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->category_id }}" selected>
                                            {{ $item->category->category_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-form-select2>
                        </div>

                        <div class="col-md-12 mb-3">
                            <x-form-select2 label="Tag" name="tags[]" :url="url('post-tag/select')" key="id"
                                value="name" :allowClear=true id="tags" :tags=true multiple>
                                @if (!empty(($tags = $more['post_tags'] ?? null)))
                                    @foreach ($tags as $item)
                                        <option value="{{ $item->tag_id }}" selected>{{ $item->tag->tag_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-form-select2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseUrl" aria-expanded="false" aria-controls="collapseUrl">
                        <strong>Video URL</strong>
                    </button>
                </h2>
                <div id="collapseUrl" class="accordion-collapse collapse" data-bs-parent="#accordionPost">
                    <div class="accordion-body">
                        <div class="col-md-12 mb-3">
                            <x-form-input
                                label="Video URL <small class='text-secondary'>(harus diawali http:// atau https://)</small>"
                                name="{{ $input }}[post_video_url]"
                                value="{{ $result->post_video_url ?? '' }}" id="post_video_url"
                                placeholder="URL Video Youtube" />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script id="template" type="text/template">
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" class="btn-clear btn-remove text-danger"><i class="bx bx-x bx-sm"></i></button>
        </div>
        <div class="col-md-4 mb-3">
            <x-form-input label="Key" class="key" name="" value="" id="post_meta_code" required />
        </div>
        <div class="col-md-4 mb-3">
            <x-form-select2-option label="Tipe" class="type" name="" :options=$meta_type :disableSearch=true value="field" id="post_meta_type_" />
        </div>
        <div id="valueField_" class="col-md-4 mb-3">
            <x-form-input label="Value" class="value" name="" value="" id="post_meta_value" required />
        </div>
    </div>
</script>

@push('style')
    <style>
        .btn-clear {
            border: 0;
            background: none;
            padding: 0;
        }
    </style>
@endpush

@push('script')
    <script>
        // auto translate show select language
        $('#post_auto_translate').on('change', function() {
            const input = $(this).find('input');
            if (input.prop('checked')) {
                // check if current language is english, then set to indonesia
                if ($('#post_language').find('select').val() === 'en') {
                    $('#post_language').find('select').val('id').trigger('change');
                }
                $('#post_language').addClass('d-none');
            } else {
                $('#post_language').removeClass('d-none');
            }
        });

        // if language is english, then show title and content english
        $('#post_language').on('change', function() {
            const input = $(this).find('select');
            if (input.val() == 'en') {
                $('#post_title_en').removeClass('d-none').find('input').attr('required', true);
                $('#post_content_en').removeClass('d-none');
                $('#post_title_id').addClass('d-none').find('input').removeAttr('required');
                $('#post_content_id').addClass('d-none');
            } else {
                $('#post_title_en').addClass('d-none').find('input').removeAttr('required');
                $('#post_content_en').addClass('d-none');
                $('#post_title_id').removeClass('d-none').find('input').attr('required', true);
                $('#post_content_id').removeClass('d-none');
            }
        });

        const status = $('#post_status').val();
        if (status != 'draft') {
            const post_date = "{{ $more['post_date'] ?? date('Y-m-d') }}";
            const post_time = "{{ $more['post_time'] ?? date('H:i') }}";

            $('#schedule').removeClass('d-none');
            $('#publish_date').attr('required', true);
            $('#publish_date').val(post_date);
            $('#publish_time').attr('required', true);
            $('#publish_time').val(post_time);
        } else {
            $('#schedule').addClass('d-none');
            $('#publish_date').attr('required', false);
            $('#publish_date').val('');
            $('#publish_time').attr('required', false);
            $('#publish_time').val('');
        }

        $('#post_status').on('change', function() {
            const value = $(this).val();
            if (value != 'draft') {
                $('#schedule').removeClass('d-none');
                $('#publish_date').attr('required', true);
                $('#publish_date').val(moment().format('YYYY-MM-DD'));
                $('#publish_time').attr('required', true);
                $('#publish_time').val(moment().format('HH:mm'));
            } else {
                $('#schedule').addClass('d-none');
                $('#publish_date').attr('required', false);
                $('#publish_date').val('');
                $('#publish_time').attr('required', false);
                $('#publish_time').val('');
            }
        });

        // tag yang diinput tidak boleh hanya berupa angka
        $('#tags').on('select2:select', function(e) {
            const data = e.params.data;
            if (data.id.match(/^\d+$/)) {
                $(this).find(`[value="${data.id}"]`).remove();
                swal.fire({
                    title: 'Oops!',
                    text: 'Tag tidak boleh hanya berupa angka!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>

    <script>
        var template;
        $(function() {
            template = $('#template').html();

            $('#addCustomField').click(function() {
                const rand = randomGen(12);
                $('#customField').append(`<div id="customField-${rand}">${template}</div>`);

                $(`#customField-${rand} .key`).attr('name', 'post_meta[' + rand + '][post_meta_code]');
                $(`#customField-${rand} .type`).attr('name', 'post_meta[' + rand + '][post_meta_type]');
                $(`#customField-${rand} .value`).attr('name', 'post_meta[' + rand + '][post_meta_value]');

                $(`#customField-${rand} [id^="post_meta_type_"]`).attr('id', 'post_meta_type_' + rand);
                $(`#customField-${rand} #valueField_`).attr('id', 'valueField_' + rand);

                // refresh select2
                $(`#customField-${rand} [id^="post_meta_type_"]`).select2({
                    placeholder: 'Pilih Tipe',
                    minimumResultsForSearch: -1
                });
            });

            $(document).on('click', '.btn-remove', function(e) {
                $(this).closest('.row').remove();
            });
        });

        // change meta_value depend on meta_type
        $(document).on('change', '[id^="post_meta_type_"]', function(e) {
            const rand = $(this).attr('id').split('_')[3];
            const value = $(this).val();
            const parent = $(this).closest('.row');
            const input = $(`#customField-${rand} .value`);

            $('#valueField_' + rand).html('');

            if (value == 'field') {
                $('#valueField_' + rand).append(
                    `<x-form-input label="Value" class="value" name="post_meta[${rand}][post_meta_value]" value="" id="post_meta_value" required />`
                );
            } else if (value == 'boolean') {
                $('#valueField_' + rand).append(
                    `<x-form-switch label="Value" class="value" name="post_meta[${rand}][post_meta_value]" :square=true labelOn="Ya" labelOff="Tdk" />`
                );
            }
        });
    </script>
@endpush
