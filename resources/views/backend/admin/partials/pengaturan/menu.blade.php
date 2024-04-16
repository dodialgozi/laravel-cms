@extends('backend.layouts.panel')

@section('title', $title)

@section('panel_content')

    <div class="row g-5">
        <div class="col-md-6">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="post-category-tab" data-bs-toggle="tab"
                        data-bs-target="#post-category-tab-pane" type="button" role="tab"
                        aria-controls="post-category-tab-pane" aria-selected="true">Kategori Post</button>
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="location-category-tab" data-bs-toggle="tab"
                        data-bs-target="#location-category-tab-pane" type="button" role="tab"
                        aria-controls="location-category-tab-pane" aria-selected="false">Kategori Lokasi</button>
                </li> --}}

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="page-tab" data-bs-toggle="tab" data-bs-target="#page-tab-pane"
                        type="button" role="tab" aria-controls="page-tab-pane" aria-selected="false">Page</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="custom-link-tab" data-bs-toggle="tab"
                        data-bs-target="#custom-link-tab-pane" type="button" role="tab"
                        aria-controls="custom-link-tab-pane" aria-selected="false">Custom Link</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="post-category-tab-pane" role="tabpanel"
                    aria-labelledby="post-category-tab" tabindex="0">
                    <div class="card border border-3">
                        <div class="card-body" style="max-height: 100vh; overflow: scroll">
                            <div id="post-cat-tree"></div>
                        </div>
                    </div>
                </div>

                {{-- <div class="tab-pane fade" id="location-category-tab-pane" role="tabpanel"
                    aria-labelledby="location-category-tab" tabindex="0">
                    <div class="card border border-3">
                        <div class="card-body" style="max-height: 100vh; overflow: scroll">
                            <div id="location-cat-tree"></div>
                        </div>
                    </div>
                </div> --}}

                <div class="tab-pane fade" id="page-tab-pane" role="tabpanel" aria-labelledby="page-tab" tabindex="0">
                    <div class="card border border-3">
                        <div class="card-body" style="max-height: 100vh; overflow: scroll">
                            <div id="page-tree"></div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-link-tab-pane" role="tabpanel" aria-labelledby="custom-link-tab"
                    tabindex="0">
                    <div class="card border border-3">
                        <div class="card-body">
                            <form id="customLink">
                                <div class="mb-3">
                                    <x-form-input label="Nama" id="menu_name" name="{{ $input }}[menu_name]"
                                        placeholder="Masukkan nama menu" required />
                                </div>

                                <div class="mb-3">
                                    <x-form-input label="Link" id="menu_link" name="{{ $input }}[menu_link]"
                                        placeholder="Masukkan link menu" required />
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-6">
            <div class="card border border-3">
                <div class="card-header">
                    <h5 class="card-title">Menu</h5>
                </div>
                <div class="card-body">
                    <div id="menu-tree"></div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-danger waves-effect btn-label waves-light" id="simpanMenu" onclick="deleteTree()"><i
                        class="label-icon fas fa-trash"></i> Hapus</button>
                <button class="btn btn-success waves-effect btn-label waves-light" id="simpanMenu" onclick="simpanTree()"><i
                        class="label-icon fas fa-check"></i> Simpan</button>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <style>
        .nav-link.active {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }
    </style>
    <link href="//cdn.jsdelivr.net/npm/jquery.fancytree@2.27/dist/skin-win8/ui.fancytree.min.css" rel="stylesheet">
@endpush

@push('script')
    <script src="//cdn.jsdelivr.net/npm/jquery.fancytree@2.27/dist/jquery.fancytree-all-deps.min.js"></script>
    <script>
        function simpanTree() {
            var tree = $("#menu-tree").fancytree("getTree");
            var d = tree.toDict(true);
            $.ajax({
                url: "{{ url('pengaturan/menu/save-menu') }}",
                type: 'post',
                data: {
                    tree: d.children,
                    _token: "{{ csrf_token() }}"
                },
            }).done(data => {
                if (data.success) {
                    var tree = $("#menu-tree").fancytree("getTree");
                    var rootNode = tree.getNodeByKey('placeholder');
                    if (rootNode) {
                        rootNode.remove();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil simpan menu'
                    });
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

        function deleteTree() {
            var tree = $("#menu-tree").fancytree("getActiveNode");

            if (!tree || (tree.key == "beranda" || tree.title == "Beranda")) {
                return;
            }

            tree && tree.remove();
        }

        $('#customLink').submit(function(e) {
            e.preventDefault();
            tambahTree();
        });

        function tambahTree() {
            var rootNode = $("#menu-tree").fancytree("getRootNode");
            var childNode = rootNode.addChildren({
                title: $("#menu_name").val(),
                link: $("#menu_link").val()
            });
            $("#menu_name").val("");
            $("#menu_link").val("");
        }

        $(document).ready(function() {
            const postCategories = {!! $postCategories !!};
            const pages = {!! $pages !!};
            const menus = {!! $menus !!};

            $('#post-cat-tree').fancytree({
                source: postCategories,
                extensions: ["dnd5"],
                dnd5: {
                    autoExpandMS: 1000,
                    draggable: {
                        scroll: false
                    },
                    droppable: null,
                    focusOnClick: false,
                    preventRecursiveMoves: true,
                    preventVoidMoves: true,
                    smartRevert: true,
                    dragStart: function(node, data) {
                        return true;
                    },
                    dragEnd: function(node, data) {
                        return true;
                    },
                    initHelper: function(node, data) {
                        return true;
                    },
                    updateHelper: function(node, data) {
                        return true;
                    }
                }
            });

            // $('#location-cat-tree').fancytree({
            //     source: locationCategories,
            //     extensions: ["dnd5"],
            //     dnd5: {
            //         autoExpandMS: 1000,
            //         draggable: {
            //             scroll: false
            //         },
            //         droppable: null,
            //         focusOnClick: false,
            //         preventRecursiveMoves: true,
            //         preventVoidMoves: true,
            //         smartRevert: true,
            //         dragStart: function(node, data) {
            //             return true;
            //         },
            //         dragEnd: function(node, data) {
            //             return true;
            //         },
            //         initHelper: function(node, data) {
            //             return true;
            //         },
            //         updateHelper: function(node, data) {
            //             return true;
            //         }
            //     }
            // });

            $('#page-tree').fancytree({
                source: pages,
                extensions: ["dnd5"],
                dnd5: {
                    autoExpandMS: 1000,
                    draggable: {
                        scroll: false
                    },
                    droppable: null,
                    focusOnClick: false,
                    preventRecursiveMoves: true,
                    preventVoidMoves: true,
                    smartRevert: true,
                    dragStart: function(node, data) {
                        return true;
                    },
                    dragEnd: function(node, data) {
                        return true;
                    },
                    initHelper: function(node, data) {
                        return true;
                    },
                    updateHelper: function(node, data) {
                        return true;
                    }
                }
            });

            $('#menu-tree').fancytree({
                source: menus,
                select: function(event, data) {
                    var selKeys = data.tree.getSelectedNodes(true);
                },
                extensions: ["dnd5"],
                dnd5: {
                    preventVoidMoves: true,
                    preventRecursiveMoves: true,
                    autoExpandMS: 400,
                    draggable: {
                        scroll: false,
                        revert: "invalid"
                    },
                    dragStart: function(node, data) {
                        if (data.originalEvent.shiftKey) {
                            console.log("dragStart with SHIFT");
                        }
                        return true;
                    },
                    dragEnter: function(node, data) {
                        return true;
                    },
                    dragDrop: function(node, data) {
                        if (!data.otherNode) {
                            var title = $(data.draggable.element).text() + " (" + (count) ++ + ")";
                            node.addNode({
                                title: $("#menu_name").val(),
                                link: $("#menu_link").val()
                            }, data.hitMode);

                            return;
                        }
                        if (data.otherNode.tree == $("#menu-tree").fancytree("getTree")) {
                            data.otherNode.moveTo(node, data.hitMode);
                        } else {
                            data.otherNode.copyTo(node, data.hitMode);
                        }
                    }
                }
            });
        });
    </script>
@endpush
