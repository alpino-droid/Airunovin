@extends('layout/app')

@section('title', 'Home - Laravel Blade')

@section('content')
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-image-wrap">
                    <img src="{{ asset('img/h4.png') }}" class="d-block w-100 carousel-slide-image" alt="Komunitas Airunovin">
                </div>
                <div class="carousel-caption d-none d-md-block">
                    <h5>{{ __('Welcome to Airunovin') }}</h5>
                    <p>{{ __('Find the best experience in airsoft with an active and supportive community.') }}</p>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-image-wrap">
                    <img src="{{ asset('img/h1.png') }}" class="d-block w-100 carousel-slide-image" alt="Informasi event dan produk airsoft gun">
                </div>
                <div class="carousel-caption d-none d-md-block">
                    <h5>{{ __('Everything in one place') }}</h5>
                    <p>{{ __('Follow competitions, get to know clubs, and find the gear that suits your needs.') }}</p>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-image-wrap">
                    <img src="{{ asset('img/h2.png') }}" class="d-block w-100 carousel-slide-image" alt="Bergabung dengan komunitas airsoft gun">
                </div>
                <div class="carousel-caption d-none d-md-block">
                    <h5>{{ __('Start your airsoft journey') }}</h5>
                    <p>{{ __('Build connections, expand your knowledge, and enjoy being part of the airsoft community.') }}</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">{{ __('Previous') }}</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">{{ __('Next') }}</span>
        </button>
    </div>

    <div class="container-xxl mt-5">
        <p class="fs-3">{{ __('Latest Events') }}</p>
        <div class="row justify-content-center g-2">
            @forelse($eventsTerbaru as $evt)
                <div class="col-auto mb-5">
                    <a href="{{ route('isiEvent') }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;">
                                @if($evt->poster && is_array(json_decode($evt->poster, true)) && count(json_decode($evt->poster, true)) > 0)
                                    @php $posters = json_decode($evt->poster, true); @endphp
                                    <img src="{{ asset('storage/' . $posters[0]) }}" alt="Event poster" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: cover;" loading="lazy">
                                @else
                                    <img src="https://static.wikitide.net/thefireriseswikiwiki/thumb/e/ec/The_fire_truly_rises.gif/200px-The_fire_truly_rises.gif" alt="Event thumbnail" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy">
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate" title="{{ $evt->nama }}">{{ $evt->nama }}</h5>
                                <ul class="list-unstyled small mb-2">
                                    <li class="mb-1">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-calendar-event" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $evt->tanggal }}</span>
                                    </li>
                                    <li>
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-person" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $evt->penyelenggara }}</span>
                                    </li>
                                </ul>
                                <div class="mt-auto pt-2 border-top">
                                    <div class="text-muted">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
                                        <span>{{ $evt->kota }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <p>{{ __('No latest events') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="container-xxl mt-5">
        <p class="fs-3">{{ __('Event') }}</p>
        <div class="row justify-content-center g-2">
            @forelse($events as $evt)
                <div class="col-auto mb-5">
                    <a href="{{ route('isiEvent') }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;">
                                @if($evt->poster && is_array(json_decode($evt->poster, true)) && count(json_decode($evt->poster, true)) > 0)
                                    @php $posters = json_decode($evt->poster, true); @endphp
                                    <img src="{{ asset('storage/' . $posters[0]) }}" alt="Event poster" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: cover;" loading="lazy">
                                @else
                                    <img src="https://static.wikitide.net/thefireriseswikiwiki/thumb/e/ec/The_fire_truly_rises.gif/200px-The_fire_truly_rises.gif" alt="Event thumbnail" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy">
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate" title="{{ $evt->nama }}">{{ $evt->nama }}</h5>
                                <ul class="list-unstyled small mb-2">
                                    <li class="mb-1">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-calendar-event" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $evt->tanggal }}</span>
                                    </li>
                                    <li>
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-person" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $evt->penyelenggara }}</span>
                                    </li>
                                </ul>
                                <div class="mt-auto pt-2 border-top">
                                    <div class="text-muted">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
                                        <span>{{ $evt->kota }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <p>{{ __('No events available') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="container-xxl mt-5">
        <p class="fs-3">{{ __('Unit') }}</p>
        <div class="row justify-content-center g-2">
            @php
                $products = [
                    ['image' => 'G.S.P1.jpg', 'name' => 'Tactical Vest', 'price' => 'Rp 450.000', 'brand' => 'Condor', 'category' => 'Perlengkapan', 'region' => 'Jakarta'],
                    ['image' => 'G.S.P2.jpg', 'name' => 'AEG M4', 'price' => 'Rp 2.750.000', 'brand' => 'Specna Arms', 'category' => 'Unit', 'region' => 'Bandung'],
                    ['image' => 'G.S.P3.jpg', 'name' => 'AEG M4 NEW RAKIT', 'price' => 'Rp 3.250.000', 'brand' => 'Custom Build', 'category' => 'Unit', 'region' => 'Surabaya'],
                    ['image' => 'G.S.P4.jpg', 'name' => 'Dcobra M790 Mod ACR From MWII 2009', 'price' => 'Rp 1.850.000', 'brand' => 'Dcobra', 'category' => 'Unit', 'region' => 'Yogyakarta'],
                    ['image' => 'G.S.P5.jpg', 'name' => 'Kokang Metal Hybrid-X Dcobra MAK47L', 'price' => 'Rp 1.250.000', 'brand' => 'Dcobra', 'category' => 'Part', 'region' => 'Malang'],
                    ['image' => 'G.S.P6.jpg', 'name' => 'Maple Leaf Hop Up Chamber Assembly for Tokyo Marui / KJ Works / WE / 1911 GBB', 'price' => 'Rp 325.000', 'brand' => 'Maple Leaf', 'category' => 'Part', 'region' => 'Depok'],
                ];
            @endphp
            @foreach ($products as $product)
                <div class="col-auto mb-5">
                    <a href="{{ route('isiMarketplace') }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;">
                                <img src="{{ asset('img/imgStatik/GambarProduk/' . $product['image']) }}" alt="{{ $product['name'] }}" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate">{{ $product['name'] }}</h5>
                                <ul class="list-unstyled small mb-2">
                                    <li class="text-success fw-semibold mb-1">{{ $product['price'] }}</li>
                                    <li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">{{ $product['brand'] }}</span></li>
                                    <li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">{{ $product['category'] }}</span></li>
                                </ul>
                                <div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $product['region'] }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container-xxl mt-5">
        <p class="fs-3">{{ __('Club') }}</p>
        <div class="row justify-content-center g-2">
            @forelse($clubs as $club)
                <div class="col-auto mb-2">
                    <a href="{{ route('isiClub') }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px; overflow: hidden; border-radius: 8px 8px 0 0;">
                                <img src="{{ $club?->logo_url ?? asset('img/balnkLogo.png') }}" alt="Organization logo" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}'">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate" title="Nama Organisasi">{{ $club->nama }}</h5>
                                <ul class="list-unstyled small mb-0">
                                    <li class="mb-1">
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $club->city}}</span>
                                    </li>
                                    <li>
                                        <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted me-1" style="width: 24px; height: 24px;"><i class="bi bi-building" aria-hidden="true"></i></span>
                                        <span class="text-muted">{{ $club->induk_organisasi}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <p>{{ __('No clubs available') }}</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection