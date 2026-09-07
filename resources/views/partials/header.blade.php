<nav class="navbar warna-nav app-navbar sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand app-navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('icon/logo.png') }}" 
                alt="Logo" 
                width="150"  
                class="d-inline-block align-text-top">
        </a>
        
        <ul class="nav align-items-center">
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

            <li class="nav-item dropdown me-2">
                <button class="btn btn-link position-relative text-dark dropdown-toggle" 
                        type="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="padding: 0 10px; text-decoration: none; color: #333; border: none;">
                    <i class="bi bi-bell-fill fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end notification-menu p-2" aria-labelledby="notificationDropdown">
                    <li class="dropdown-header d-flex justify-content-between align-items-center px-2 pb-2">
                        <span class="fw-semibold">Notifikasi</span>
                        <span class="badge bg-primary rounded-pill">3 baru</span>
                    </li>
                    <li>
                        <div class="notification-item unread">
                            <div class="d-flex align-items-start gap-2">
                                <span class="notification-dot"></span>
                                <div>
                                    <div class="fw-semibold">Pembelian baru</div>
                                    <div class="small text-muted">Produk "Rantai Matic" berhasil dibeli oleh pelanggan.</div>
                                    <div class="small text-primary mt-1">2 menit lalu</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="notification-item unread">
                            <div class="d-flex align-items-start gap-2">
                                <span class="notification-dot"></span>
                                <div>
                                    <div class="fw-semibold">Pesanan dikirim</div>
                                    <div class="small text-muted">Status pengiriman untuk order #MK-2048 sudah diperbarui.</div>
                                    <div class="small text-primary mt-1">1 jam lalu</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="notification-item">
                            <div class="d-flex align-items-start gap-2">
                                <span class="notification-dot notification-dot--muted"></span>
                                <div>
                                    <div class="fw-semibold">Review pelanggan</div>
                                    <div class="small text-muted">Ada ulasan baru dari pembeli atas produk Anda.</div>
                                    <div class="small text-primary mt-1">1 hari lalu</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li class="text-center">
                        <a class="dropdown-item text-primary fw-semibold" href="{{ route('notifications') }}">Lihat semua</a>
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

            {{-- ========================================== --}}
            {{-- DROPDOWN PROFILE (AUTH)                    --}}
            {{-- ========================================== --}}
            <li class="nav-item dropdown">
                <button class="btn warna-nav" 
                        type="button" 
                        id="dropdownProfile" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="padding: 0; border: none;">
                    
                    {{-- ✅ FOTO PROFIL DARI DATABASE --}}
                    @auth
                        {{-- Jika user login, tampilkan foto dari database --}}
                        <img src="{{ Auth::user()->profile_picture_url }}" 
                             alt="Profile" 
                             class="photoProfile_size"
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;"
                             onerror="this.src='{{ asset('img/blankPhotoProfile.png') }}'">
                    @else
                        {{-- Jika belum login, tampilkan gambar default --}}
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