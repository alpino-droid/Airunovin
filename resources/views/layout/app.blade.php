<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Laravel Blade App')</title>

    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    
    <!-- 1. BOOTSTRAP CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- 2. CSS KUSTOM (harus di bawah Bootstrap) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- 3. CSS Tambahan per halaman -->
    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('partials.header')
    

    <main class="warna-bg-mainContent" style="min-height: 100vh;">
        @yield('content')
    </main>

    
    <!-- Footer -->
    @include('partials.footer')
    

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- 2. JS KUSTOM -->
    <script src="{{ asset('js/custom.js') }}"></script>
    
    <!-- 3. Script Tambahan per halaman -->
    @stack('scripts')
</body>
</html>