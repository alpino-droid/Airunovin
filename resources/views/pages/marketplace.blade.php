@extends('layout/app')

@section('title', __('Marketplace'))

@section('content')
    <div class="container text-start pt-3">
        <div class="row align-items-start">
            <div class="col">
                <h1 class="h1 marketplace-title">{{ __('Marketplace') }}</h1>
            </div>
            <div class="col">
                <form method="GET" action="{{ route('marketplace') }}" class="d-flex flex-wrap gap-2 justify-content-end">
                    <input type="text" name="q" class="form-control" style="max-width: 200px;" placeholder="{{ __('Cari produk / toko...') }}" value="{{ request('q') ?? request('search') }}">
                    <select name="province" class="form-select" aria-label="{{ __('Province') }}" style="max-width: 170px;">
                        <option value="">{{ __('All Provinces') }}</option>
                        @foreach($provinsi as $prov)
                            <option value="{{ $prov->provinsi }}" @selected(request('province') == $prov->provinsi)>{{ $prov->provinsi }}</option>
                        @endforeach
                    </select>
                    <select name="city" class="form-select" aria-label="{{ __('City') }}" style="max-width: 150px;">
                        <option value="">{{ __('All Cities') }}</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" @selected(request('city') == $city)>{{ $city }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" type="submit">Filter</button>
                    @if(request('q') || request('search') || request('province') || request('city') || request('unit') || request('sparepart') || request('aksesoris') || request('merk'))
                        <a href="{{ route('marketplace') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-clockwise"></i></a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        @if(request('q') || request('search'))
            <div class="alert alert-light border d-flex justify-content-between align-items-center py-2 px-3 mb-3">
                <div>
                    <i class="bi bi-search me-1 text-primary"></i> Menampilkan hasil pencarian untuk: <strong>"{{ request('q') ?? request('search') }}"</strong> 
                    <span class="badge bg-secondary ms-1">{{ count($products) }} produk ditemukan</span>
                </div>
                <a href="{{ route('marketplace') }}" class="btn btn-sm btn-outline-secondary">Reset Pencarian</a>
            </div>
        @endif
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2">
                    {{-- Filter Unit --}}
                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle {{ request('unit') || request('jenis') === 'unit' ? 'border-primary text-primary fw-semibold' : '' }}" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ request('unit') && request('unit') !== 'all' ? request('unit') : __('Unit') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px;">
                            <li><a class="dropdown-item {{ (request('unit') === 'all' || request('jenis') === 'unit') ? 'active' : '' }}" href="{{ route('marketplace', array_merge(request()->except(['unit', 'sparepart', 'aksesoris', 'jenis', 'page']), ['unit' => 'all'])) }}">{{ __('Semua Unit') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach(['Rifle', 'Shotgun', 'Machine Gun', 'Sniper Rifle', 'Handgun'] as $sub)
                                <li><a class="dropdown-item {{ request('unit') === $sub ? 'active' : '' }}" href="{{ route('marketplace', array_merge(request()->except(['unit', 'sparepart', 'aksesoris', 'jenis', 'page']), ['unit' => $sub])) }}">{{ $sub }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Filter Sparepart --}}
                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle {{ request('sparepart') || request('jenis') === 'sparepart' ? 'border-primary text-primary fw-semibold' : '' }}" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ request('sparepart') && request('sparepart') !== 'all' ? request('sparepart') : __('Sparepart') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px;">
                            <li><a class="dropdown-item {{ (request('sparepart') === 'all' || request('jenis') === 'sparepart') ? 'active' : '' }}" href="{{ route('marketplace', array_merge(request()->except(['unit', 'sparepart', 'aksesoris', 'jenis', 'page']), ['sparepart' => 'all'])) }}">{{ __('Semua Sparepart') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach(['Inbar', 'Hop up', 'Spring', 'Gearbox', 'Piston', 'Motor'] as $sub)
                                <li><a class="dropdown-item {{ request('sparepart') === $sub ? 'active' : '' }}" href="{{ route('marketplace', array_merge(request()->except(['unit', 'sparepart', 'aksesoris', 'jenis', 'page']), ['sparepart' => $sub])) }}">{{ $sub }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Filter Aksesoris --}}
                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle {{ request('aksesoris') || in_array(request('jenis'), ['aksesoris', 'accessories', 'Accessories']) ? 'border-primary text-primary fw-semibold' : '' }}" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ request('aksesoris') && request('aksesoris') !== 'all' ? request('aksesoris') : __('Aksesoris') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px;">
                            <li><a class="dropdown-item {{ (request('aksesoris') === 'all' || in_array(request('jenis'), ['aksesoris', 'accessories'])) ? 'active' : '' }}" href="{{ route('marketplace', array_merge(request()->except(['unit', 'sparepart', 'aksesoris', 'jenis', 'page']), ['aksesoris' => 'all'])) }}">{{ __('Semua Aksesoris') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach(['Scope', 'Hand grip', 'Laser', 'Silencer', 'Flashlight', 'Sling', 'Vest'] as $sub)
                                <li><a class="dropdown-item {{ request('aksesoris') === $sub ? 'active' : '' }}" href="{{ route('marketplace', array_merge(request()->except(['unit', 'sparepart', 'aksesoris', 'jenis', 'page']), ['aksesoris' => $sub])) }}">{{ $sub }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Filter Merk --}}
                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle {{ request('merk') || request('jenis') === 'merk' ? 'border-primary text-primary fw-semibold' : '' }}" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ request('merk') && request('merk') !== 'all' ? request('merk') : __('Merk') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px; max-height: 260px; overflow-y: auto;">
                            <li><a class="dropdown-item {{ request('merk') === 'all' || request('jenis') === 'merk' ? 'active' : '' }}" href="{{ route('marketplace', array_merge(request()->except(['merk', 'jenis', 'page']), ['merk' => 'all'])) }}">{{ __('Semua Merk') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(isset($brands) && $brands->isNotEmpty())
                                @foreach ($brands as $brandItem)
                                    <li><a class="dropdown-item {{ request('merk') === $brandItem ? 'active' : '' }}" href="{{ route('marketplace', array_merge(request()->except(['merk', 'jenis', 'page']), ['merk' => $brandItem])) }}">{{ $brandItem }}</a></li>
                                @endforeach
                            @else
                                @foreach(['Specna Arms', "D'Cobra Custom", 'Custom Build', 'Condor Tactical', 'RCW Engineering', 'Maple Leaf'] as $brandItem)
                                    <li><a class="dropdown-item {{ request('merk') === $brandItem ? 'active' : '' }}" href="{{ route('marketplace', array_merge(request()->except(['merk', 'jenis', 'page']), ['merk' => $brandItem])) }}">{{ $brandItem }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    {{-- Reset Filter Button --}}
                    <a href="{{ route('marketplace') }}" class="btn btn-reset-filter text-decoration-none d-inline-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise me-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                        </svg>
                        {{ __('Reset Filter') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 marketplace-section-title">{{ __('Latest Products') }}</h5>
            @if(request()->hasAny(['unit', 'sparepart', 'aksesoris', 'merk', 'province', 'city', 'jenis']))
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                    <i class="bi bi-funnel me-1"></i>Filter Aktif: {{ request('unit') ?: (request('sparepart') ?: (request('aksesoris') ?: (request('merk') ?: (request('city') ?: request('province'))))) }}
                </span>
            @endif
        </div>

        <div class="row justify-content-center g-2 mb-4">
            @forelse ($products as $produk)
                <div class="col-auto mb-5">
                    <a href="{{ route('isiMarketplace', $produk->id) }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top product-image-frame">
                                @if ($produk->gambar)
                                    <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                                @else
                                    <img src="{{ asset('img/balnkLogo.png') }}" alt="{{ $produk->nama }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy">
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate" title="{{ $produk->nama }}">{{ $produk->nama }}</h5>
                                <ul class="list-unstyled small mb-2">
                                    <li class="text-success fw-semibold mb-1">Rp {{ number_format($produk->harga, 0, ',', '.') }}</li>
                                    <li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">{{ $produk->merk }}</span></li>
                                    @if($produk->unit)
                                        <li><i class="bi bi-crosshair" aria-hidden="true"></i> <span class="text-muted">{{ $produk->unit }}</span></li>
                                    @elseif($produk->sparepart)
                                        <li><i class="bi bi-gear" aria-hidden="true"></i> <span class="text-muted">{{ $produk->sparepart }}</span></li>
                                    @elseif($produk->aksesoris)
                                        <li><i class="bi bi-shield-check" aria-hidden="true"></i> <span class="text-muted">{{ $produk->aksesoris }}</span></li>
                                    @elseif($produk->jenis)
                                        <li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">{{ $produk->jenis }}</span></li>
                                    @endif
                                </ul>
                                <div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $produk->lokasi }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <div class="p-4 bg-light rounded-3 d-inline-block">
                        <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                        <h5 class="fw-bold">Tidak ada produk ditemukan</h5>
                        <p class="text-muted mb-3">Tidak ada produk yang sesuai dengan kriteria filter yang Anda pilih.</p>
                        <a href="{{ route('marketplace') }}" class="btn btn-outline-primary btn-sm">Reset Semua Filter</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection