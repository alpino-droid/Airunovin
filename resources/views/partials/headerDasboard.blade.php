<nav class="navbar" style="background-color: #f8f9fa; position: sticky; top: 0; z-index: 1000; padding: 8px 0;">
    <div class="container-fluid d-flex align-items-center">
        
        <div class="d-flex align-items-center gap-2">
            <a class="nav-link" href="{{ route('home') }}" style="color: #333; font-weight: 500; padding: 8px 12px;">Home</a>
            <a class="nav-link" href="{{ route('club') }}" style="color: #333; font-weight: 500; padding: 8px 12px;">Club</a>
            <a class="nav-link" href="{{ route('event') }}" style="color: #333; font-weight: 500; padding: 8px 12px;">Event</a>
            <a class="nav-link" href="{{ route('marketplace') }}" style="color: #333; font-weight: 500; padding: 8px 12px;">Marketplace</a>
        </div>

        <div class="flex-grow-1"></div>

        <ul class="nav" style="margin-left: auto;">
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
                {{-- DROPDOWN MENU - SESUAI STATUS LOGIN      --}}
                {{-- ========================================== --}}
                <ul class="dropdown-menu dropdown-menu-end" 
                    style="min-width: 180px; padding: 8px 0; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: none;">
                    
                    @auth
                        {{-- ✅ MENU UNTUK USER LOGIN --}}
                        
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
                            <i class="fas fa-tachometer-alt"></i> {{ __('Profile') }}
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('profil') }}" style="padding: 8px 20px;">
                            <i class="fas fa-user-circle"></i> {{ __('Edit Profile') }}
                        </a></li>
                        <li class="dropdown dropend">
                            <button class="dropdown-item dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 8px 20px;">
                                <i class="fas fa-language"></i> {{ app()->getLocale() === 'id' ? 'ID' : 'EN' }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item {{ app()->getLocale() === 'id' ? 'active' : '' }}" href="{{ route('language.switch', ['locale' => 'id']) }}">{{ __('Indonesian') }}</a></li>
                                <li><a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('language.switch', ['locale' => 'en']) }}">{{ __('English') }}</a></li>
                            </ul>
                        </li>
                        <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item" style="padding: 8px 20px; color: #dc3545; border: none; background: none; width: 100%; text-align: left;">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                        
                    @else
                        {{-- ✅ MENU UNTUK GUEST (BELUM LOGIN) --}}
                        
                        <li><a class="dropdown-item" href="{{ route('login') }}" style="padding: 8px 20px;">
                            <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}" style="padding: 8px 20px;">
                            <i class="fas fa-user-plus"></i> {{ __('Register') }}
                        </a></li>
                        <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}" style="padding: 8px 20px;">
                            <i class="fas fa-home"></i> {{ __('Home') }}
                        </a></li>
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
                            <a class="nav-link active" href="{{ route('dashboard') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="fas fa-crown"></i> {{ __('Your Profile') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboardClub') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="fas fa-crown"></i> {{ __('Profile Club') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboardMarketplace') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="fas fa-store-alt"></i> {{ __('Marketplace Dashboard') }}
                            </a>
                        </li>
                        <li><hr style="margin: 8px 16px;"></li>
                        
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link" style="padding: 12px 24px; font-weight: 500; color: #dc3545; border: none; background: none; width: 100%; text-align: left;">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                        
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}" style="padding: 12px 24px; font-weight: 500;">
                                <i class="fas fa-user-plus"></i> {{ __('Register') }}
                            </a>
                        </li>
                        <li><hr style="margin: 8px 16px;"></li>
                    @endauth
                    
                    <li><hr style="margin: 8px 16px;"></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}" style="padding: 12px 24px; font-weight: 500;">
                            <i class="fas fa-home"></i> {{ __('Home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('club') }}" style="padding: 12px 24px; font-weight: 500;">
                            <i class="fas fa-users"></i> {{ __('Club') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('event') }}" style="padding: 12px 24px; font-weight: 500;">
                            <i class="fas fa-calendar"></i> {{ __('Event') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="padding: 12px 24px; font-weight: 500;">
                            <i class="fas fa-bolt"></i> {{ __('Active') }}
                        </a>
                    </li>
            </ul>
        </aside>

    </div>
</nav>