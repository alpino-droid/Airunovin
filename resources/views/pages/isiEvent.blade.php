@extends('layout/app')

@section('title', 'Home - Laravel Blade')

@section('content')
    @php $posters = $event->poster ?? []; @endphp

    <div class="container text-start pt-3">
       <div class="row align-items-start">
            <div class="col-4">
                <div class="standard-poster-frame">
                                        @if(count($posters) > 0)
                                            <img src="{{ asset('storage/' . $posters[0]) }}"
                                                alt="Poster {{ $event->nama }}"
                                                class="event-main-poster"
                                                data-bs-toggle="modal"
                                                data-bs-target="#eventImageModal"
                                                data-image="{{ asset('storage/' . $posters[0]) }}"
                                                style="cursor: zoom-in;">
                                        @else
                                            <span class="text-muted">Tidak ada poster</span>
                                        @endif
                                    </div>

                @if(count($posters) > 0)
                    <div class="d-flex flex-wrap gap-2 mt-2" aria-label="Galeri poster event">
                        @foreach($posters as $poster)
                            <button type="button"
                                class="border-0 p-0 bg-transparent event-poster-thumb"
                                data-bs-toggle="modal"
                                data-bs-target="#eventImageModal"
                                data-image="{{ asset('storage/' . $poster) }}"
                                aria-label="Buka poster event">
                                <img src="{{ asset('storage/' . $poster) }}"
                                    alt="Poster {{ $event->nama }}"
                                    style="width: 64px; height: 96px; object-fit: cover; border-radius: 6px;">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col">

                <h1 class="h2 fw-bold mb-3">{{ $event->nama }}</h1>
                <p class="lead text-muted mb-4">{{ $event->deskripsi ?: 'Belum ada deskripsi untuk event ini.' }}</p>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block mb-1">Tanggal</small>
                            <strong>{{ $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') : '-' }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted d-block mb-1">Biaya pendaftaran</small>
                            <strong>{{ $event->htm !== null ? 'Rp' . number_format($event->htm, 0, ',', '.') : 'Gratis' }}</strong>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <small class="text-muted d-block mb-1">Lokasi acara</small>
                            <strong>{{ $event->lokasi ?: '-' }}{{ $event->kota ? ', ' . $event->kota : '' }}</strong>
                        </div>
                    </div>
                </div>

                <section class="mb-4">
                    <h2 class="h5 fw-bold">Tentang event</h2>
                    <p class="mb-0">{{ $event->deskripsi ?: 'Belum ada informasi tentang event ini.' }}</p>
                </section>

                <section class="mb-4">
                    <h2 class="h5 fw-bold">Daya tarik pertandingan</h2>
                    <ul class="mb-0">
                        <li>Penyelenggara: <strong>{{ $event->penyelenggara }}</strong>.</li>
                        <li>Kota: <strong>{{ $event->kota }}</strong>.</li>
                        @if($event->sumber)
                            <li><a href="{{ $event->sumber }}" target="_blank" rel="noopener">Lihat sumber event</a></li>
                        @endif
                    </ul>
                </section>

                <section class="mb-4">
                    <h2 class="h5 fw-bold">Kelas pertandingan</h2>
                    @if($event->kelasPertandingan)
                        <p class="mb-0">{{ $event->kelasPertandingan }}</p>
                    @else
                        <p class="text-muted mb-0">Belum ada kelas pertandingan yang dicantumkan.</p>
                    @endif
                </section>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    @if($event->sumber)
                        <a href="{{ $event->sumber }}" target="_blank" rel="noopener" class="btn btn-outline-primary">Lihat sumber</a>
                    @endif
                </div>

                <div class="border-top pt-3">
                    <h2 class="h6 text-uppercase text-muted fw-bold">Kontributor</h2>
                    <div class="d-flex align-items-center gap-2">
                            <img src="{{ $event->user?->profile_picture_url ?? asset('img/blankPhotoProfile.png') }}"
                                alt="Foto profil {{ $event->user?->nama ?? 'kontributor' }}"
                             class="rounded-circle"
                             width="40"
                             height="40">
                        <strong>{{ $event->user?->nama ?? $event->penyelenggara }}</strong>
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
                    <a href="{{ route('isiEvent', $evt) }}" class="text-decoration-none text-dark">
                        <div class="card h-100" style="width: 13rem;">
                            <div class="card-img-top standard-poster-frame">
                                @if($evt->poster && count($evt->poster) > 0)
                                    @php $posters = $evt->poster; @endphp
                                    <img src="{{ asset('storage/' . $posters[0]) }}" alt="Event poster" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy">
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

    @if(count($posters) > 0)
        <div class="modal fade" id="eventImageModal" tabindex="-1" aria-labelledby="eventImageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-dark border-0">
                    <div class="modal-header border-0">
                        <h2 class="modal-title text-white fs-6" id="eventImageModalLabel">{{ $event->nama }}</h2>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body text-center p-2 p-md-4">
                        <img id="eventModalImage"
                            src="{{ asset('storage/' . $posters[0]) }}"
                            alt="Poster {{ $event->nama }}"
                            style="max-width: 100%; max-height: 75vh; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection 

@push('scripts')
    <script>
        document.querySelectorAll('[data-bs-target="#eventImageModal"]').forEach((imageTrigger) => {
            imageTrigger.addEventListener('click', () => {
                const modalImage = document.getElementById('eventModalImage');
                modalImage.src = imageTrigger.dataset.image;
            });
        });
    </script>
@endpush