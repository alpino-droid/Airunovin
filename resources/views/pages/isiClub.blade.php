@extends('layout/app')

@php
    $namaClub = $club->nama;
    $deskripsi = $club->deskripsi ?: 'Informasi lengkap mengenai club belum tersedia.';
    $wilayah = collect([$club->city, $provinsi?->provinsi])->filter()->implode(', ');
    $kontributor = $club->user?->nama ?? 'Kontributor';
@endphp

@section('title', $namaClub . ' - Airunovin')

@push('styles')
<style>
    .club-detail__hero,
    .club-detail__panel {
        border: 1px solid #dfe5e8;
        background: #fff;
        box-shadow: 0 10px 28px rgba(31, 49, 58, .07);
    }

    .club-detail__logo {
        min-height: 360px;
        background: linear-gradient(135deg, #eef7f4, #f7f9f8);
    }

    .club-detail__logo img {
        max-width: 82%;
        max-height: 330px;
        object-fit: contain;
    }

    .club-detail__stat {
        background: #f7faf9;
        border: 1px solid #e6eeeb;
    }
</style>
@endpush

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="Breadcrumb" class="mb-4">
            <a href="{{ route('club') }}" class="text-decoration-none">Daftar Club</a>
            <span class="text-muted mx-2">/</span>
            <span class="text-muted">{{ $club->nama }}</span>
        </nav>

        <div class="club-detail__hero rounded-3 p-3 p-lg-4 row g-4 g-lg-5 align-items-start">
            <div class="col-12 col-lg-5">
            <div class="club-detail__logo rounded overflow-hidden d-flex align-items-center justify-content-center">
                    <img src="{{ $club->logo_url }}"
                        alt="Logo {{ $namaClub }}"
                        class="img-fluid"
                        style="max-height: 360px; object-fit: contain;">
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <p class="text-uppercase text-primary small fw-semibold mb-2">Profil Club</p>
                <h1 class="display-6 fw-bold mb-3">{{ $namaClub }}</h1>
                <p class="lead text-muted mb-4">{{ $deskripsi }}</p>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="club-detail__stat rounded p-3 h-100">
                            <small class="text-muted d-block mb-1">Wilayah</small>
                            <strong>{{ $wilayah ?: '-' }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="club-detail__stat rounded p-3 h-100">
                            <small class="text-muted d-block mb-1">Organisasi induk</small>
                            <strong>{{ $club->induk_organisasi ?: 'Mandiri' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    @if ($club->gform_link)
                        <a href="{{ $club->gform_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1" aria-hidden="true"></i>
                            Pendaftaran
                        </a>
                    @else
                        <button type="button" class="btn btn-primary" disabled title="Link pendaftaran belum tersedia">
                            <i class="bi bi-person-plus me-1" aria-hidden="true"></i>
                            Pendaftaran belum tersedia
                        </button>
                    @endif
                    <a href="{{ route('club') }}" class="btn btn-outline-secondary">Kembali ke Daftar Club</a>
                </div>

                <section aria-labelledby="deskripsi-club" class="mt-4">
                    <h2 id="deskripsi-club" class="h5">Tentang Club</h2>
                    <p class="mb-0" style="white-space: pre-line;">{{ $deskripsi }}</p>
                </section>

                <section class="mt-4" aria-labelledby="kontributor-club">
                    <h2 id="kontributor-club" class="h5">Kontributor</h2>
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ $club->user?->profile_picture_url ?? asset('img/blankPhotoProfile.png') }}"
                            alt="Profil {{ $kontributor }}"
                            width="40"
                            height="40"
                            class="rounded-circle object-fit-cover">
                        <span class="fw-semibold">{{ $kontributor }}</span>
                    </div>
                </section>
            </div>
        </div>

        @if($clubs->isNotEmpty())
            <section class="mt-5" aria-labelledby="club-lainnya">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <p class="text-uppercase text-muted small fw-semibold mb-1">Komunitas lain</p>
                        <h2 id="club-lainnya" class="h4 fw-bold mb-0">Club lainnya</h2>
                    </div>
                    <a href="{{ route('club') }}" class="small text-decoration-none">Lihat semua</a>
                </div>
                <div class="row g-3">
                    @foreach($clubs as $relatedClub)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <a href="{{ route('isiClub', $relatedClub) }}" class="club-detail__panel rounded-3 p-3 d-flex align-items-center gap-3 text-decoration-none text-dark h-100">
                                <img src="{{ $relatedClub->logo_url }}" alt="Logo {{ $relatedClub->nama }}" width="56" height="56" class="rounded-2 object-fit-cover bg-light">
                                <span class="min-w-0">
                                    <strong class="d-block text-truncate">{{ $relatedClub->nama }}</strong>
                                    <small class="text-muted">{{ $relatedClub->city ?: 'Lokasi belum diisi' }}</small>
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
