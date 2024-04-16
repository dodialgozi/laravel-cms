<div class="py-1 clearfix">
    <hr class="my-2">
</div>
<div class="px-3 mt-4">
    <h3 class="text-color-dark text-capitalize font-weight-bold text-5 m-0">Kategori</h3>
    <ul class="nav nav-list flex-column mt-2 mb-0 p-relative right-9">
        @foreach ($kategori as $item)
            <li class="nav-item">
                <a class="nav-link bg-transparent border-0" href="@if ($item->children->isEmpty()) {{ url('kategori') }}/{{ $item->category_slug }} @else javascript:void(0) @endif" @if($item->children->isNotEmpty()) data-bs-target="#category-{{ $item->category_id }}" data-bs-toggle="collapse" @endif>{{ $item->category_name }} @if ($item->children->isNotEmpty()) ({{ $item->children->count() }}) @endif</a>
                <div class="collapse" id="category-{{ $item->category_id }}">
                @if ($item->children->isNotEmpty())
                    @include('frontend.components.child-category', ['children' => $item->children])
                @endif
                </div>
            </li>
        @endforeach
    </ul>
</div>