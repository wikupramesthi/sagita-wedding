<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-wrapper active">

        {{-- Sidenav Header --}}
        <div class="sidenav-header">
            <a href="{{ route('dashboard.index') }}" class="navbar-brand m-0 d-flex align-items-center sidebar-brand">
                <img src="{{ asset('img/logo.WebP') }}" alt="Sagita Wedding" class="sidebar-brand__mark">
            </a>

            <div class="sidebar-toggler x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>

        <hr class="horizontal dark mt-0">

        {{-- Menu --}}
        <div class="sidebar-menu">
            <ul class="menu navbar-nav">

                <li class="sidebar-title">Menu Utama</li>

                <li class="sidebar-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <a class="sidebar-link nav-link" href="{{ route('dashboard.index') }}">
                        <div class="icon icon-shape icon-sm">
                            <i class="bx bx-home"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>

                @foreach ($menus as $menu)
                @can($menu->permission_name)
                @php
                // Determine if any of the submenu items are active
                $isActive = false;
                foreach ($menu->items as $item) {
                if (request()->routeIs($item->route)) {
                $isActive = true;
                break;
                }
                }
                @endphp

                <li class="sidebar-item has-sub {{ $isActive ? 'active' : '' }}">
                    <a href="#" class="sidebar-link nav-link">
                        <div class="icon icon-shape icon-sm">
                            <i class="bx {{ $menu->icon }}"></i>
                        </div>
                        <span class="nav-link-text ms-1">{{ $menu->name }}</span>
                    </a>

                    <ul class="submenu nav ms-4">
                        @foreach ($menu->items as $item)
                        @can($item->permission_name)
                        <li class="submenu-item {{ request()->routeIs($item->route) ? 'active' : '' }}">
                            <a href="{{ route($item->route) }}" class="submenu-link nav-link">
                                <span class="sidenav-mini-icon">{{ strtoupper(mb_substr($item->name, 0, 1)) }}</span>
                                <span class="sidenav-normal">{{ $item->name }}</span>
                            </a>
                        </li>
                        @endcan
                        @endforeach
                    </ul>
                </li>

                <!-- end foreach items -->
                @endcan
                <!-- end can menu -->
                @endforeach


                @role('user')
                <li class="sidebar-title">Others</li>
                <li class="sidebar-item has-sub">
                    <a href="#" class="sidebar-link nav-link">
                        <div class="icon icon-shape icon-sm">
                            <i class="bx bx-cog"></i>
                        </div>
                        <span class="nav-link-text ms-1">Pengaturan</span>
                    </a>

                    <ul class="submenu nav ms-4">
                        <li class="submenu-item">
                            <a href="{{ route('profile.edit') }}" class="submenu-link nav-link">
                                <span class="sidenav-mini-icon">M</span>
                                <span class="sidenav-normal">Profil Saya</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                <li class="sidebar-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-link nav-link">
                            <div class="icon icon-shape icon-sm">
                                <i class="bi bi-arrow-bar-left"></i>
                            </div>
                            <span class="nav-link-text ms-1">Keluar</span>
                        </button>
                    </form>
                </li>
                @endif

            </ul>
        </div>

    </div>
</div>