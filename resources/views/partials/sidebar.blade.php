@php
    $menus = config('sidebar');
@endphp
<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">

        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="index.html">
                        <img src="https://designreset.com/cork/html/src/assets/img/logo.svg" class="navbar-logo"
                            alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a href="/" class="nav-link"> MONDYS </a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            @php
                $menus = config('sidebar');
            @endphp

            @foreach ($menus as $menu)
                @php
                    $childActive = false;

                    if (isset($menu['children'])) {
                        foreach ($menu['children'] as $child) {
                            if (request()->routeIs($child['route'])) {
                                $childActive = true;
                            }
                        }
                    }
                @endphp

                <li
                    class="menu {{ $childActive || (isset($menu['route']) && request()->routeIs($menu['route'])) ? 'active' : '' }}">

                    {{-- MENU WITH SUBMENU --}}
                    @if (isset($menu['children']))
                        <a href="#{{ $menu['id'] }}" data-bs-toggle="collapse"
                            aria-expanded="{{ $childActive ? 'true' : 'false' }}" class="dropdown-toggle">

                            <div>
                                <i class="{{ $menu['icon'] }}" style="width: 24px, height: 24px"></i>
                                <span>{{ $menu['title'] }}</span>
                            </div>

                            <div>
                                <i class="fa-solid fa-chevron-right" style="width: 24px, height: 24px"></i>
                            </div>

                        </a>

                        <ul class="collapse submenu list-unstyled {{ $childActive ? 'show' : '' }}"
                            id="{{ $menu['id'] }}" data-bs-parent="#accordionExample">

                            @foreach ($menu['children'] as $child)
                                <li class="{{ request()->routeIs($child['route']) ? 'active' : '' }}">
                                    <a href="{{ route($child['route']) }}">
                                        {{ $child['title'] }}
                                    </a>
                                </li>
                            @endforeach

                        </ul>

                        {{-- MENU WITHOUT SUBMENU --}}
                    @else
                        <a href="{{ route($menu['route']) }}" aria-expanded="false" class="dropdown-toggle">
                            <div>
                                <i class="{{ $menu['icon'] }}"></i>
                                <span>{{ $menu['title'] }}</span>
                            </div>
                        </a>
                    @endif

                </li>
            @endforeach
        </ul>

    </nav>
</div>
<!--  END SIDEBAR  -->
