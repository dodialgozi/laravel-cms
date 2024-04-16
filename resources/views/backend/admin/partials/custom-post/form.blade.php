@php
    $status = $more['status'] ?? [];
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

                <!-- <div class="col-12 mt-5">
                    <div class="mb-3">
                        <h5 class="mb-0">Post Data</h5>
                    </div>
                    <div>
                        @php
                            $postTypeFields = json_decode($more['postType']->post_type_field ?? '[]', true);
                        @endphp

                        @foreach ($postTypeFields as $item)
{{ $item['field_name'] }}
@endforeach
                    </div>
                </div> -->

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
                    <x-form-select2-option label="Status" name="{{ $input }}[post_status]" :options=$status
                        :disableSearch=true value="{{ $result->post_status ?? 'draft' }}" id="post_status" required />
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
                        <strong>Kategori</strong>
                    </button>
                </h2>
                <div id="collapseKategoriTag" class="accordion-collapse collapse" data-bs-parent="#accordionPost">
                    <div class="accordion-body">
                        <div class="col-md-12 mb-3">
                            <x-form-select2 label="Kategori" name="categories[]" :url="url('custom-post-category/select?type=' . $type)" key="id"
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
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
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
    </script>
@endpush
