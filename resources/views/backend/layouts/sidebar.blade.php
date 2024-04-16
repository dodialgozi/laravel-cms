<ul class="metismenu list-unstyled" id="side-menu">
    <li class="menu-title" key="t-menu">Menu</li>
    <li>
        <a href="{{ url('index') }}" class="waves-effect">
            <i class="bx bx-home-circle"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @if (getLevel() == 'administrator')
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bxs-user-detail"></i>
                <span>Role Management</span>
            </a>
            <ul class="sub-menu" aria-expanded="true">
                <li><a href="{{ url('role') }}" key="t-vertical">Role</a></li>
                <li><a href="{{ url('admin-menu') }}" key="t-vertical">Menu</a></li>
                <li><a href="{{ url('permission') }}" key="t-vertical">Permission</a></li>
                <li><a href="{{ url('permission-group') }}" key="t-vertical">Permission Group</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="bx bxs-building-house"></i>
                <span>Instance Management</span>
            </a>
            <ul class="sub-menu" aria-expanded="true">
                <li><a href="{{ url('instance') }}" key="t-vertical">Instance</a></li>
                <li><a href="{{ url('user-instance') }}" key="t-vertical">User Instance</a></li>
            </ul>
        </li>
    @endif

    @if (getLevel() == 'administrator')
        <li>
            <a href="{{ url('user') }}" class="waves-effect">
                <i class="bx bxs-user"></i>
                <span>User</span>
            </a>
        </li>
    @endif

    @php $postType = getCustomPostType() @endphp
    @if (count($postType) > 0)
        <li>
            <a href="javascript:void(0);" class="waves-effect has-arrow menu-level-1">
                <i class="bx bx-customize"></i>
                <span>Custom Post</span>
            </a>
            <ul class="sub-menu" aria-expanded="true">
                @foreach ($postType as $pt)
                    <li>
                        <a href="{{ url('custom-post') }}/{{ $pt->post_type_code }}/custom"
                            class="menu-level-2 waves-effect">
                            <span>{{ $pt->post_type_name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endif

    @if (getLevel() != 'administrator' && !empty(getInstanceId()))
        @php $menu = getMenu(); @endphp
        @foreach ($menu as $m1)
            <li>
                @php $hasChildren = count($m1->menu_children ?? []) > 0; @endphp
                <a href="{{ $hasChildren ? 'javascript: void(0);' : getMenuLink($m1->menu_link) }}"
                    class="menu-level-1 waves-effect {{ $m1->menu_class }} {{ $hasChildren ? 'has-arrow' : '' }}">
                    <i class="bx {{ $m1->menu_icon }}"></i>
                    <span>{{ $m1->menu_name }}</span>
                </a>
                @if ($hasChildren)
                    <ul class="sub-menu" aria-expanded="true">
                        @foreach ($m1->menu_children as $m2)
                            <li>
                                @php $hasChildren = count($m2->menu_children ?? []) > 0; @endphp
                                <a href="{{ $hasChildren ? 'javascript: void(0);' : getMenuLink($m2->menu_link) }}"
                                    class="menu-level-2 waves-effect {{ $m2->menu_class }} {{ $hasChildren ? 'has-arrow' : '' }}">
                                    <span>{{ $m2->menu_name }}</span>
                                </a>
                                @if ($hasChildren)
                                    <ul class="sub-menu" aria-expanded="true">
                                        @foreach ($m2->menu_children as $m3)
                                            <li>
                                                <a href="{{ getMenuLink($m3->menu_link) }}"
                                                    class="menu-level-3 {{ $m3->menu_class }}" key="t-vertical">
                                                    <span>{{ $m3->menu_name }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    @endif
</ul>

@push('script')
    <script>
        $(function() {
            $sidebar = $('#sidebar-menu');
            const runCountParent = (level) => {
                $sidebar.find(`.menu-level-${level}.has-arrow`).each(function() {
                    let count = 0;
                    $(this).next().find('>li>a>span.badge').each(function() {
                        count += parseInt($(this).text());
                    });
                    if (count) {
                        $(this).find('>span.badge').remove();
                        $(this).append(
                            `<span class="badge rounded-pill bg-info float-end">${count}</span>`);
                        $(this).addClass('not-has-arrow');
                    } else {
                        $(this).removeClass('not-has-arrow');
                    }
                });
            }

            // const loadNotifikasiMenu = () => {
            //     $.ajax({
            //         url: '{{ url('notifikasi-menu') }}',
            //         method: 'POST',
            //     }).done(data => {
            //         for (const key in data) {
            //             const count = data[key];
            //             const $menu = $sidebar.find(`.${key}`);
            //             $menu.find('span.badge').remove();
            //             if(count) {
            //                 $menu.append(`<span class="badge rounded-pill bg-info float-end">${count}</span>`);
            //             }
            //         }

            //         runCountParent(2);
            //         runCountParent(1);
            //     });
            // }

            // loadNotifikasiMenu();
            // setInterval(() => {
            //     loadNotifikasiMenu();
            // }, 120 * 1000);
        });
    </script>
@endpush
