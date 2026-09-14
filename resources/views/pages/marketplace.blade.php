@extends('layout/app')

@section('title', __('Marketplace'))

@section('content')
    <div class="container text-start pt-3">
        <div class="row align-items-start">
            <div class="col">
                <h1 class="h1 marketplace-title">{{ __('Marketplace') }}</h1>
            </div>
            <div class="col">
                <div class="d-flex gap-2 justify-content-end">
                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" style="width: 200px;">
                            {{ __('Province') }}
                        </button>
                        <ul class="dropdown-menu" style="width: 200px;">
                            <li>
                                <div class="mb-3 px-3">
                                    <input type="text" class="form-control" placeholder="{{ __('Search') }} {{ __('Province') }}">
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="#">Action 1</a></li>
                            <li><a class="dropdown-item" href="#">Action 2</a></li>
                            <li><a class="dropdown-item" href="#">Action 3</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" style="width: 200px;">
                            {{ __('City') }}
                        </button>
                        <ul class="dropdown-menu" style="width: 200px;">
                            <li>
                                <div class="mb-3 px-3">
                                    <input type="text" class="form-control" placeholder="{{ __('Search') }} {{ __('City') }}">
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="#">Action A</a></li>
                            <li><a class="dropdown-item" href="#">Action B</a></li>
                            <li><a class="dropdown-item" href="#">Action C</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-12">
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

        <h5 class="fw-bold mb-3 marketplace-section-title">{{ __('Latest Products') }}</h5>

        @php
            $produkMarketplace = [
                [
                    'gambar' => 'img/imgStatik/GambarProduk/G.S.P1.jpg',
                    'nama' => 'Tactical Vest Modular',
                    'harga' => 350000,
                    'merk' => 'EmersonGear',
                    'jenis' => 'Accessories',
                    'wilayah' => 'Jakarta Selatan',
                ],
                [
                    'gambar' => 'img/imgStatik/GambarProduk/G.S.P2.jpg',
                    'nama' => 'M4 Custom AEG',
                    'harga' => 2850000,
                    'merk' => 'M4',
                    'jenis' => 'Unit',
                    'wilayah' => 'Bandung',
                ],
                [
                    'gambar' => 'img/imgStatik/GambarProduk/G.S.P3.jpg',
                    'nama' => 'M4 CQB AEG',
                    'harga' => 3200000,
                    'merk' => 'M4',
                    'jenis' => 'Unit',
                    'wilayah' => 'Surabaya',
                ],
                [
                    'gambar' => 'img/imgStatik/GambarProduk/G.S.P4.jpg',
                    'nama' => 'Dcobra SIG556 custom',
                    'harga' => 900000,
                    'merk' => 'Dcobra',
                    'jenis' => 'Unit',
                    'wilayah' => 'Jakarta Timur',
                ],
                [
                    'gambar' => 'img/imgStatik/GambarProduk/G.S.P5.jpg',
                    'nama' => 'Kokang Metal Hybrid-1 MAK47L',
                    'harga' => 175000,
                    'merk' => 'RCW',
                    'jenis' => 'Sparepart',
                    'wilayah' => 'Malang',
                ],
                [
                    'gambar' => 'img/imgStatik/GambarProduk/G.S.P6.jpg',
                    'nama' => 'Maple Leaf Hop Up Chamber',
                    'harga' => 450000,
                    'merk' => 'Maple Leaf',
                    'jenis' => 'Sparepart',
                    'wilayah' => 'Malang',
                ],
            ];
        @endphp

        <div class="row justify-content-center g-2">
            @foreach ($produkMarketplace as $produk)
                <div class="col-auto mb-5">
                    <a href="{{ route('isiMarketplace') }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;"><img src="{{ asset($produk['gambar']) }}" alt="{{ $produk['nama'] }}" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy"></div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate" title="{{ $produk['nama'] }}">{{ $produk['nama'] }}</h5>
                                <ul class="list-unstyled small mb-2">
                                    <li class="text-success fw-semibold mb-1">Rp {{ number_format($produk['harga'], 0, ',', '.') }}</li>
                                    <li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">{{ $produk['merk'] }}</span></li>
                                    <li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">{{ $produk['jenis'] }}</span></li>
                                </ul>
                                <div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $produk['wilayah'] }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection