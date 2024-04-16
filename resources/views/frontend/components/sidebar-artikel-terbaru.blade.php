<div class="py-1 clearfix">
    <hr class="my-2">
</div>

<div class="px-3 mt-4">
    <h3 class="text-color-dark text-capitalize font-weight-bold text-5 m-0 mb-3">Artikel Terbaru</h3>
    @foreach ($newest_post as $post)
    <div class="mb-1">
        <a href="javascript:void(0);"
            class="text-color-default text-uppercase text-1 mb-0 d-block text-decoration-none">{{
            printDate($post->post_date) }} <span class="opacity-3 d-inline-block px-2">|</span> {{
            $post->user->user_name }} <span class="opacity-3 d-inline-block px-2">|</span> {{
            $post->post_view ?? 0 }} <i class="fas fa-eye ms-1"></i> </a>
        <a href="{{ url('artikel') }}/{{ $post->post_slug }}"
            class="text-color-dark text-hover-primary font-weight-bold text-3 d-block pb-3 line-height-4">{{
            $post->post_title }}</a>
    </div>
    @endforeach
</div>