<ul>
    @foreach ($children as $child)
        <li class="nav-item">
            <a class="nav-link bg-transparent border-0" href="@if ($child->children->isEmpty()) {{ url('kategori') }}/{{ $child->category_slug }} @else javascript:void(0) @endif" @if($child->children->isNotEmpty()) data-bs-target="#category-{{ $child->category_id }}" data-bs-toggle="collapse" @endif>{{ $child->category_name }} @if ($child->children->isNotEmpty()) ({{ $child->children->count() }}) @endif</a>
            <div class="collapse" id="category-{{ $child->category_id }}">
            @if ($child->children->isNotEmpty())
                @include('frontend.components.child-category', ['children' => $child->children])
            @endif
            </div>
        </li>
    @endforeach
</ul>