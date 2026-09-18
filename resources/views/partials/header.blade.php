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
            {{-- Input Pencarian dengan Ikon SVG --}}
            <li class="nav-item position-relative me-2 app-navbar-search">
                <input 
                    class="form-control form-control-sm" 
                    type="text" 
                    placeholder="{{ __('Search') }}" 
                    aria-label="{{ __('Search') }}"
                    style="padding-left: 35px; width: 200px;"
                >
                {{-- SVG dari public/icon --}}
                <img src="{{ asset('icon/material-symbols-light--search.svg') }}" 
                     alt="Search" 
                     style="position: absolute; 
                            left: 10px; 
                            top: 50%; 
                            transform: translateY(-50%); 
                            width: 18px; 
                            height: 18px; 
                            pointer-events: none;
                            opacity: 0.6;">
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
                $headerUnreadCount = \App\Models\Notification::unread()->count();
                $headerNotifications = \App\Models\Notification::orderBy('created_at', 'desc')->take(5)->get();
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