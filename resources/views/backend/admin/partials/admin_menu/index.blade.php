@extends('backend.layouts.panel')

@section('title', $title)

@section('panel_content')

    <ul id="tree"></ul>

@endsection

@push('modal')
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h3>Tambah Menu</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row" action="{{ url("{$mainURL}") }}" method="POST">
                        <input type="hidden" name="parent" class="parentId">
                        <div class="col-md-12 mb-3">
                            <x-form-input label="Nama Menu" name="name" required />
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="form-label">Icon</label>
                                <select class="form-control" placeholder="Icon" name="icon">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-input label="Link" name="link" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-input label="Class" name="class" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-select2 label="Permission" name="permission" :url="url('permission/select')" function="loadPermission"
                                key="id" value="nama" :allowClear=true :autoInit=false :useSelector=true />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-select2-option label="Permit" name="permit[]" :options=$permits :allowClear=true
                                class="permit" multiple />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-switch label="Enable" name="enable" :checked=true labelOn="Ya" labelOff="Tdk" />
                        </div>

                        <div class="col-md-12 mt-3 d-flex flex-row justify-content-end">
                            <button type="submit" class="btn btn-md btn-primary waves-effect btn-label waves-light"><i
                                    class="label-icon fas fa-check"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('modal')
    <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h3>Ubah Menu</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row" action="{{ url("{$mainURL}") }}" method="POST">
                        @method('PUT')
                        <input type="hidden" name="id" class="menuId">
                        <div class="col-md-12 mb-3">
                            <x-form-input label="Nama Menu" name="name" required />
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="form-label">Icon</label>
                                <select class="form-control" placeholder="Icon" name="icon">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-input label="Link" name="link" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-input label="Class" name="class" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-select2 label="Permission" name="permission" :url="url('permission/select')" function="loadPermission"
                                key="id" value="nama" :allowClear=true :autoInit=false :useSelector=true
                                :noScript=true />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-select2-option label="Permit" name="permit[]" :options=$permits :allowClear=true
                                class="permit" multiple />
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-form-switch label="Enable" name="enable" :checked=true labelOn="Ya" labelOff="Tdk" />
                        </div>

                        <div class="col-md-12 mt-3 d-flex flex-row justify-content-end">
                            <button type="submit" class="btn btn-md btn-primary waves-effect btn-label waves-light"><i
                                    class="label-icon fas fa-check"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

<x-validation selector="#createModal form" />
<x-validation selector="#updateModal form" />

@push('style')
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/sortable-list-tree/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/toastr/build/toastr.min.css') }}">
    <style>
        .branch-level-3 .btn-tambah {
            display: none !important;
        }

        .contents.text-danger span {
            text-decoration: line-through;
        }
    </style>
@endpush

@push('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('backend/assets/libs/sortable-list-tree/js/custom.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/toastr/build/toastr.min.js') }}"></script>
    <script>
        toastr.options.positionClass = 'toast-bottom-right';

        const iconData = {!! json_encode($icons) !!};
        var addMenuSelector, editMenuSelector;
        var addIconLoaded = false;
        var editIconLoaded = false;
        $(function() {
            const data = JSON.parse(atob('{!! base64_encode(json_encode($data)) !!}'));

            const sortable = new TreeSortable();

            const $tree = $('#tree');

            const $content = data.map(sortable.createBranch);

            $tree.html($content);
            sortable.run();

            const delay = () => {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve();
                    }, 100);
                });
            };

            // Sorting
            sortable.onSortCompleted(async (event, ui) => {
                await delay();
                saveOrder();
            });

            // Tambah
            $(document).on('click', '.btn-tambah', function(e) {
                e.preventDefault();

                addMenuSelector = $(this).getBranch();
                $('#createModal .parentId').val(addMenuSelector.data('id'));

                loadPermission($('#createModal select[name=permission]'));
                if (!addIconLoaded) {
                    loadIcon($('#createModal select[name=icon]'));
                    addIconLoaded = true;
                }

                $('#createModal [name=name]').val('');
                $('#createModal [name=link]').val('');
                $('#createModal [name=class]').val('');
                $('#createModal [name=icon]').val(null).trigger('change');
                $('#createModal [name=permission]').empty().trigger('change');
                $('#createModal .permit').val(null);
                $('#createModal [name=enable]').prop('checked', true);

                $('#createModal').modal('show');
            });

            $('#createModal').on('shown.bs.modal', function() {
                $('#createModal .permit').trigger('change');
            });

            $('#createModal form').submit(function(e) {
                e.preventDefault();
                const data = $(this).serializeJSON();
                if (data.link != '') {
                    data.link = !data.link.includes('http') ? '{[BASEURL]}/' + data.link : data.link;
                }
                const url = $(this).prop('action');
                const method = $(this).prop('method');

                $.ajax({
                    url,
                    method,
                    data,
                }).done(data => {
                    if (data.success) {
                        data = data.data;
                        addMenuSelector.addChildBranch({
                            'id': data.id,
                            'title': data.name,
                            'data': {
                                'name': data.name,
                                'icon': data.icon,
                                'link': data.link,
                                'class': data.class,
                                'permission': data.permission,
                                'permission_id': data.permission_id,
                                'permit': data.permit,
                                'enable': data.enable,
                            }
                        });
                        saveOrder();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Maaf',
                            text: data.message
                        });
                    }
                }).fail(xhr => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf',
                        text: 'Terjadi kesalahan.'
                    });
                    console.error(xhr);
                }).always(() => {
                    $('#createModal').modal('hide');
                });
            });

            // Ubah
            $(document).on('click', '.btn-ubah', function(e) {
                e.preventDefault();

                editMenuSelector = $(this).getBranch();
                $('#updateModal .menuId').val(editMenuSelector.data('id'));

                loadPermission($('#updateModal select[name=permission]'));
                if (!editIconLoaded) {
                    loadIcon($('#updateModal select[name=icon]'));
                    editIconLoaded = true;
                }

                const data = JSON.parse(atob(editMenuSelector.data('data')));

                $('#updateModal [name=name]').val(data.name);
                $('#updateModal [name=class]').val(data.class);
                if (data.link != null) {
                    $('#updateModal [name=link]').val(data.link.replace('{[BASEURL]}/', ''));
                } else {
                    $('#updateModal [name=link]').val('');
                }
                if (data.icon == null) {
                    $('#updateModal [name=icon]').val(null).trigger('change');
                } else {
                    $('#updateModal [name=icon]').val(data.icon).trigger('change');
                }
                if (data.permission_id == null) {
                    $('#updateModal [name=permission]').empty().trigger('change');
                } else {
                    $('#updateModal [name=permission]').empty().append(new Option(data.permission, data
                        .permission_id, false, false)).trigger('change');
                }
                if (data.permit.length > 0) {
                    $('#updateModal .permit').val(data.permit).trigger('change');
                } else {
                    $('#updateModal .permit').val(null).trigger('change');
                }
                $('#updateModal [name=enable]').prop('checked', data.enable);

                $('#updateModal').modal('show');
            });

            $('#updateModal').on('shown.bs.modal', function() {
                $('#updateModal .permit').trigger('change');
            });

            $('#updateModal form').submit(function(e) {
                e.preventDefault();

                const data = $(this).serializeJSON();
                if (data.link != '') {
                    data.link = !data.link.includes('http') ? '{[BASEURL]}/' + data.link : data.link;
                }
                const url = $(this).prop('action');
                const method = $(this).prop('method');
                $.ajax({
                    url: `${url}/${data.id}`,
                    method,
                    data,
                }).done(data => {
                    if (data.success) {
                        data = data.data;
                        editMenuSelector.prevObject.updateBranch({
                            'id': data.id,
                            'title': data.name,
                            'data': {
                                'name': data.name,
                                'icon': data.icon,
                                'link': data.link,
                                'class': data.class,
                                'permission': data.permission,
                                'permission_id': data.permission_id,
                                'permit': data.permit,
                                'enable': data.enable,
                            }
                        });
                        toastr.success('Perubahan menu berhasil disimpan.');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Maaf',
                            text: data.message
                        });
                    }
                }).fail(xhr => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf',
                        text: 'Terjadi kesalahan.'
                    });
                    console.error(xhr);
                }).always(() => {
                    $('#updateModal').modal('hide');
                });
            });

            // Hapus
            $(document).on('click', '.btn-hapus', function(e) {
                const ini = this;
                const branch = $(this).getBranch();
                const id = branch.data('id');
                const {
                    name
                } = JSON.parse(atob(branch.data('data')));
                Swal.fire({
                    title: 'Anda Yakin?',
                    html: `Yakin akan menghapus menu <b>${name}</b> ?`,
                    icon: 'warning',
                    iconColor: 'red',
                    showCancelButton: true,
                    confirmButtonColor: '#f46a6a',
                    cancelButtonColor: '#636678',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                }).then(function(t) {
                    if (t.value) {
                        $.ajax({
                            url: '{{ url("{$mainURL}") }}/' + id,
                            method: 'DELETE',
                        }).done(data => {
                            if (data.success) {
                                $(ini).removeBranch();
                                toastr.success('Menu berhasil dihapus.');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Maaf',
                                    text: data.message
                                });
                            }
                        }).fail(xhr => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Maaf',
                                text: 'Terjadi kesalahan.'
                            });
                            console.error(xhr);
                        });
                    }
                });
            });
        });

        function saveOrder() {
            let order = [];
            $('#tree .tree-branch').each(function() {
                const parent = $(this).data('parent');
                order.push({
                    'id': $(this).data('id'),
                    'parent': $(this).data('level') > 1 ? parent : null,
                });
            });

            $.ajax({
                url: '{{ url("{$mainURL}") }}/order',
                method: 'POST',
                data: {
                    order: order
                },
            }).done(data => {
                if (data.success) {
                    toastr.success('Urutan menu baru berhasil disimpan.');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf',
                        text: data.message
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }).fail(xhr => {
                Swal.fire({
                    icon: 'error',
                    title: 'Maaf',
                    text: 'Terjadi kesalahan.'
                });
                setTimeout(() => {
                    location.reload();
                }, 1000);
                console.error(xhr);
            });
        }

        function loadIcon(selector) {
            $(selector).select2({
                data: iconData,
                templateResult: function formatState(state) {
                    var $state = $(`
            <div class="d-flex align-items-center">
                <i class="bx bx-sm ${state.text} me-2"></i>
                <span class="fs-4">${state.text}</span>
            </div>`);
                    return $state;
                },
                placeholder: 'Icon',
                dropdownParent: $(selector).parent(),
                allowClear: true,
                allowHtml: true,
            });
        }
    </script>
@endpush
