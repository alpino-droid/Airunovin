<footer class="app-footer">
    <div class="container py-5">
        <div class="row gy-4 align-items-start">
            <div class="col-lg-5">
                <a href="{{ route('home') }}" class="app-footer-brand">
                    <img src="{{ asset('icon/logo.png') }}" alt="Airunovin" width="150">
                </a>
                <p class="app-footer-copy mt-3 mb-0">{{ __('Find the best experience in airsoft with an active and supportive community.') }}</p>
            </div>
            <div class="col-6 col-lg-3">
                <h2 class="app-footer-title">{{ __('Explore') }}</h2>
                <a href="{{ route('event') }}" class="app-footer-link">{{ __('Event') }}</a>
                <a href="{{ route('club') }}" class="app-footer-link">{{ __('Club') }}</a>
                <a href="{{ route('marketplace') }}" class="app-footer-link">{{ __('Marketplace') }}</a>
                <a href="{{ route('about') }}" class="app-footer-link">Tentang Kami</a>
                <a href="{{ route('privacy') }}" class="app-footer-link">Kebijakan Privasi</a>
            </div>
            <div class="col-6 col-lg-4">
                <h2 class="app-footer-title">{{ __('Stay connected') }}</h2>
                <p class="app-footer-copy">{{ __('Build connections, expand your knowledge, and enjoy being part of the airsoft community.') }}</p>
                <div class="app-footer-socials" aria-label="Social media">
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="app-footer-bottom mt-5 pt-3">
            <span>&copy; {{ date('Y') }} Airunovin</span>
            <span>{{ __('All rights reserved.') }}</span>
        </div>
    </div>
</footer>