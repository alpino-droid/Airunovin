<nav class="navbar navbar-expand-lg warna-nav app-navbar sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand app-navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('icon/logo.png') }}" 
                alt="Logo" 
                width="150"  
                class="d-inline-block align-text-top">
        </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbarMenu" aria-controls="mainNavbarMenu" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="mainNavbarMenu">
            <ul class="nav align-items-center ms-auto">
            {{-- Input Pencarian dengan Ikon SVG & Mesin Pencari Pintar --}}
            <li class="nav-item position-relative me-2 app-navbar-search">
                <form action="{{ route('global.search') }}" method="GET" class="m-0 p-0 position-relative" id="globalSearchForm" role="search">
                    <input 
                        class="form-control form-control-sm" 
                        type="search" 
                        name="q"
                        id="globalSearchInput"
                        placeholder="{{ __('Cari event, club, produk...') }}" 
                        aria-label="{{ __('Search') }}"
                        value="{{ request('search') ?? request('q') }}"
                        style="padding-left: 35px; padding-right: 32px; width: 230px;"
                        autocomplete="off"
                        role="combobox"
                        aria-autocomplete="list"
                        aria-expanded="false"
                        aria-controls="globalSearchDropdown"
                    >
                    <button type="submit" class="btn p-0 border-0 bg-transparent" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; line-height: 1; z-index: 5;" aria-label="Submit Search">
                        <img src="{{ asset('icon/material-symbols-light--search.svg') }}" 
                             alt="Search" 
                             style="width: 18px; height: 18px; opacity: 0.6;">
                    </button>
                    {{-- AJAX Loading Spinner --}}
                    <div id="globalSearchSpinner" class="spinner-border spinner-border-sm text-secondary d-none position-absolute" style="right: 10px; top: calc(50% - 7px); width: 14px; height: 14px; border-width: 2px; z-index: 5;" role="status" aria-hidden="true">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    {{-- Live Autocomplete Dropdown Preview --}}
                    <div id="globalSearchDropdown" class="dropdown-menu shadow-lg p-2 d-none position-absolute" style="width: 320px; max-width: calc(100vw - 2rem); right: 0; left: auto; top: calc(100% + 6px); max-height: 420px; overflow-y: auto; z-index: 1060; border-radius: 12px;" role="listbox">
                        <div id="globalSearchDropdownContent"></div>
                    </div>
                </form>
            </li>
            
            {{-- NAVIGASI MENU --}}
            <li class="nav-item app-navbar-item">
                <a class="nav-link main-nav-link" aria-current="page" href="{{ route('home') }}">{{ __('Home') }}</a>
            </li>
            <li class="nav-item app-navbar-item">
                <a class="nav-link main-nav-link" aria-current="page" href="{{ route('club') }}">{{ __('Club') }}</a>
            </li>
            <li class="nav-item app-navbar-item">
                <a class="nav-link main-nav-link" aria-current="page" href="{{ route('event') }}">{{ __('Event') }}</a>
            </li>
            <li class="nav-item app-navbar-item">
                <a class="nav-link main-nav-link" aria-current="page" href="{{ route('marketplace') }}">{{ __('Marketplace') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled main-nav-separator" aria-disabled="true">|</a>
            </li>

            @php
                $headerUnreadCount = \App\Models\Notification::where('id_user', auth()->id())->unread()->count();
                $headerNotifications = \App\Models\Notification::where('id_user', auth()->id())->orderBy('created_at', 'desc')->take(5)->get();
            @endphp
            <li class="nav-item dropdown me-2">
                <button class="btn btn-link position-relative text-dark dropdown-toggle" 
                        type="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="padding: 0 10px; text-decoration: none; color: #333; border: none;"
                        aria-label="Pusat Notifikasi">
                    <i class="bi bi-bell-fill fs-5"></i>
                    @if($headerUnreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $headerUnreadCount > 99 ? '99+' : $headerUnreadCount }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end notification-menu p-2" aria-labelledby="notificationDropdown" style="min-width: 280px; max-width: 320px;">
                    <li class="dropdown-header d-flex justify-content-between align-items-center px-2 pb-2">
                        <span class="fw-semibold">Notifikasi</span>
                        @if($headerUnreadCount > 0)
                            <span class="badge bg-primary rounded-pill">{{ $headerUnreadCount }} baru</span>
                        @else
                            <span class="badge bg-light text-muted border rounded-pill">0 baru</span>
                        @endif
                    </li>
                    @forelse($headerNotifications as $hNotif)
                        <li>
                            <a href="{{ route('notifications.detail', $hNotif->id) }}" class="text-decoration-none">
                                <div class="notification-item {{ $hNotif->isUnread() ? 'unread' : '' }} p-2 rounded">
                                    <div class="d-flex align-items-start gap-2">
                                        <span class="notification-dot {{ $hNotif->isUnread() ? '' : 'notification-dot--muted' }}" style="margin-top: 6px;"></span>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-semibold text-dark small text-truncate">
                                                {{ $hNotif->title }}
                                            </div>
                                            <div class="small text-muted text-truncate">
                                                {{ $hNotif->message }}
                                            </div>
                                            <div class="small text-primary mt-1" style="font-size: 0.75rem;">
                                                {{ $hNotif->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-3 text-center text-muted small">
                            <i class="bi bi-bell-slash d-block mb-1"></i>
                            Belum ada notifikasi
                        </li>
                    @endforelse
                    <li><hr class="dropdown-divider my-2"></li>
                    <li class="text-center">
                        <a class="dropdown-item text-primary fw-semibold small" href="{{ route('notifications') }}">Lihat semua notifikasi</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <button class="btn btn-link text-dark dropdown-toggle" 
                        type="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="padding: 0 10px; text-decoration: none; color: #333; border: none;">
                    <i class="fas fa-language"></i> {{ app()->getLocale() === 'id' ? 'ID' : 'EN' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item {{ app()->getLocale() === 'id' ? 'active' : '' }}" href="{{ route('language.switch', ['locale' => 'id']) }}">{{ __('Indonesian') }}</a></li>
                    <li><a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('language.switch', ['locale' => 'en']) }}">{{ __('English') }}</a></li>
                </ul>
            </li>

        </ul>
        </div>

        <ul class="nav align-items-center ms-lg-2">
            {{-- ========================================== --}}
            {{-- DROPDOWN PROFILE (AUTH)                    --}}
            {{-- ========================================== --}}
            <li class="nav-item dropdown">
                <button class="btn" 
                        type="button" 
                        id="dropdownProfile" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="border: none; background: transparent; padding: 0;">
                    
                    {{-- ✅ FOTO PROFIL DARI DATABASE --}}
                    @auth
                        <img src="{{ Auth::user()->profile_picture_url }}" 
                             alt="Profile" 
                             class="photoProfile_size"
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;"
                             onerror="this.src='{{ asset('img/blankPhotoProfile.png') }}'">
                    @else
                        <img src="{{ asset('img/blankPhotoProfile.png') }}" 
                             alt="Profile" 
                             class="photoProfile_size"
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                    @endauth
                </button>
                
                {{-- ========================================== --}}
                {{-- MENU DROPDOWN - SESUAI STATUS LOGIN      --}}
                {{-- ========================================== --}}
                <ul class="dropdown-menu dropdown-menu-end" 
                    style="min-width: 180px; padding: 8px 0; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: none;" 
                    aria-labelledby="dropdownProfile">
                    
                    {{-- ✅ CEK STATUS LOGIN --}}
                    @auth
                        {{-- MENU UNTUK USER YANG SUDAH LOGIN --}}
                        
                        {{-- Foto + Nama User --}}
                        <li style="padding: 8px 20px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #eee;">
                            <img src="{{ Auth::user()->profile_picture_url }}" 
                                 alt="Profile" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;"
                                 onerror="this.src='{{ asset('img/blankPhotoProfile.png') }}'">
                            <div>
                                <div style="font-weight: bold; color: #333;">{{ Auth::user()->nama }}</div>
                                <div style="font-size: 12px; color: #888;">{{ Auth::user()->email }}</div>
                            </div>
                        </li>
                        
                        {{-- Menu Profil --}}
                        <li>
                            <a class="dropdown-item" href="{{ route('profil') }}" style="padding: 8px 20px;">
                                <i class="fas fa-user-circle"></i> {{ __('My Profile') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}" style="padding: 8px 20px;">
                                <i class="fas fa-tachometer-alt"></i> {{ __('Dashboard') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboardMarketplace') }}" style="padding: 8px 20px;">
                                <i class="fas fa-store-alt"></i> {{ __('Marketplace Dashboard') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboardClub') }}" style="padding: 8px 20px;">
                                <i class="fas fa-crown"></i> {{ __('Club Dashboard') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('event.create') }}" style="padding: 8px 20px;">
                                <i class="fas fa-plus-circle"></i> {{ __('Create Event') }}
                            </a>
                        </li>
                        
                        <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                        
                        {{-- ✅ TOMBOL LOGOUT (menggunakan form POST) --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item" style="padding: 8px 20px; color: #dc3545; border: none; background: none; width: 100%; text-align: left;">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                        
                    @else
                        {{-- MENU UNTUK GUEST (BELUM LOGIN) --}}
                        
                        <li>
                            <a class="dropdown-item" href="{{ route('login') }}" style="padding: 8px 20px;">
                                <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('register') }}" style="padding: 8px 20px;">
                                <i class="fas fa-user-plus"></i> {{ __('Register') }}
                            </a>
                        </li>
                        
                        <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                        
                        
                        <li>
                            <a class="dropdown-item" href="{{ route('home') }}" style="padding: 8px 20px;">
                                <i class="fas fa-home"></i> {{ __('Home') }}
                            </a>
                        </li>
                    @endauth
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('globalSearchInput');
    const searchDropdown = document.getElementById('globalSearchDropdown');
    const searchContent = document.getElementById('globalSearchDropdownContent');
    const searchForm = document.getElementById('globalSearchForm');
    const searchSpinner = document.getElementById('globalSearchSpinner');

    if (!searchInput || !searchDropdown || !searchContent) return;

    let debounceTimer = null;
    let abortController = null;
    let activeIndex = -1;

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function highlightMatch(text, query) {
        if (!text || !query) return escapeHtml(text);
        const safeText = escapeHtml(text);
        const safeQuery = query.trim().replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        if (!safeQuery) return safeText;
        const regex = new RegExp(`(${safeQuery})`, 'gi');
        return safeText.replace(regex, '<mark class="p-0 bg-warning-subtle text-dark fw-bold">$1</mark>');
    }

    function showDropdown() {
        searchDropdown.classList.remove('d-none');
        searchDropdown.classList.add('show');
        searchDropdown.style.display = 'block';
        searchInput.setAttribute('aria-expanded', 'true');
    }

    function hideDropdown() {
        searchDropdown.classList.add('d-none');
        searchDropdown.classList.remove('show');
        searchDropdown.style.display = 'none';
        searchInput.setAttribute('aria-expanded', 'false');
        activeIndex = -1;
        updateActiveItem();
    }

    function setSpinner(show) {
        if (!searchSpinner) return;
        if (show) {
            searchSpinner.classList.remove('d-none');
        } else {
            searchSpinner.classList.add('d-none');
        }
    }

    function getItems() {
        return searchContent.querySelectorAll('.search-suggest-item');
    }

    function updateActiveItem() {
        const items = getItems();
        items.forEach((item, idx) => {
            if (idx === activeIndex) {
                item.classList.add('active', 'bg-light');
                item.setAttribute('aria-selected', 'true');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active', 'bg-light');
                item.removeAttribute('aria-selected');
            }
        });
    }

    function performSearch(query) {
        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        setSpinner(true);

        const url = `{{ route('search.suggest') }}?q=${encodeURIComponent(query)}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            signal: abortController.signal
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            setSpinner(false);
            activeIndex = -1;

            const events = Array.isArray(data.events) ? data.events : [];
            const clubs = Array.isArray(data.clubs) ? data.clubs : [];
            const products = Array.isArray(data.products) ? data.products : [];

            const total = events.length + clubs.length + products.length;

            if (total === 0) {
                searchContent.innerHTML = `
                    <div class="p-3 text-center text-muted small">
                        <i class="bi bi-search d-block mb-1 fs-5 text-secondary"></i>
                        <div>{{ __('Tidak ada saran untuk') }} "<strong>${escapeHtml(query)}</strong>"</div>
                        <div class="mt-2 text-secondary" style="font-size: 0.75rem;">
                            Tekan <kbd class="bg-light text-dark border px-1">Enter</kbd> {{ __('untuk mencari di semua data') }}
                        </div>
                    </div>
                `;
                showDropdown();
                return;
            }

            let html = '';

            // Section Event
            if (events.length > 0) {
                html += `
                    <div class="d-flex align-items-center justify-content-between px-2 pt-1 pb-1 text-uppercase text-secondary fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <span><i class="bi bi-calendar-event me-1 text-primary"></i> Event</span>
                        <span class="badge bg-primary-subtle text-primary rounded-pill">${events.length}</span>
                    </div>
                `;
                events.forEach(e => {
                    html += `
                        <a href="${escapeHtml(e.url)}" class="search-suggest-item dropdown-item d-flex align-items-center gap-2 py-2 px-2 rounded mb-1 text-decoration-none" role="option">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                <i class="bi ${escapeHtml(e.icon || 'bi-calendar-event')}"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-dark text-truncate small">${highlightMatch(e.title, query)}</div>
                                <div class="text-muted text-truncate" style="font-size: 0.75rem;">${escapeHtml(e.sub || '')}</div>
                            </div>
                        </a>
                    `;
                });
            }

            // Section Club
            if (clubs.length > 0) {
                html += `
                    <div class="d-flex align-items-center justify-content-between px-2 pt-2 pb-1 text-uppercase text-secondary fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <span><i class="bi bi-shield-shaded me-1 text-success"></i> Club</span>
                        <span class="badge bg-success-subtle text-success rounded-pill">${clubs.length}</span>
                    </div>
                `;
                clubs.forEach(c => {
                    html += `
                        <a href="${escapeHtml(c.url)}" class="search-suggest-item dropdown-item d-flex align-items-center gap-2 py-2 px-2 rounded mb-1 text-decoration-none" role="option">
                            <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                <i class="bi ${escapeHtml(c.icon || 'bi-shield-shaded')}"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-dark text-truncate small">${highlightMatch(c.title, query)}</div>
                                <div class="text-muted text-truncate" style="font-size: 0.75rem;">${escapeHtml(c.sub || '')}</div>
                            </div>
                        </a>
                    `;
                });
            }

            // Section Produk / Marketplace
            if (products.length > 0) {
                html += `
                    <div class="d-flex align-items-center justify-content-between px-2 pt-2 pb-1 text-uppercase text-secondary fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <span><i class="bi bi-shop me-1 text-warning"></i> Marketplace</span>
                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">${products.length}</span>
                    </div>
                `;
                products.forEach(p => {
                    html += `
                        <a href="${escapeHtml(p.url)}" class="search-suggest-item dropdown-item d-flex align-items-center gap-2 py-2 px-2 rounded mb-1 text-decoration-none" role="option">
                            <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                <i class="bi ${escapeHtml(p.icon || 'bi-box-seam')}"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-dark text-truncate small">${highlightMatch(p.title, query)}</div>
                                <div class="text-muted text-truncate" style="font-size: 0.75rem;">${escapeHtml(p.sub || '')}</div>
                            </div>
                        </a>
                    `;
                });
            }

            // Tombol cari semua di bagian bawah
            html += `
                <hr class="dropdown-divider my-1">
                <button type="button" class="search-submit-btn dropdown-item text-center text-primary fw-semibold py-2 small rounded d-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-arrow-return-right"></i>
                    <span>{{ __('Lihat semua hasil untuk') }} "<em>${escapeHtml(query)}</em>"</span>
                </button>
            `;

            searchContent.innerHTML = html;

            const submitBtn = searchContent.querySelector('.search-submit-btn');
            if (submitBtn) {
                submitBtn.addEventListener('click', function () {
                    searchForm.submit();
                });
            }

            showDropdown();
        })
        .catch(err => {
            if (err.name === 'AbortError') return;
            setSpinner(false);
            console.error('AJAX search error:', err);
        });
    }

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 2) {
            if (abortController) abortController.abort();
            setSpinner(false);
            hideDropdown();
            searchContent.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            performSearch(query);
        }, 250);
    });

    searchInput.addEventListener('keydown', function (e) {
        const items = getItems();
        const isOpen = !searchDropdown.classList.contains('d-none');

        if (e.key === 'ArrowDown') {
            if (!isOpen && searchContent.innerHTML.trim() !== '') {
                showDropdown();
                return;
            }
            if (items.length > 0) {
                e.preventDefault();
                activeIndex = (activeIndex + 1) % items.length;
                updateActiveItem();
            }
        } else if (e.key === 'ArrowUp') {
            if (items.length > 0 && isOpen) {
                e.preventDefault();
                activeIndex = (activeIndex - 1 + items.length) % items.length;
                updateActiveItem();
            }
        } else if (e.key === 'Enter') {
            if (isOpen && activeIndex >= 0 && items[activeIndex]) {
                e.preventDefault();
                items[activeIndex].click();
            }
        } else if (e.key === 'Escape') {
            if (isOpen) {
                e.preventDefault();
                hideDropdown();
            }
        }
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            hideDropdown();
        }
    });

    searchInput.addEventListener('focus', function () {
        if (searchContent.innerHTML.trim() !== '' && this.value.trim().length >= 2) {
            showDropdown();
        }
    });
});
</script>