<nav class="navbar" style="background-color: #f8f9fa; position: sticky; top: 0; z-index: 1000; padding: 8px 0;">
    <div class="container-fluid d-flex align-items-center">
        
        <div class="d-flex align-items-center gap-2">
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" style="color: #333; font-weight: 500; padding: 8px 12px;">{{ __('Home') }}</a>
            <a class="nav-link {{ request()->routeIs('club*') ? 'active' : '' }}" href="{{ route('club') }}" style="color: #333; font-weight: 500; padding: 8px 12px;">{{ __('Club') }}</a>
            <a class="nav-link {{ request()->routeIs('event*') ? 'active' : '' }}" href="{{ route('event') }}" style="color: #333; font-weight: 500; padding: 8px 12px;">{{ __('Event') }}</a>
            <a class="nav-link {{ request()->routeIs('marketplace*') ? 'active' : '' }}" href="{{ route('marketplace') }}" style="color: #333; font-weight: 500; padding: 8px 12px;">{{ __('Marketplace') }}</a>
        </div>

        <div class="flex-grow-1"></div>

        <ul class="nav align-items-center" style="margin-left: auto;">
            {{-- Dropdown Ubah Bahasa di Top Navbar --}}
            <li class="nav-item dropdown me-2">
                <button class="btn btn-link text-dark dropdown-toggle d-flex align-items-center gap-1" 
                        type="button" 
                        id="dropdownLanguageDashboard" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="padding: 6px 10px; text-decoration: none; color: #333; border: none; font-size: 14px; font-weight: 500;">
                    <i class="bi bi-translate"></i>
                    <span>{{ app()->getLocale() === 'id' ? 'ID' : 'EN' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" 
                    aria-labelledby="dropdownLanguageDashboard" 
                    style="min-width: 160px; border-radius: 8px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <li>
                        <a class="dropdown-item d-flex align-items-center justify-content-between {{ app()->getLocale() === 'id' ? 'active' : '' }}" 
                           href="{{ route('language.switch', ['locale' => 'id']) }}">
                            <span>{{ __('Indonesian') }}</span>
                            @if(app()->getLocale() === 'id') <i class="bi bi-check2 ms-2"></i> @endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center justify-content-between {{ app()->getLocale() === 'en' ? 'active' : '' }}" 
                           href="{{ route('language.switch', ['locale' => 'en']) }}">
                            <span>{{ __('English') }}</span>
                            @if(app()->getLocale() === 'en') <i class="bi bi-check2 ms-2"></i> @endif
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Dropdown Profil --}}
            <li class="nav-item dropdown">
                <button class="btn" 
                        type="button" 
                        id="dropdownProfile" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="border: none; background: transparent; padding: 0;">
                    
                    {{-- FOTO PROFIL DARI DATABASE --}}
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
                
                {{-- DROPDOWN MENU - SESUAI STATUS LOGIN --}}
                <ul class="dropdown-menu dropdown-menu-end" 
                    style="min-width: 190px; padding: 8px 0; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: none;">
                    
                    @auth
                        {{-- MENU UNTUK USER LOGIN --}}
                        
                        {{-- Foto + Nama + Email --}}
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
                        
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}" style="padding: 8px 20px;">
                            <i class="bi bi-speedometer2 me-1"></i> {{ __('Profile') }}
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('profil') }}" style="padding: 8px 20px;">
                            <i class="bi bi-person-circle me-1"></i> {{ __('Edit Profile') }}
                        </a></li>

                        {{-- Ubah Bahasa (Langsung bisa dipilih tanpa subdropdown tertutup) --}}
                        <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                        <li style="padding: 6px 20px 2px 20px;">
                            <div style="font-size: 11px; font-weight: 600; color: #888; text-transform: uppercase;">
                                <i class="bi bi-translate me-1"></i> {{ __('Language') }}
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between {{ app()->getLocale() === 'id' ? 'active' : '' }}" 
                               href="{{ route('language.switch', ['locale' => 'id']) }}" 
                               style="padding: 6px 20px;">
                                <span>{{ __('Indonesian') }}</span>
                                @if(app()->getLocale() === 'id') <i class="bi bi-check2"></i> @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between {{ app()->getLocale() === 'en' ? 'active' : '' }}" 
                               href="{{ route('language.switch', ['locale' => 'en']) }}" 
                               style="padding: 6px 20px;">
                                <span>{{ __('English') }}</span>
                                @if(app()->getLocale() === 'en') <i class="bi bi-check2"></i> @endif
                            </a>
                        </li>

                        <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item" style="padding: 8px 20px; color: #dc3545; border: none; background: none; width: 100%; text-align: left;">
                                    <i class="bi bi-box-arrow-right me-1"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                        
                    @else
                        {{-- MENU UNTUK GUEST (BELUM LOGIN) --}}
                        
                        <li><a class="dropdown-item" href="{{ route('login') }}" style="padding: 8px 20px;">
                            <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('Login') }}
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}" style="padding: 8px 20px;">
                            <i class="bi bi-person-plus me-1"></i> {{ __('Register') }}
                        </a></li>
                        <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}" style="padding: 8px 20px;">
                            <i class="bi bi-house me-1"></i> {{ __('Home') }}
                        </a></li>

                        {{-- Ubah Bahasa untuk Guest --}}
                        <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                        <li style="padding: 6px 20px 2px 20px;">
                            <div style="font-size: 11px; font-weight: 600; color: #888; text-transform: uppercase;">
                                <i class="bi bi-translate me-1"></i> {{ __('Language') }}
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between {{ app()->getLocale() === 'id' ? 'active' : '' }}" 
                               href="{{ route('language.switch', ['locale' => 'id']) }}" 
                               style="padding: 6px 20px;">
                                <span>{{ __('Indonesian') }}</span>
                                @if(app()->getLocale() === 'id') <i class="bi bi-check2"></i> @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between {{ app()->getLocale() === 'en' ? 'active' : '' }}" 
                               href="{{ route('language.switch', ['locale' => 'en']) }}" 
                               style="padding: 6px 20px;">
                                <span>{{ __('English') }}</span>
                                @if(app()->getLocale() === 'en') <i class="bi bi-check2"></i> @endif
                            </a>
                        </li>
                    @endauth
                </ul>
            </li>
        </ul>

        <aside class="dashboard-sidebar">
            <div class="dashboard-sidebar__brand">
                <img src="{{ asset('icon/logo.png') }}" alt="Logo" width="150">
            </div>
            <ul class="navbar-nav dashboard-sidebar__menu">
                    
                    @auth
                        {{-- Info User --}}
                        <li style="padding: 12px 24px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 12px;">
                            <img src="{{ Auth::user()->profile_picture_url }}" 
                                 alt="Profile" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;"
                                 onerror="this.src='{{ asset('img/blankPhotoProfile.png') }}'">
                            <div>
                                <div style="font-weight: bold; font-size: 16px;">{{ Auth::user()->nama }}</div>
                                <div style="font-size: 13px; color: #888;">{{ Auth::user()->email }}</div>
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="bi bi-person me-2"></i> {{ __('Your Profile') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboardClub*') ? 'active' : '' }}" href="{{ route('dashboardClub') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="bi bi-people me-2"></i> {{ __('Profile Club') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboardMarketplace*') ? 'active' : '' }}" href="{{ route('dashboardMarketplace') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="bi bi-shop me-2"></i> {{ __('Marketplace Dashboard') }}
                            </a>
                        </li>
                        <li><hr style="margin: 8px 16px;"></li>
                        
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link" style="padding: 12px 24px; font-weight: 500; color: #dc3545; border: none; background: none; width: 100%; text-align: left;">
                                    <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                        
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="bi bi-box-arrow-in-right me-2"></i> {{ __('Login') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="bi bi-person-plus me-2"></i> {{ __('Register') }}
                            </a>
                        </li>
                        <li><hr style="margin: 8px 16px;"></li>
                    @endauth
                    
                    <li><hr style="margin: 8px 16px;"></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" style="padding: 12px 24px; font-weight: 500;">
                            <i class="bi bi-house me-2"></i> {{ __('Home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('club*') ? 'active' : '' }}" href="{{ route('club') }}" style="padding: 12px 24px; font-weight: 500;">
                            <i class="bi bi-people me-2"></i> {{ __('Club') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('event*') ? 'active' : '' }}" href="{{ route('event') }}" style="padding: 12px 24px; font-weight: 500;">
                            <i class="bi bi-calendar-event me-2"></i> {{ __('Event') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('marketplace*') ? 'active' : '' }}" href="{{ route('marketplace') }}" style="padding: 12px 24px; font-weight: 500;">
                            <i class="bi bi-shop me-2"></i> {{ __('Marketplace') }}
                        </a>
                    </li>

                    {{-- Switcher Bahasa di Sidebar --}}
                    <li><hr style="margin: 8px 16px;"></li>
                    <li class="nav-item" style="padding: 8px 24px 16px 24px;">
                        <div class="d-flex align-items-center justify-content-between">
                            <span style="font-size: 13px; font-weight: 500; color: #666;">
                                <i class="bi bi-translate me-1"></i> {{ __('Language') }}
                            </span>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Language switcher">
                                <a href="{{ route('language.switch', ['locale' => 'id']) }}" 
                                   class="btn btn-sm {{ app()->getLocale() === 'id' ? 'btn-danger text-white' : 'btn-outline-secondary' }}" 
                                   style="padding: 2px 8px; font-size: 11px; font-weight: 600;">ID</a>
                                <a href="{{ route('language.switch', ['locale' => 'en']) }}" 
                                   class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-danger text-white' : 'btn-outline-secondary' }}" 
                                   style="padding: 2px 8px; font-size: 11px; font-weight: 600;">EN</a>
                            </div>
                        </div>
                    </li>
            </ul>
        </aside>

    </div>
</nav>