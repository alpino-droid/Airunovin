<header class="topbar d-flex align-items-center justify-content-between gap-3">
    <div class="search-box position-relative">
        <form action="{{ route('admin.search') }}" method="GET" class="m-0 p-0 position-relative" id="adminSearchForm">
            <div class="search-wrap position-relative">
                <button type="submit" class="btn p-0 border-0 bg-transparent icon" aria-label="Search" style="cursor: pointer; left: 0.9rem; top: 50%; transform: translateY(-50%); position: absolute; z-index: 5;">
                    <i class="bi bi-search text-muted"></i>
                </button>
                <input type="search" 
                       name="q" 
                       id="adminSearchInput" 
                       class="form-control" 
                       placeholder="{{ __('Cari event, club, produk, toko, user...') }}"
                       value="{{ request('search') ?? request('q') }}"
                       autocomplete="off"
                       style="padding-left: 2.5rem;">
            </div>

            {{-- Live Autocomplete Dropdown Preview Khusus Admin --}}
            <div id="adminSearchDropdown" class="dropdown-menu shadow-lg p-2 d-none position-absolute w-100" style="left: 0; top: calc(100% + 6px); max-height: 420px; overflow-y: auto; z-index: 1060; border-radius: 12px;">
                <div id="adminSearchDropdownContent"></div>
            </div>
        </form>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="btn-group">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">{{ __('Dashboard') }}</a>
            <a href="{{ route('admin.club') }}" class="btn btn-sm btn-outline-primary {{ request()->routeIs('admin.club') ? 'active' : '' }}">{{ __('Club') }}</a>
            <a href="{{ route('admin.event') }}" class="btn btn-sm btn-outline-primary {{ request()->routeIs('admin.event') ? 'active' : '' }}">{{ __('Event') }}</a>
            <a href="{{ route('admin.marketplace') }}" class="btn btn-sm btn-outline-primary {{ request()->routeIs('admin.marketplace') ? 'active' : '' }}">{{ __('Market') }}</a>
            <a href="{{ route('admin.product') }}" class="btn btn-sm btn-outline-primary {{ request()->routeIs('admin.product') ? 'active' : '' }}">{{ __('Produk') }}</a>
        </div>

        {{-- Tombol +Buat baru dihapus sesuai permintaan user --}}

        <div class="user-pill">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->nama ?? 'AD', 0, 2)) }}</div>
            <div>
                <div class="fw-bold">{{ Auth::user()->nama ?? 'Admin' }}</div>
                <small class="text-muted">Super admin</small>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('adminSearchInput');
    const searchDropdown = document.getElementById('adminSearchDropdown');
    const searchContent = document.getElementById('adminSearchDropdownContent');
    const searchForm = document.getElementById('adminSearchForm');

    if (!searchInput || !searchDropdown || !searchContent) return;

    let debounceTimer = null;

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 2) {
            searchDropdown.classList.add('d-none');
            searchContent.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('admin.search.suggest') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    const hasEvents = data.events && data.events.length > 0;
                    const hasClubs = data.clubs && data.clubs.length > 0;
                    const hasMarketplaces = data.marketplaces && data.marketplaces.length > 0;
                    const hasProducts = data.products && data.products.length > 0;
                    const hasUsers = data.users && data.users.length > 0;

                    if (!hasEvents && !hasClubs && !hasMarketplaces && !hasProducts && !hasUsers) {
                        html = `
                            <div class="p-2 text-center text-muted small">
                                <i class="bi bi-search me-1"></i> Tekan <strong>Enter</strong> untuk mencari "<em>${query}</em>" di panel Admin
                            </div>
                        `;
                    } else {
                        if (hasEvents) {
                            html += `<div class="dropdown-header text-uppercase text-primary fw-bold px-2 py-1 small"><i class="bi bi-calendar-event me-1"></i> Event (Admin)</div>`;
                            data.events.forEach(e => {
                                html += `
                                    <a href="${e.url}" class="dropdown-item d-flex align-items-center py-2 px-2 rounded">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-semibold text-dark text-truncate small">${e.title}</div>
                                            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">${e.sub}</div>
                                        </div>
                                    </a>
                                `;
                            });
                        }

                        if (hasClubs) {
                            html += `<div class="dropdown-header text-uppercase text-primary fw-bold px-2 py-1 small mt-2"><i class="bi bi-shield-shaded me-1"></i> Club (Admin)</div>`;
                            data.clubs.forEach(c => {
                                html += `
                                    <a href="${c.url}" class="dropdown-item d-flex align-items-center py-2 px-2 rounded">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-semibold text-dark text-truncate small">${c.title}</div>
                                            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">${c.sub}</div>
                                        </div>
                                    </a>
                                `;
                            });
                        }

                        if (hasMarketplaces) {
                            html += `<div class="dropdown-header text-uppercase text-primary fw-bold px-2 py-1 small mt-2"><i class="bi bi-shop me-1"></i> Toko Marketplace (Admin)</div>`;
                            data.marketplaces.forEach(m => {
                                html += `
                                    <a href="${m.url}" class="dropdown-item d-flex align-items-center py-2 px-2 rounded">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-semibold text-dark text-truncate small">${m.title}</div>
                                            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">${m.sub}</div>
                                        </div>
                                    </a>
                                `;
                            });
                        }

                        if (hasProducts) {
                            html += `<div class="dropdown-header text-uppercase text-primary fw-bold px-2 py-1 small mt-2"><i class="bi bi-box-seam me-1"></i> Produk (Admin)</div>`;
                            data.products.forEach(p => {
                                html += `
                                    <a href="${p.url}" class="dropdown-item d-flex align-items-center py-2 px-2 rounded">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-semibold text-dark text-truncate small">${p.title}</div>
                                            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">${p.sub}</div>
                                        </div>
                                    </a>
                                `;
                            });
                        }

                        if (hasUsers) {
                            html += `<div class="dropdown-header text-uppercase text-primary fw-bold px-2 py-1 small mt-2"><i class="bi bi-people me-1"></i> Pendaftar / User (Admin)</div>`;
                            data.users.forEach(u => {
                                html += `
                                    <a href="${u.url}" class="dropdown-item d-flex align-items-center py-2 px-2 rounded">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-semibold text-dark text-truncate small">${u.title}</div>
                                            <div class="text-muted small text-truncate" style="font-size: 0.75rem;">${u.sub}</div>
                                        </div>
                                    </a>
                                `;
                            });
                        }

                        html += `
                            <hr class="dropdown-divider my-1">
                            <button type="submit" class="dropdown-item text-center text-primary fw-semibold py-2 small rounded">
                                <i class="bi bi-arrow-return-right me-1"></i> Tekan Enter untuk cari di halaman Admin
                            </button>
                        `;
                    }

                    searchContent.innerHTML = html;
                    searchDropdown.classList.remove('d-none');
                })
                .catch(() => {
                    searchDropdown.classList.add('d-none');
                });
        }, 200);
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.add('d-none');
        }
    });

    searchInput.addEventListener('focus', function () {
        if (searchContent.innerHTML.trim() !== '' && this.value.trim().length >= 2) {
            searchDropdown.classList.remove('d-none');
        }
    });
});
</script>
