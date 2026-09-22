@extends('layout/app')

@section('title', ($marketplace->nama ?? 'Toko Airsoft') . ' - Market')

@section('content')

@php
    $storeName = $marketplace->nama ?? 'Airsoft Store';
    $words = preg_split('/\s+/', trim($storeName));
    $initials = count($words) >= 2
        ? strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1))
        : strtoupper(mb_substr($storeName, 0, 2));
    
    $ownerName = $marketplace->user->nama ?? 'Penjual Terverifikasi';
    $storePhone = $marketplace->user->phone ?? null;
    $cleanPhone = $storePhone ? preg_replace('/[^0-9]/', '', $storePhone) : null;
    if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
        $cleanPhone = '62' . substr($cleanPhone, 1);
    }
    $storeLocation = $marketplace->user->city ?? $marketplace->user->province ?? ($products->first()?->lokasi ?? 'Indonesia');
@endphp

{{-- ==================== FLASH NOTIFIKASI ==================== --}}
<div class="container pt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

{{-- ==================== HEADER PROFILE TOKO + FILTER ==================== --}}
<div class="container text-start pt-2">
    <div class="row align-items-center g-3 flex-wrap">

        {{-- Avatar + Info Profil Toko --}}
        <div class="col-12 col-lg-auto">
            <div class="d-flex align-items-center gap-3">
                @if($marketplace && $marketplace->logo)
                    <img src="{{ asset('storage/' . $marketplace->logo) }}" 
                         alt="{{ $storeName }}"
                         class="rounded-circle object-fit-cover shadow-sm flex-shrink-0 border"
                         style="width: 90px; height: 90px;"
                         onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                @else
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0 shadow-sm"
                        style="width: 90px; height: 90px; font-size: 1.75rem;">
                        {{ $initials ?: 'AS' }}
                    </div>
                @endif

                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h2 class="fw-bold mb-0 text-uppercase lh-1 fs-3">{{ $storeName }}</h2>
                        @if(($marketplace->status ?? 'active') === 'active')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                                <i class="bi bi-patch-check-fill me-1"></i>Toko Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary px-2 py-1 small">Nonaktif</span>
                        @endif
                    </div>

                    <div class="text-muted small mt-1">
                        <i class="bi bi-person me-1"></i>{{ $ownerName }}
                        <span class="mx-2">&bull;</span>
                        <i class="bi bi-geo-alt me-1"></i>{{ $storeLocation }}
                        <span class="mx-2">&bull;</span>
                        <i class="bi bi-box-seam me-1"></i>{{ $totalProductsCount }} Produk
                    </div>

                    <div class="text-muted small mt-1 text-truncate" style="max-width: 480px;" title="{{ $marketplace->deskripsi ?? 'Pusat jual beli unit airsoft dan perlengkapan terlengkap.' }}">
                        {{ $marketplace->deskripsi ?? 'Pusat jual beli unit airsoft, perlengkapan, dan sparepart berkualitas.' }}
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-2">
                        @if($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo ' . $storeName . ', saya melihat katalog produk Anda di Airunovin Marketplace.') }}" 
                               target="_blank" rel="noopener noreferrer"
                               class="btn btn-outline-success btn-sm py-1 px-2 d-inline-flex align-items-center gap-1">
                                <i class="bi bi-whatsapp"></i> Chat Penjual
                            </a>
                        @endif

                        {{-- Link ke Dashboard jika toko milik user yang sedang login --}}
                        @if($isOwner)
                            <a href="{{ route('dashboardMarketplace') }}" 
                               class="btn btn-outline-primary btn-sm py-1 px-2 d-inline-flex align-items-center gap-1"
                               title="Kelola Toko dan Produk di Dashboard">
                                <i class="bi bi-speedometer2"></i> Kelola di Dashboard
                            </a>
                        @endif

                        {{-- Dropdown Pilih Toko Lain jika ada --}}
                        @if($marketplaces->count() > 1)
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-light btn-sm border py-1 px-2 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-shop me-1"></i>Ganti Toko
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($marketplaces as $m)
                                        <li>
                                            <a class="dropdown-item d-flex justify-content-between align-items-center {{ $marketplace && $marketplace->id === $m->id ? 'active fw-bold' : '' }}" 
                                               href="{{ route('market', $m->id) }}">
                                                <span>{{ $m->nama }}</span>
                                                <small class="text-muted ms-2">{{ $m->products_count ?? $m->products()->count() }} item</small>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Spacer --}}
        <div class="col"></div>

        {{-- Tombol Filter Kategori & Merek --}}
        <div class="col-12 col-lg-auto">
            <div class="d-flex flex-wrap gap-2">
                {{-- Filter Unit --}}
                <div class="dropdown">
                    <button class="btn marketplace-filter dropdown-toggle {{ request('jenis') === 'Unit' ? 'border-primary text-primary fw-semibold' : '' }}" 
                            type="button" data-bs-toggle="dropdown" style="min-width: 140px;">
                        {{ request('jenis') === 'Unit' ? (request('subtype') ?: __('Unit')) : __('Unit') }}
                    </button>
                    <ul class="dropdown-menu" style="min-width: 180px;">
                        <li>
                            <a class="dropdown-item {{ request('jenis') === 'Unit' && !request('subtype') ? 'active' : '' }}" 
                               href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['jenis', 'subtype', 'page']), ['jenis' => 'Unit'])) }}">
                                {{ __('All Units') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach(['Rifle', 'Shotgun', 'Machine Gun', 'Sniper Rifle', 'Handgun'] as $unitSub)
                            <li>
                                <a class="dropdown-item {{ request('subtype') === $unitSub ? 'active' : '' }}" 
                                   href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['subtype', 'page']), ['jenis' => 'Unit', 'subtype' => $unitSub])) }}">
                                    {{ $unitSub }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Filter Sparepart --}}
                <div class="dropdown">
                    <button class="btn marketplace-filter dropdown-toggle {{ request('jenis') === 'Sparepart' ? 'border-primary text-primary fw-semibold' : '' }}" 
                            type="button" data-bs-toggle="dropdown" style="min-width: 140px;">
                        {{ request('jenis') === 'Sparepart' ? (request('subtype') ?: __('Sparepart')) : __('Sparepart') }}
                    </button>
                    <ul class="dropdown-menu" style="min-width: 180px;">
                        <li>
                            <a class="dropdown-item {{ request('jenis') === 'Sparepart' && !request('subtype') ? 'active' : '' }}" 
                               href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['jenis', 'subtype', 'page']), ['jenis' => 'Sparepart'])) }}">
                                {{ __('All Spareparts') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach(['Inbar', 'Hop up', 'Spring', 'Gearbox', 'Piston', 'Motor'] as $partSub)
                            <li>
                                <a class="dropdown-item {{ request('subtype') === $partSub ? 'active' : '' }}" 
                                   href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['subtype', 'page']), ['jenis' => 'Sparepart', 'subtype' => $partSub])) }}">
                                    {{ $partSub }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Filter Accessories --}}
                <div class="dropdown">
                    <button class="btn marketplace-filter dropdown-toggle {{ in_array(request('jenis'), ['Accessories', 'Aksesoris']) ? 'border-primary text-primary fw-semibold' : '' }}" 
                            type="button" data-bs-toggle="dropdown" style="min-width: 140px;">
                        {{ in_array(request('jenis'), ['Accessories', 'Aksesoris']) ? (request('subtype') ?: __('Accessories')) : __('Accessories') }}
                    </button>
                    <ul class="dropdown-menu" style="min-width: 180px;">
                        <li>
                            <a class="dropdown-item {{ in_array(request('jenis'), ['Accessories', 'Aksesoris']) && !request('subtype') ? 'active' : '' }}" 
                               href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['jenis', 'subtype', 'page']), ['jenis' => 'Accessories'])) }}">
                                {{ __('All Accessories') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach(['Scope', 'Hand grip', 'Laser', 'Silencer', 'Flashlight', 'Sling'] as $accSub)
                            <li>
                                <a class="dropdown-item {{ request('subtype') === $accSub ? 'active' : '' }}" 
                                   href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['subtype', 'page']), ['jenis' => 'Accessories', 'subtype' => $accSub])) }}">
                                    {{ $accSub }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Filter Brand --}}
                <div class="dropdown">
                    <button class="btn marketplace-filter dropdown-toggle {{ request('merk') ? 'border-primary text-primary fw-semibold' : '' }}" 
                            type="button" data-bs-toggle="dropdown" style="min-width: 140px;">
                        {{ request('merk') ?: __('Brand') }}
                    </button>
                    <ul class="dropdown-menu" style="min-width: 180px; max-height: 250px; overflow-y: auto;">
                        <li>
                            <a class="dropdown-item {{ !request('merk') ? 'active' : '' }}" 
                               href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['merk', 'page']))) }}">
                                {{ __('All Brands') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @forelse($brands as $brand)
                            <li>
                                <a class="dropdown-item {{ request('merk') === $brand ? 'active' : '' }}" 
                                   href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['merk', 'page']), ['merk' => $brand])) }}">
                                    {{ $brand }}
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-muted small">Belum ada merek</span></li>
                        @endforelse
                    </ul>
                </div>

                {{-- Tombol Reset Filter --}}
                <a href="{{ route('market', $marketplace?->id) }}" class="btn btn-reset-filter d-inline-flex align-items-center gap-1" title="Reset Semua Filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                    </svg>
                    {{ __('Reset Filter') }}
                </a>
            </div>
        </div>

    </div>
</div>

{{-- ==================== TITLE + SEARCH BAR ==================== --}}
<div class="container mt-4">
    <div class="row align-items-center g-3 mb-3">
        <div class="col-md">
            <h4 class="fw-bold mb-0">
                <i class="bi bi-shop text-primary me-2"></i>{{ __('Katalog Produk Toko') }}
            </h4>
            <div class="text-muted small">
                Menampilkan <strong>{{ $products->count() }}</strong> produk dari {{ $storeName }}
                @if(request('q') || request('jenis') || request('subtype') || request('merk'))
                    <span class="text-primary">(Difilter)</span>
                @endif
            </div>
        </div>

        <div class="col-md-auto d-flex flex-wrap gap-2 align-items-center">
            {{-- Form Pencarian Produk --}}
            <form method="GET" action="{{ route('market', $marketplace?->id) }}" class="d-flex gap-2">
                @if(request('jenis'))
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                @endif
                @if(request('subtype'))
                    <input type="hidden" name="subtype" value="{{ request('subtype') }}">
                @endif
                @if(request('merk'))
                    <input type="hidden" name="merk" value="{{ request('merk') }}">
                @endif

                <div class="input-group">
                    <input type="text"
                           name="q"
                           class="form-control market-search"
                           placeholder="{{ __('Cari produk di toko ini...') }}"
                           value="{{ request('q') }}">
                    <button class="btn btn-primary px-3" type="submit" title="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Filter Chips yang sedang aktif --}}
    @if(request('q') || request('jenis') || request('subtype') || request('merk'))
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <span class="small text-muted">Filter aktif:</span>
            @if(request('q'))
                <span class="badge bg-light text-dark border p-2">
                    Pencarian: "{{ request('q') }}"
                    <a href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['q', 'page']))) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                </span>
            @endif
            @if(request('jenis'))
                <span class="badge bg-light text-dark border p-2">
                    Jenis: {{ request('jenis') }}
                    <a href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['jenis', 'page']))) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                </span>
            @endif
            @if(request('subtype'))
                <span class="badge bg-light text-dark border p-2">
                    Subtipe: {{ request('subtype') }}
                    <a href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['subtype', 'page']))) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                </span>
            @endif
            @if(request('merk'))
                <span class="badge bg-light text-dark border p-2">
                    Merek: {{ request('merk') }}
                    <a href="{{ route('market', array_merge(['marketplace' => $marketplace?->id], request()->except(['merk', 'page']))) }}" class="text-danger ms-1 text-decoration-none">&times;</a>
                </span>
            @endif
            <a href="{{ route('market', $marketplace?->id) }}" class="btn btn-link btn-sm text-decoration-none text-muted py-0">Hapus Semua</a>
        </div>
    @endif
</div>

{{-- ==================== GRID CARD PRODUK (READ ONLY) ==================== --}}
<div class="container pb-5">
    @if ($products->count() > 0)
        <div class="row g-3">
            @foreach ($products as $produk)
                @php
                    $imgSrc = $produk->gambar
                        ? (str_starts_with($produk->gambar, 'http') ? $produk->gambar : asset('storage/' . $produk->gambar))
                        : asset('img/balnkLogo.png');
                @endphp

                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                    <a href="{{ route('isiMarketplace', $produk->id) }}" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border product-card position-relative">

                            {{-- Badge Kondisi / Stok --}}
                            <div class="position-absolute top-0 start-0 m-2 z-1">
                                @if($produk->stok > 0)
                                    <span class="badge bg-dark bg-opacity-75 text-white shadow-sm" style="font-size: 0.7rem;">
                                        {{ $produk->kondisi }}
                                    </span>
                                @else
                                    <span class="badge bg-danger shadow-sm" style="font-size: 0.7rem;">
                                        Stok Habis
                                    </span>
                                @endif
                            </div>

                            {{-- Gambar Produk --}}
                            <div class="card-img-top product-image-frame position-relative">
                                <img src="{{ $imgSrc }}"
                                     alt="{{ $produk->nama }}"
                                     class="img-fluid"
                                     style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                            </div>

                            {{-- Body Card --}}
                            <div class="card-body p-2 d-flex flex-column">
                                <h6 class="card-title fw-bold text-truncate mb-1" title="{{ $produk->nama }}" style="font-size: 0.9rem;">
                                    {{ $produk->nama }}
                                </h6>

                                <div class="text-success fw-bold mb-1" style="font-size: 0.95rem;">
                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                </div>

                                <ul class="list-unstyled small mb-2 text-muted" style="font-size: 0.78rem;">
                                    <li class="text-truncate mb-1" title="{{ $produk->merk }}">
                                        <i class="bi bi-tag me-1" aria-hidden="true"></i>{{ $produk->merk }}
                                    </li>
                                    <li class="text-truncate">
                                        <i class="bi bi-grid-3x3-gap me-1" aria-hidden="true"></i>{{ $produk->jenis }}
                                    </li>
                                </ul>

                                <div class="mt-auto pt-2 border-top text-muted small d-flex justify-content-between align-items-center" style="font-size: 0.75rem;">
                                    <span class="text-truncate" title="{{ $produk->lokasi }}">
                                        <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>{{ $produk->lokasi }}
                                    </span>
                                    @if($produk->stok > 0)
                                        <span class="text-secondary fw-semibold">Sisa {{ $produk->stok }}</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        {{-- Tampilan Kosong (Empty State) --}}
        <div class="text-center text-muted py-5 my-4 bg-white rounded-3 shadow-sm border p-4">
            <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
            <h5 class="fw-bold text-dark mb-1">{{ __('Tidak ada produk yang ditemukan') }}</h5>
            <p class="text-muted small mb-3">
                @if(request('q') || request('jenis') || request('subtype') || request('merk'))
                    Tidak ada produk di {{ $storeName }} yang cocok dengan kriteria filter saat ini.
                @else
                    Toko ini belum memiliki katalog produk aktif.
                @endif
            </p>
            @if(request('q') || request('jenis') || request('subtype') || request('merk'))
                <a href="{{ route('market', $marketplace?->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
                </a>
            @endif
        </div>
    @endif
</div>

{{-- ==================== CUSTOM STYLE ==================== --}}
@push('styles')
<style>
    .marketplace-filter {
        background: #fdfdfd;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 6px;
        font-size: 0.875rem;
        padding: 0.4rem 0.75rem;
        transition: all .2s ease;
    }
    .marketplace-filter:hover {
        background-color: #f1f3f5;
        border-color: #adb5bd;
    }
    .btn-reset-filter {
        background: #fdfdfd;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 6px;
        font-size: 0.875rem;
        padding: 0.4rem 0.75rem;
        color: #495057;
        transition: all .2s ease;
    }
    .btn-reset-filter:hover {
        background-color: #e9ecef;
        color: #212529;
    }
    .market-search {
        min-width: 220px;
        border-radius: 6px 0 0 6px;
        font-size: 0.875rem;
    }
    .product-image-frame {
        width: 100%;
        height: 12.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8f9fa;
        border-bottom: 1px solid #f0f0f0;
    }
    .product-card {
        transition: transform .2s ease, box-shadow .2s ease;
        border-radius: 8px;
    }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
    }
</style>
@endpush

@endsection