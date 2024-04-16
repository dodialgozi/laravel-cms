@foreach ($results as $result)
    @include('backend.admin.partials.custom-post-category._list-category', [
        'category' => $result,
        'indent' => $indent,
    ])
    @if ($result->children->count() > 0)
        @include('backend.admin.partials.custom-post-category._nested-category', [
            'results' => $result->children,
            'indent' => $indent + 3,
        ])
    @endif
@endforeach
