@extends('layout/app')

@section('title', 'Home - Laravel Blade')

@section('content')
    <div class="container text-start pt-3">
       <div class="row align-items-start">
            <div class="col-4">
                <div style="width: 100%; height: 560px; background: #f8f9fa; 
                                                display: flex; align-items: center; justify-content: center; 
                                                overflow: hidden; border-radius: 8px 8px 0 0;">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSttwgExhmL4biPZHFTFiLQ-DX9LkDUTZXNNT0ML00OTA&s=10" 
                                            alt="..."
                                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
            </div>
            <div class="col">

                <h1 class="h2 fw-bold mb-3">Merah Putih Airsoft Challenge 2026</h1>
                <p class="lead text-muted mb-4">Siap menguji strategi, ketepatan, dan kekompakan tim di arena airsoft?</p>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block mb-1">Tanggal</small>
                            <strong>21–23 Agustus 2026</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block mb-1">Biaya pendaftaran</small>
                            <strong>Rp50.000–Rp100.000</strong>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <small class="text-muted d-block mb-1">Lokasi acara</small>
                            <strong>Monumen Yos Sudarso, Kodiklatal, Bumimoro, Surabaya</strong>
                        </div>
                    </div>
                </div>

                <section class="mb-4">
                    <h2 class="h5 fw-bold">Tentang event</h2>
                    <p class="mb-0">Saatnya tunjukkan kemampuan terbaikmu dalam Merah Putih Airsoft Challenge. Ikuti pertandingan seru, susun strategi bersama tim, dan rasakan pengalaman kompetisi airsoft yang penuh tantangan.</p>
                </section>

                <section class="mb-4">
                    <h2 class="h5 fw-bold">Daya tarik pertandingan</h2>
                    <ul class="mb-0">
                        <li>Total hadiah hingga <strong>Rp50 juta</strong>.</li>
                        <li>Doorprize sepeda motor untuk tim terbaik dan tim kostum terbaik.</li>
                        <li>Terbuka untuk umum, TNI, Polri, dan komunitas airsoft.</li>
                    </ul>
                </section>

                <section class="mb-4">
                    <h2 class="h5 fw-bold">Kelas pertandingan</h2>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark border">3 on 3 Rifle</span>
                        <span class="badge bg-light text-dark border">3 on 3 Hand Gun</span>
                        <span class="badge bg-light text-dark border">Duelling Plat</span>
                    </div>
                </section>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button type="button" class="btn btn-primary">Daftar sekarang</button>
                    <button type="button" class="btn btn-outline-primary">Lihat sumber</button>
                </div>

                <div class="border-top pt-3">
                    <h2 class="h6 text-uppercase text-muted fw-bold">Kontributor</h2>
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('img/blankPhotoProfile.png') }}"
                             alt="Foto profil Andika"
                             class="rounded-circle"
                             width="40"
                             height="40">
                        <strong>Andika</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col">
            <div class="row">
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
            </div>
        </div>
    </div>
@endsection 