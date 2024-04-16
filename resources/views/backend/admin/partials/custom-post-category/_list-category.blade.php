<li class="child-content margin-left-{{ $indent }}">
    @if ($indent > 0)
        <span class="branch-path"></span>
    @endif
    <div class="card shadow shadow-sm border border-2">
        <div class="card-body px-3 py-2 shadow d-flex justify-content-between align-items-center gap-3">
            <div class="d-flex gap-3 gap-lg-5">
                <div>
                    <small>Nama Kategori</small>
                    <div class="fw-bold">{{ $category->category_name_id }} @empty(!$category->category_name_en)
                            ({{ $category->category_name_en }})
                        @endempty
                    </div>
                </div>

                <div>
                    <small>Status</small>
                    <span
                        class="d-block badge {{ $category->category_active ? 'bg-success' : 'bg-danger' }}">{{ $category->category_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                </div>
            </div>

            <div>
                @php
                    $encId = encodeId($category->{$primaryKey});
                    $action = [
                        [
                            userCan("{$permission}.ubah"),
                            'Ubah',
                            'fas fa-pencil-alt text-warning',
                            urlWithQueryParams("{$mainURL}/{$encId}/edit"),
                        ],
                        [
                            userCan("{$permission}.hapus"),
                            'Hapus',
                            'fas fa-trash-alt text-danger',
                            'onClick' => ['hapus', url("{$mainURL}/{$encId}"), $title, $category->{$descKey} ?? ''],
                        ],
                    ];
                @endphp
                {!! Blade::render(actionButtons($action)) !!}
            </div>
        </div>
    </div>
</li>

@push('style')
    <style>
        .margin-left-{{ $indent }} {
            margin-left: {{ $indent * 0.5 }}rem !important;
        }
    </style>
@endpush
