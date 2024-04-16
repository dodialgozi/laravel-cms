<div class="row">
    <div class="col-xl-8">
        <div class="col-md-12 mb-3" id="gallery_title_id">
            <x-form-input label="Judul Galeri (ID)" name="{{ $input }}[gallery_title_id]"
                value="{{ $result->gallery_title_id ?? '' }}" required />
        </div>

        <div class="col-md-12 mb-3" id="gallery_title_en">
            <x-form-input label="Judul Galeri (EN)" name="{{ $input }}[gallery_title_en]"
                value="{{ $result->gallery_title_en ?? '' }}" />
        </div>

        <div class="col-md-12 mb-3" id="gallery_description_id">
            <x-form-rich-text label="Deskripsi Galeri (ID)" name="{{ $input }}[gallery_description_id]"
                value="{{ $result->gallery_description_id ?? '' }}" />
        </div>

        <div class="col-md-12 mb-3" id="gallery_description_en">
            <x-form-rich-text label="Deskripsi Galeri (EN)" name="{{ $input }}[gallery_description_en]"
                value="{{ $result->gallery_description_en ?? '' }}" />
        </div>

        <div class="col-md-12 mb-3">
            <x-form-file label="Foto Galeri" name="{{ $inputFile }}[gallery_image]" :value="$result->gallery_image ?? null" :image=true
                :download=false />
        </div>

        <!-- dropzone -->
        <div class="col-md-12 mb-3">

            <div class="dropzone">
                <div class="fallback">
                    <input name="file" type="file" multiple="multiple">
                </div>
                <div class="dz-message needsclick">
                    <div class="mb-3">
                        <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                    </div>

                    <h4>Drop files here or click to upload.</h4>
                </div>
            </div>
            <ul class="list-unstyled mb-0" id="dropzone-preview">
                <li class="mt-2" id="dropzone-preview-list">
                    <!-- This is used as the file preview template -->
                    <div class="border rounded">
                        <div class="d-flex p-2">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm bg-light rounded">
                                    <img data-dz-thumbnail class="img-fluid rounded d-block" {{-- src="../../../img.themesbrand.com/judia/new-document.png" --}}
                                        src="{{ asset('backend/assets/images/file.png') }}" alt="Dropzone-Image">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="pt-1">
                                    <h5 class="fs-md mb-1" data-dz-name>&nbsp;</h5>
                                    <p class="fs-sm text-muted mb-0" data-dz-size></p>
                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                </div>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

        </div>

        <!-- input hidden for uploaded images -->
        <input type="hidden" name="gallery_images" id="gallery_images" value="{{ $result->gallery_images ?? '' }}" />
        <input type="file" multiple="multiple" class="dz-hidden-input" tabindex="-1"
            style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
    </div>

    <div class="col-xl-4">
        <div class="col-md-12 mb-3">
            <x-form-switch label="Auto Translate" name="{{ $input }}[gallery_auto_translate]" :checked="true"
                id="gallery_auto_translate" labelOn="Ya" labelOff="Tdk" />
            <small class="text-danger">*Auto Translate akan menggantikan data yang sudah ada</small>
            <small class="text-danger">*Pastikan data sudah disimpan sebelum menggunakan fitur ini</small>
        </div>

        <div class="col-md-12 mb-3 d-none" id="gallery_language">
            <x-form-select2-option label="Bahasa" name="{{ $input }}[gallery_language]" :options="['id' => 'Indonesia', 'en' => 'English']"
                :value="'id'" :disableSearch=true required />
        </div>
    </div>
</div>

@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/dropzone/min/dropzone.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('backend/assets/libs/dropzone/min/dropzone.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // auto translate show select language
            $('#gallery_auto_translate').change(function() {
                const input = $(this).find('input');
                if (input.prop('checked')) {
                    // check if current language is english, then set to indonesia
                    if ($('#gallery_language').find('select').val() === 'en') {
                        $('#gallery_language').find('select').val('id').trigger('change');
                    }
                    $('#gallery_language').addClass('d-none');
                } else {
                    $('#gallery_language').removeClass('d-none');
                }
            });

            // hide gallery title en and description en
            $('#gallery_title_en').addClass('d-none');
            $('#gallery_description_en').addClass('d-none');

            // show gallery title en and description en when auto translate is off
            $('#gallery_language').change(function() {
                const input = $(this).find('select');
                if (input.val() == 'en') {
                    $('#gallery_title_en').removeClass('d-none');
                    $('#gallery_description_en').removeClass('d-none');
                    $('#gallery_title_id').addClass('d-none');
                    $('#gallery_description_id').addClass('d-none');
                } else {
                    $('#gallery_title_en').addClass('d-none');
                    $('#gallery_description_en').addClass('d-none');
                    $('#gallery_title_id').removeClass('d-none');
                    $('#gallery_description_id').removeClass('d-none');
                }
            });

        });
    </script>
    <script>
        var galleryImages = @json($result->images ?? []);
        // console.log(galleryImages);
        var uploadedDocumentMap = new Array();
        var MAX_FILE_SIZE = 2 // MB
        var MAX_FILE_COUNT = 100
        var previewTemplate, dropzone, dropzonePreviewNode = document.querySelector("#dropzone-preview-list");
        dropzonePreviewNode.id = "", dropzonePreviewNode && (previewTemplate = dropzonePreviewNode.parentNode.innerHTML,
            dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode), dropzone = new Dropzone(".dropzone", {
                url: "{{ url('upload-image') }}",
                maxFilesize: MAX_FILE_SIZE,
                maxFiles: MAX_FILE_COUNT,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                paramName: "image",
                acceptedFiles: "image/*",
                method: "post",
                previewTemplate: previewTemplate,
                previewsContainer: "#dropzone-preview",
                init: function() {
                    var myDropzone = this;
                    if (galleryImages.length > 0) {
                        galleryImages.forEach(function(image) {
                            var mockFile = {
                                name: image.image,
                                size: image.size
                            };
                            myDropzone.emit("addedfile", mockFile), myDropzone.emit("thumbnail",
                                    mockFile, image.image),
                                myDropzone.emit("complete", mockFile), myDropzone.files.push(mockFile);
                            var obj = {
                                size: image.size,
                                path: image.image
                            };
                            uploadedDocumentMap.push(obj), $("#gallery_images").val(JSON.stringify(
                                uploadedDocumentMap))
                        })
                    }
                    this.on("addedfile", function(file) {
                        this.files.length > MAX_FILE_COUNT && Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Maksimal file yang diupload adalah " + MAX_FILE_COUNT
                        })
                    }), this.on("error", function(file, response) {
                        file.size > 1024 * MAX_FILE_SIZE * 1024 && (Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Ukuran file terlalu besar, maksimal " + MAX_FILE_SIZE +
                                "MB"
                        }), this.removeFile(file)), response.message && Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message
                        })
                    }), this.on("success", function(file, response) {
                        var obj = {
                            size: file.size,
                            path: response.path
                        };
                        uploadedDocumentMap.push(obj), $("#gallery_images").val(JSON.stringify(
                                uploadedDocumentMap)),
                            file.previewElement.classList.add("dz-success");
                        var fileuploded = file.previewElement.querySelector("[data-dz-name]");
                        fileuploded.innerHTML = response.path
                    })
                },
                removedfile: function(file) {
                    var fileName = file.previewElement.querySelector("[data-dz-name]").innerHTML;
                    if (this.options.dictRemoveFile && file.previewElement) return Swal.fire({
                        title: "Hapus File",
                        text: "Apakah Anda yakin ingin menghapus file ini?",
                        icon: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, hapus",
                        cancelButtonText: "Batal"
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            var index = uploadedDocumentMap.findIndex(function(x) {
                                return x.path === fileName
                            });
                            if (index > -1) $.ajax({
                                type: "POST",
                                url: "{{ url('delete-image') }}",
                                data: {
                                    path: fileName
                                },
                                dataType: "json",
                                success: function(data) {
                                    Swal.fire({
                                            icon: "success",
                                            title: "Berhasil",
                                            text: data.message
                                        }), uploadedDocumentMap.splice(index, 1),
                                        console.log(uploadedDocumentMap),
                                        $("#gallery_images").val(JSON.stringify(
                                            uploadedDocumentMap))
                                }
                            });
                            var fileRef;
                            return (fileRef = file.previewElement) != null ? fileRef.parentNode
                                .removeChild(file.previewElement) :
                                void 0
                        }
                    })
                }
            }));
    </script>
@endpush
