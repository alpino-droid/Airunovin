<aside class="sidebar">
    <div class="brand d-flex align-items-center">
        <div class="brand-badge">A</div>
        <div>
            <div class="brand-name">{{ __('Admin Panel') }}</div>
            <small class="text-white-50">{{ __('Club & Event') }}</small>
        </div>
    </div>

    <nav class="nav-menu">
        <div class="nav-section">{{ __('Menu') }}</div>

        <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-speedometer2 fs-5"></i></span>
            <span>{{ __('Dashboard') }}</span>
        </a>
        <a href="{{ route('admin.club') }}" class="menu-link {{ request()->routeIs('admin.club') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-shield-shaded fs-5"></i></span>
            <span>{{ __('Club') }}</span>
        </a>
        <a href="{{ route('admin.event') }}" class="menu-link {{ request()->routeIs('admin.event') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-calendar-event fs-5"></i></span>
            <span>{{ __('Event') }}</span>
        </a>
        <a href="{{ route('admin.marketplace') }}" class="menu-link {{ request()->routeIs('admin.marketplace') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-shop fs-5"></i></span>
            <span>{{ __('Marketplace (Toko)') }}</span>
        </a>
        <a href="{{ route('admin.product') }}" class="menu-link {{ request()->routeIs('admin.product') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-box-seam fs-5"></i></span>
            <span>{{ __('Produk') }}</span>
        </a>
        <a href="{{ route('admin.registrasi') }}" class="menu-link {{ request()->routeIs('admin.registrasi') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-person-lines-fill fs-5"></i></span>
            <span>{{ __('Registration') }}</span>
        </a>
        <a href="{{ route('admin.settings') }}" class="menu-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-gear fs-5"></i></span>
            <span>{{ __('Settings') }}</span>
        </a>
    </nav>
</aside>
