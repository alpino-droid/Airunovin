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