<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src={{ asset(get_setting('logo')) }} alt="" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">AmenaWhite</span>
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

                <li class="nav-item">
                    <a href="{{ route('users.index') }}"
                        class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Manage Users</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('categories.index') }}"
                        class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-grid-fill"></i>
                        <p>Categories</p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('contents.index')}}"
                        class="nav-link {{ request()->routeIs('contents.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-stack"></i>
                        <p>Contents</p>
                    </a>
                </li> --}}

                @php
                    $journalPage = request()->routeIs(
                        'journal.*',
                        'journal-type.*',
                        'admin.profile.*',
                        'admin.dynamic_page.*',
                    );
                @endphp

                <li class="nav-item has-treeview {{ $journalPage ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link custom-parent {{ $journalPage ? 'active' : '' }}"
                        data-lte-toggle="treeview">
                        <i class="nav-icon bi bi-stack"></i>
                        <span class="ms-2">Journal</span>
                        <i class="nav-arrow bi bi-chevron-right ms-auto"></i>
                    </a>

                    <ul class="nav nav-treeview custom-submenu" style="{{ $journalPage ? 'display:block;' : '' }}">

                        <li class="nav-item">
                            <a href="{{ route('journal.index')}}"
                                class="nav-link {{ request()->routeIs('journal.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle fs-7"></i>
                                <span>All Journal</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('journal-type.index') }}"
                                class="nav-link {{ request()->routeIs('journal-type*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle fs-7"></i>
                                <span>Journal Type</span>
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
