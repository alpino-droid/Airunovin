@extends('layout/app')

@section('title', __('Market'))

@section('content')

{{-- ==================== HEADER PROFILE + FILTER ==================== --}}
<div class="container text-start pt-3">
    <div class="row align-items-center g-3 flex-wrap">

        {{-- Avatar + Info User --}}
        <div class="col-auto">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0 shadow-sm"
                    style="width: 90px; height: 90px; font-size: 1.75rem;">
                    RA
                </div>

                <div>
                    <h2 class="fw-bold mb-0 text-uppercase lh-1">M4 Airsoft Store</h2>
                    <div class="text-muted small mt-1">Lorem</div>
                    <div class="text-muted small">Lorem</div>
                </div>
            </div>
        </div>

        {{-- Spacer --}}
        <div class="col"></div>

        {{-- Tombol Filter (6 kotak) --}}
        <div class="col-auto">
            <div class="d-flex flex-wrap gap-2">
                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ __('Unit') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px;">
                            <li><a class="dropdown-item" href="#">{{ __('All Units') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Rifle</a></li>
                            <li><a class="dropdown-item" href="#">Shotgun</a></li>
                            <li><a class="dropdown-item" href="#">Machine Gun</a></li>
                            <li><a class="dropdown-item" href="#">Sniper Rifle</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ __('Sparepart') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px;">
                            <li><a class="dropdown-item" href="#">{{ __('All Spareparts') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Inbar</a></li>
                            <li><a class="dropdown-item" href="#">Hop up</a></li>
                            <li><a class="dropdown-item" href="#">Spring</a></li>
                            <li><a class="dropdown-item" href="#"></a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ __('Accessories') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px;">
                            <li><a class="dropdown-item" href="#">{{ __('All Accessories') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Scope</a></li>
                            <li><a class="dropdown-item" href="#">Hand grip</a></li>
                            <li><a class="dropdown-item" href="#">Laser</a></li>
                            <li><a class="dropdown-item" href="#"></a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ __('Brand') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px;">
                            <li><a class="dropdown-item" href="#">{{ __('All Brands') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Merk A</a></li>
                            <li><a class="dropdown-item" href="#">Merk B</a></li>
                            <li><a class="dropdown-item" href="#">Merk C</a></li>
                            <li><a class="dropdown-item" href="#">Merk D</a></li>
                        </ul>
                    </div>

                    <button class="btn btn-reset-filter" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise me-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                        </svg>
                        {{ __('Reset Filter') }}
                    </button>
                </div>
        </div>

    </div>
</div>

{{-- ==================== TITLE + SEARCH BAR ==================== --}}
<div class="container mt-5">
    <div class="row align-items-center g-3 mb-4">
        <div class="col-md">
            <h4 class="fw-bold mb-0">{{ __('Latest Products') }}</h4>
            <div class="text-muted small">{{ __('Temukan produk airsoft terbaru di sekitarmu') }}</div>
        </div>
        <div class="col-md-auto">
            <form method="GET" action="" class="d-flex gap-2">
                <input type="text"
                       name="q"
                       class="form-control market-search"
                       placeholder="{{ __('Cari produk...') }}"
                       value="{{ request('q') }}">
                <button class="btn btn-primary px-4" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ==================== GRID CARD PRODUK ==================== --}}
<div class="container">
    @php
        $products = [
            ['image' => 'G.S.P1.jpg', 'name' => 'Tactical Vest', 'price' => 'Rp 450.000', 'brand' => 'Condor', 'category' => 'Perlengkapan', 'region' => 'Jakarta'],
            ['image' => 'G.S.P2.jpg', 'name' => 'AEG M4', 'price' => 'Rp 2.750.000', 'brand' => 'Specna Arms', 'category' => 'Unit', 'region' => 'Bandung'],
            ['image' => 'G.S.P3.jpg', 'name' => 'AEG M4 NEW RAKIT', 'price' => 'Rp 3.250.000', 'brand' => 'Custom Build', 'category' => 'Unit', 'region' => 'Surabaya'],
            ['image' => 'G.S.P4.jpg', 'name' => 'Dcobra SIG556 custom', 'price' => 'Rp 900.000', 'brand' => 'Dcobra', 'category' => 'Unit', 'region' => 'Yogyakarta'],
            ['image' => 'G.S.P5.jpg', 'name' => 'Kokang Metal Hybrid-X Dcobra MAK47L', 'price' => 'Rp 1.250.000', 'brand' => 'RCW', 'category' => 'Part', 'region' => 'Malang'],
            ['image' => 'G.S.P6.jpg', 'name' => 'Maple Leaf Hop Up Chamber Assembly for Tokyo Marui / KJ Works / WE / 1911 GBB', 'price' => 'Rp 325.000', 'brand' => 'Maple Leaf', 'category' => 'Part', 'region' => 'Depok'],
        ];
    @endphp

    @if (count($products) > 0)
        <div class="row justify-content-center g-2">
            @foreach ($products as $produk)
                <div class="col-auto mb-5">
                    <a href="" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">

                            {{-- Gambar Produk --}}
                            <div class="card-img-top product-image-frame">
                                <img src="{{ asset('img/imgStatik/GambarProduk/' . $produk['image']) }}"
                                     alt="{{ $produk['name'] }}"
                                     class="img-fluid"
                                     style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                     loading="lazy">
                            </div>

                            {{-- Body Card --}}
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate" title="{{ $produk['name'] }}">
                                    {{ $produk['name'] }}
                                </h5>
                                <ul class="list-unstyled small mb-2">
                                    <li class="text-success fw-semibold mb-1">{{ $produk['price'] }}</li>
                                    <li>
                                        <i class="bi bi-tag" aria-hidden="true"></i>
                                        <span class="text-muted">{{ $produk['brand'] }}</span>
                                    </li>
                                    <li>
                                        <i class="bi bi-grid-3x3-gap" aria-hidden="true"></i>
                                        <span class="text-muted">{{ $produk['category'] }}</span>
                                    </li>
                                </ul>
                                <div class="mt-auto pt-2 border-top text-muted">
                                    <i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $produk['region'] }}
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            {{ __('Belum ada produk tersedia') }}
        </div>
    @endif
</div>

{{-- ==================== CUSTOM STYLE ==================== --}}
@push('styles')
<style>
    .market-filter-btn {
        width: 100px;
        height: 32px;
        border-radius: 6px;
        transition: all .2s ease;
    }
    .market-filter-btn:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .market-search {
        min-width: 240px;
        border-radius: 6px;
    }
    .product-image-frame {
        width: 100%;
        height: 13rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f1f3f5;
    }
    .card {
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
    }
</style>
@endpush

@endsection