@php
    $attributes = $attributes->merge([
        'summernote' => true,
        'name' => $name,
        'id' => $id ?? '',
]); @endphp

<x-form-textarea :label=$label :placeholder=$placeholder :horizontal=$horizontal :labelSize=$labelSize
    {{ $attributes }}>{{ $slot }}
</x-form-textarea>

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Style to fix summernote fullscreen and button in modal -->
    <style>
        .note-editor.note-frame.fullscreen {
            background-color: white;
        }

        .note-modal-footer {
            height: 50px;
        }

        .garuda-dropdown-item {
            cursor: pointer;
        }

        .garuda-dropdown-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <script>
        $(document).ready(function() {
            const createDropdownButton = function(context, buttonLabel, key, url) {
                var ui = $.summernote.ui;

                const button = ui.buttonGroup([
                    ui.button({
                        contents: buttonLabel + ' <i class="note-icon-caret"></i>',
                        data: {
                            toggle: 'dropdown'
                        }
                    }),
                    ui.dropdown({
                        items: [],
                        className: key + '-dropdown',
                        click: function(event) {
                            const value = $(event.target).data('value');
                            if (value) {
                                context.invoke('editor.pasteHTML', '<p>' + value + '</p>');
                            }
                        }
                    })
                ]);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        var dropdownButtonItems = [];
                        const result = data.data.data;
                        if (result.length > 0) {
                            result.forEach(function(item) {
                                const encryptId = CryptoJS.AES.encrypt(item.id.toString(),
                                    "lancangkuning2");
                                dropdownButtonItems.push(
                                    `<div data-value="[${key}=${encryptId}]" class="p-1 garuda-dropdown-item"><a href="#" data-value="[${key}=${encryptId}]" style="color: black">${item.title}</a></div>`
                                );
                            });
                        } else {
                            dropdownButtonItems.push(
                                `<div data-value="">Tidak ada ${buttonLabel} Aktif</div>`);
                        }

                        $('.' + key + '-dropdown').html(dropdownButtonItems.join(''));
                    },
                    error: function(data) {
                        console.log(data);
                        $('.' + key + '-dropdown').html(
                            `<div data-value="">Tidak ada ${buttonLabel} Aktif</div>`);
                    }
                });

                return button.render();
            };

            let id = '{{ $id }}'
            let summernote = id && id != '' ? '#' + id : '[summernote]'

            $(summernote).summernote({
                toolbar: [
                    ['style', ['style']],
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript',
                        'subscript', 'clear'
                    ]],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['color', ['color']],
                    ['table', ['table']],
                    ['insert', ['link', 'unlink', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                    ['help', ['help']],
                    ['customButton', ['quiz', 'poll']]
                ],
                styleTags: [
                    'p',
                    {
                        title: 'Blockquote',
                        tag: 'blockquote',
                        className: 'blockquote',
                        value: 'blockquote'
                    },
                    'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
                ],
                blockquoteBreakingLevel: 2,
                tableClassName: 'table table-bordered border-2 table-striped',
                height: ($(window).height() - 300),
                callbacks: {
                    onImageUpload: function(image) {
                        uploadImage(image[0]);
                    }
                },
                onInit: function() {
                    var existingImageUrl = '{!! $result->gambar ?? '' !!}';
                    if (existingImageUrl) {
                        var image = $('<img>').attr('src', existingImageUrl);
                        $(summernote).summernote("pasteHTML", image[0].outerHTML);
                    }
                }
            })

            function uploadImage(image) {
                var data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("image", image);
                var dataUrl = '{{ url('upload-image') }}';

                $.ajax({
                    url: dataUrl,
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    type: "post",
                    success: function(json) {
                        console.log(json);
                        var image = $('<img>').attr('src', json.path);
                        image.attr('style', 'max-width: 100%;');
                        $(summernote).summernote("insertNode", image[0]);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        });
    </script>
@endpush
