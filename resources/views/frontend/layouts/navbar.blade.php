<div class="header-nav-bar">
    <div class="container">
        <div
            class="header-nav justify-content-start header-nav-line header-nav-bottom-line header-nav-bottom-line-effect-1">
            <div
                class="header-nav-main header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-effect-2 header-nav-main-sub-effect-1" style="top: 0!important">
                <nav class="collapse">
                    <ul class="nav nav-pills" id="mainNav">
                        @foreach ($menu as $item)
                        @php
                        $active = false;

                        if ($item->menu_link == url()->current()) {
                            $active = true;
                        } else if ($item->children->count() > 0) {
                            foreach ($item->children as $child) {
                                if ($child->menu_link == url()->current()) {
                                    $active = true;
                                    break;
                                }
                            }
                        }
                        @endphp
                        <li class="dropdown">
                            <a class="dropdown-item {{ $active ? 'active' : '' }}"
                                href="{{ $item->children->count() > 0 ? 'javascript:void(0)' : url($item->menu_link) }}">
                                {{ $item->menu_name }}
                            </a>

                            @if ($item->children->count() > 0)
                            <ul class="dropdown-menu">
                                @foreach ($item->children as $child)
                                <li>
                                    <a class="dropdown-item" href="{{ $child->menu_link }}">
                                        {{ $child->menu_name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>