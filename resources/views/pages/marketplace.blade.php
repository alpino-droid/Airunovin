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
                            <li><a class="dropdown-item" href="#">Unit A</a></li>
                            <li><a class="dropdown-item" href="#">Unit B</a></li>
                            <li><a class="dropdown-item" href="#">Unit C</a></li>
                            <li><a class="dropdown-item" href="#">Unit D</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ __('Sparepart') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px;">
                            <li><a class="dropdown-item" href="#">{{ __('All Spareparts') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Sparepart A</a></li>
                            <li><a class="dropdown-item" href="#">Sparepart B</a></li>
                            <li><a class="dropdown-item" href="#">Sparepart C</a></li>
                            <li><a class="dropdown-item" href="#">Sparepart D</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn marketplace-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" style="min-width: 150px;">
                            {{ __('Accessories') }}
                        </button>
                        <ul class="dropdown-menu" style="min-width: 200px;">
                            <li><a class="dropdown-item" href="#">{{ __('All Accessories') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Aksesoris A</a></li>
                            <li><a class="dropdown-item" href="#">Aksesoris B</a></li>
                            <li><a class="dropdown-item" href="#">Aksesoris C</a></li>
                            <li><a class="dropdown-item" href="#">Aksesoris D</a></li>
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

        <div class="row justify-content-center g-2">
            <div class="col-auto mb-5">
                <a href="{{ route('isiMarketplace') }}" class="text-decoration-none text-dark">
                    <div class="card h-100" style="width: 13rem;">
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;"><img src="{{ asset('img/imgStatik/GambarProduk/G.S.P1.jpg') }}" alt="Product thumbnail 1" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy"></div>
                        <div class="card-body d-flex flex-column"><h5 class="card-title fw-bold text-truncate">Nama Produk</h5><ul class="list-unstyled small mb-2"><li class="text-success fw-semibold mb-1">Rp 150.000</li><li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">Merk A</span></li><li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">Elektronik</span></li></ul><div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> Wilayah</div></div>
                    </div>
                </a>
            </div>
            <div class="col-auto mb-5"><a href="{{ route('isiMarketplace') }}" class="text-decoration-none text-dark"><div class="card h-100" style="width: 13rem;"><div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;"><img src="{{ asset('img/imgStatik/GambarProduk/G.S.P2.jpg') }}" alt="Product thumbnail 2" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy"></div><div class="card-body d-flex flex-column"><h5 class="card-title fw-bold text-truncate">Nama Produk</h5><ul class="list-unstyled small mb-2"><li class="text-success fw-semibold mb-1">Rp 150.000</li><li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">Merk A</span></li><li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">Elektronik</span></li></ul><div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> Wilayah</div></div></div></a></div>
            <div class="col-auto mb-5"><a href="{{ route('isiMarketplace') }}" class="text-decoration-none text-dark"><div class="card h-100" style="width: 13rem;"><div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;"><img src="{{ asset('img/imgStatik/GambarProduk/G.S.P3.jpg') }}" alt="Product thumbnail 3" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy"></div><div class="card-body d-flex flex-column"><h5 class="card-title fw-bold text-truncate">Nama Produk</h5><ul class="list-unstyled small mb-2"><li class="text-success fw-semibold mb-1">Rp 150.000</li><li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">Merk A</span></li><li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">Elektronik</span></li></ul><div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> Wilayah</div></div></div></a></div>
            <div class="col-auto mb-5"><a href="{{ route('isiMarketplace') }}" class="text-decoration-none text-dark"><div class="card h-100" style="width: 13rem;"><div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;"><img src="{{ asset('img/imgStatik/GambarProduk/G.S.P4.jpg') }}" alt="Product thumbnail 4" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy"></div><div class="card-body d-flex flex-column"><h5 class="card-title fw-bold text-truncate">Nama Produk</h5><ul class="list-unstyled small mb-2"><li class="text-success fw-semibold mb-1">Rp 150.000</li><li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">Merk A</span></li><li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">Elektronik</span></li></ul><div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> Wilayah</div></div></div></a></div>
            <div class="col-auto mb-5"><a href="{{ route('isiMarketplace') }}" class="text-decoration-none text-dark"><div class="card h-100" style="width: 13rem;"><div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;"><img src="{{ asset('img/imgStatik/GambarProduk/G.S.P5.jpg') }}" alt="Product thumbnail 5" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy"></div><div class="card-body d-flex flex-column"><h5 class="card-title fw-bold text-truncate">Nama Produk</h5><ul class="list-unstyled small mb-2"><li class="text-success fw-semibold mb-1">Rp 150.000</li><li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">Merk A</span></li><li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">Elektronik</span></li></ul><div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> Wilayah</div></div></div></a></div>
            <div class="col-auto mb-5"><a href="{{ route('isiMarketplace') }}" class="text-decoration-none text-dark"><div class="card h-100" style="width: 13rem;"><div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;"><img src="{{ asset('img/imgStatik/GambarProduk/G.S.P6.jpg') }}" alt="Product thumbnail 6" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy"></div><div class="card-body d-flex flex-column"><h5 class="card-title fw-bold text-truncate">Nama Produk</h5><ul class="list-unstyled small mb-2"><li class="text-success fw-semibold mb-1">Rp 150.000</li><li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">Merk A</span></li><li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">Elektronik</span></li></ul><div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> Wilayah</div></div></div></a></div>
        </div>
    </div>
@endsection