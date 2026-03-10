<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="./assets/img/AdminLTELogo.png" alt="" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">AdminLTE 4</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" role="menu" data-accordion="false" id="navigation">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-house-fill"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @php
                    $settingsActive = request()->routeIs(
                        'admin.system.*',
                        'admin.social.*',
                        'admin.profile.*',
                        'admin.dynamic_page.*',
                    );
                @endphp

                <li class="nav-item has-treeview {{ $settingsActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link custom-parent {{ $settingsActive ? 'active' : '' }}"
                        data-lte-toggle="treeview">
                        <i class="nav-icon bi bi-gear"></i>
                        <span class="ms-2">Settings</span>
                        <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                    </a>

                    <ul class="nav nav-treeview custom-submenu" style="{{ $settingsActive ? 'display:block;' : '' }}">

                        <li class="nav-item">
                            <a href="{{ route('admin.system.index') }}"
                                class="nav-link {{ request()->routeIs('admin.system.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle fs-7"></i>
                                <span>General Settings</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.social.index') }}"
                                class="nav-link {{ request()->routeIs('admin.social.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle fs-7"></i>
                                <span>Social Settings</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.profile.setting') }}"
                                class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle fs-7"></i>
                                <span>Profile Settings</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.dynamic_page.index') }}"
                                class="nav-link {{ request()->routeIs('admin.dynamic_page.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle fs-7"></i>
                                <span>Page Settings</span>
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
