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

        @php
            $sidebarUser = auth()->user();
            $sidebarRole = $sidebarUser?->hasRole('super-admin')
                ? 'Super Admin'
                : ($sidebarUser?->hasRole('admin')
                    ? 'Administrator'
                    : ($sidebarUser?->hasRole('vendor')
                        ? 'Vendor'
                        : ($sidebarUser?->hasRole('user') ? 'Pelanggan' : 'Pengguna')));
            $sidebarName = $sidebarUser?->name ?? 'Pengguna';
            $sidebarAvatar = $sidebarUser?->avatar;
            $sidebarAvatarIsRemote = $sidebarAvatar && \Illuminate\Support\Str::startsWith($sidebarAvatar, ['http://', 'https://']);
            $sidebarAvatarExists = $sidebarAvatar && !$sidebarAvatarIsRemote && \Illuminate\Support\Facades\Storage::disk('public')->exists($sidebarAvatar);
            $sidebarAvatarUrl = $sidebarAvatarIsRemote
                ? $sidebarAvatar
                : ($sidebarAvatarExists ? asset('storage/' . $sidebarAvatar) : asset('dist/assets/images/avatar.jpg'));
            $sidebarOtherGroupPermissions = [
                'articles.index',
                'testimonial.index',
                'menu.role-permission',
                'menu-item.index',
                'menu.keamanan',
                'sita.analytics.view',
            ];
            $sidebarOtherGroups = $menus
                ->whereIn('permission_name', $sidebarOtherGroupPermissions)
                ->filter(fn ($group) => $sidebarUser?->can($group->permission_name))
                ->map(fn ($group) => [
                    'group' => $group,
                    'items' => $group->items->filter(fn ($item) => $sidebarUser?->can($item->permission_name)),
                ])
                ->filter(fn ($group) => $group['items']->isNotEmpty())
                ->values();
            $sidebarOtherFirstPermission = data_get($sidebarOtherGroups->first(), 'group.permission_name');
            $sidebarRouteIsActive = fn ($route) => request()->routeIs(
                $route,
                \Illuminate\Support\Str::beforeLast($route, '.') . '.*'
            );
        @endphp
        <hr class="horizontal dark mt-0">

        {{-- Menu --}}
        <div class="sidebar-menu">
            <ul class="menu navbar-nav" aria-label="Navigasi utama">

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
                @php
                    $currentUser = auth()->user();
                    $isAdmin = $currentUser?->hasAnyRole(['super-admin', 'admin']) ?? false;
                    $isCustomer = ($currentUser?->hasRole('user') ?? false) && ! $isAdmin;
                    $isPackageGroup = $menu->permission_name === 'package.member';
                    $customerPackageRoutes = ['package-offer-requests.mine', 'package-offers.mine'];
                    $visibleItems = $menu->items->filter(function ($item) use ($currentUser, $isAdmin, $isCustomer, $isPackageGroup, $customerPackageRoutes) {
                        if (! $currentUser || ! $currentUser->can($item->permission_name)) {
                            return false;
                        }

                        if (! $isPackageGroup) {
                            return true;
                        }

                        if ($isAdmin) {
                            return ! in_array($item->route, $customerPackageRoutes, true);
                        }

                        if ($isCustomer) {
                            return $item->route === 'packages.member';
                        }

                        return ! in_array($item->route, $customerPackageRoutes, true);
                    });
                    $isOtherGroup = in_array($menu->permission_name, $sidebarOtherGroupPermissions, true);
                    $isOtherGroupStart = $isOtherGroup && $menu->permission_name === $sidebarOtherFirstPermission;
                    $shouldRenderMenu = $currentUser?->can($menu->permission_name) && $visibleItems->isNotEmpty();
                @endphp

                @if ($isOtherGroupStart)
                    <li class="sidebar-title">Menu Lainnya</li>
                @endif

                @if ($shouldRenderMenu)
                @if ($isOtherGroup)
                    @php
                        $groupIsActive = $visibleItems->contains(fn ($item) => $sidebarRouteIsActive($item->route));
                        $groupLabel = $menu->permission_name === 'sita.analytics.view' ? 'Sita AI' : $menu->name;
                        $groupIcon = $menu->permission_name === 'sita.analytics.view' ? 'bx-bot' : $menu->icon;
                        $submenuId = 'menu-group-' . $menu->id;
                    @endphp
                    <li class="sidebar-item has-sub {{ $groupIsActive ? 'active' : '' }}">
                        <a href="#" class="sidebar-link has-sub" aria-expanded="{{ $groupIsActive ? 'true' : 'false' }}" aria-controls="{{ $submenuId }}">
                            <span class="icon icon-shape icon-sm"><i class="bx {{ $groupIcon }}"></i></span>
                            <span class="nav-link-text ms-1">{{ $groupLabel }}</span>
                        </a>
                        <ul id="{{ $submenuId }}" class="submenu {{ $groupIsActive ? 'submenu-open' : 'submenu-closed' }}">
                            @foreach ($visibleItems as $item)
                                @php $itemIsActive = $sidebarRouteIsActive($item->route); @endphp
                                <li class="submenu-item {{ $itemIsActive ? 'active' : '' }}">
                                    <a class="submenu-link" href="{{ route($item->route) }}" @if ($itemIsActive) aria-current="page" @endif>
                                        {{ $item->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                @if ($visibleItems->count() > 1)
                    <li class="sidebar-title">{{ $menu->name }}</li>
                @endif
                @foreach ($visibleItems as $item)
                    @php
                        $itemLabel = match ($item->route) {
                            'packages.member' => 'Katalog Paket',
                            'orders.index' => 'Daftar Pesanan',
                            default => $item->name,
                        };
                        $itemIsActive = $item->route === 'orders.index'
                            ? request()->routeIs('orders.*')
                            : request()->routeIs($item->route);
                        $itemIcon = match ($item->route) {
                            'packages.index' => 'bx-package',
                            'packages.member' => 'bx-store-alt',
                            'package-promos.index' => 'bx-purchase-tag-alt',
                            'orders.index' => 'bx-receipt',
                            'user.index' => 'bx-group',
                            'route.index' => 'bx-route',
                            'menu.index' => 'bx-menu-alt-left',
                            'website-identity.index' => 'bx-globe',
                            'email-setting.index' => 'bx-envelope',
                            'articles.index' => 'bx-news',
                            'events.index' => 'bx-calendar-event',
                            'categories.index' => 'bx-category-alt',
                            'banner.index' => 'bx-images',
                            'testimonial.index' => 'bx-message-square-check',
                            'faq.index' => 'bx-help-circle',
                            'venues.index' => 'bx-building-house',
                            'vendor.index' => 'bx-camera',
                            'vendors_categories.index' => 'bx-category',
                            'memberships.index' => 'bx-wallet',
                            'konsultasi.index' => 'bx-message-rounded-dots',
                            'pengguna.index' => 'bx-user-plus',
                            'permission.index', 'role.index' => 'bx-shield-quarter',
                            default => $menu->icon,
                        };
                    @endphp
                    <li class="sidebar-item {{ $itemIsActive ? 'active' : '' }}">
                        <a class="sidebar-link nav-link" href="{{ route($item->route) }}" @if ($itemIsActive) aria-current="page" @endif>
                            <span class="icon icon-shape icon-sm"><i class="bx {{ $itemIcon }}"></i></span>
                            <span class="nav-link-text ms-1">{{ $itemLabel }}</span>
                        </a>
                    </li>
                    @if ($menu->permission_name === 'package.member' && $item->route === 'package-promos.index' && $currentUser?->can('package.index'))
                    <li class="sidebar-item {{ request()->routeIs('package-offers.*') ? 'active' : '' }}">
                        <a class="sidebar-link nav-link" href="{{ route('package-offers.index') }}" @if (request()->routeIs('package-offers.*')) aria-current="page" @endif>
                            <span class="icon icon-shape icon-sm"><i class="bx bx-file"></i></span>
                            <span class="nav-link-text ms-1">Penawaran Khusus</span>
                            @if ($pendingPackageOfferCount > 0)
                            <span class="badge rounded-pill text-bg-danger ms-auto">{{ $pendingPackageOfferCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('package-offer-requests.*') ? 'active' : '' }}">
                        <a class="sidebar-link nav-link" href="{{ route('package-offer-requests.index') }}" @if (request()->routeIs('package-offer-requests.*')) aria-current="page" @endif>
                            <span class="icon icon-shape icon-sm"><i class="bx bx-message-square-detail"></i></span>
                            <span class="nav-link-text ms-1">Permintaan Pelanggan</span>
                            @if ($pendingPackageRequestCount > 0)
                            <span class="badge rounded-pill text-bg-danger ms-auto">{{ $pendingPackageRequestCount }}</span>
                            @endif
                        </a>
                    </li>
                    @endif
                @endforeach
                @endif
                @endif
                @endforeach

                @php
                    $customerPackageItems = collect();
                    $customerPackageGroup = $menus->firstWhere('permission_name', 'package.member');
                    if ($customerPackageGroup && auth()->user()?->hasRole('user')) {
                        $customerPackageItems = $customerPackageGroup->items
                            ->filter(fn ($item) => in_array($item->route, ['package-offer-requests.mine', 'package-offers.mine'], true))
                            ->filter(fn ($item) => auth()->user()->can($item->permission_name));
                    }
                    $customerPackageMenuActive = $customerPackageItems->contains(fn ($item) => request()->routeIs($item->route));
                @endphp
                @if ($customerPackageItems->isNotEmpty())
                <li class="sidebar-title">Tindak Lanjut Saya</li>
                @foreach ($customerPackageItems as $item)
                <li class="sidebar-item {{ request()->routeIs($item->route) ? 'active' : '' }}">
                    <a href="{{ route($item->route) }}" class="sidebar-link nav-link" @if (request()->routeIs($item->route)) aria-current="page" @endif>
                        <span class="icon icon-shape icon-sm">
                            <i class="bx {{ $item->route === 'package-offer-requests.mine' ? 'bx-message-square-detail' : 'bx-file-blank' }}"></i>
                        </span>
                        <span class="nav-link-text ms-1">{{ $item->name }}</span>
                    </a>
                </li>
                @endforeach
                @endif

                @role('user')
                <li class="sidebar-title">Akun</li>
                <li class="sidebar-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}" class="sidebar-link nav-link" @if (request()->routeIs('profile.*')) aria-current="page" @endif>
                        <div class="icon icon-shape icon-sm"><i class="bx bx-user"></i></div>
                        <span class="nav-link-text ms-1">Profil Saya</span>
                    </a>
                </li>
                @endrole

            </ul>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-footer__account">
                <img
                    src="{{ $sidebarAvatarUrl }}"
                    alt="{{ $sidebarName }}"
                    class="sidebar-footer__avatar"
                    onerror="this.onerror=null;this.src='{{ asset('dist/assets/images/avatar.jpg') }}'">
                <div class="sidebar-footer__identity">
                    <strong title="{{ $sidebarName }}">{{ $sidebarName }}</strong>
                    <small title="{{ $sidebarRole }}">{{ $sidebarRole }}</small>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-footer__logout" aria-label="Keluar" title="Keluar">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>