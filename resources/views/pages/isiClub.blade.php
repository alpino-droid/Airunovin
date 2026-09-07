@extends('layout/app')

@php
    $clubData = $club ?? null;
    $namaClub = $clubData?->nama ?? 'Nama Club';
    $deskripsi = $clubData?->deskripsi ?? 'Informasi lengkap mengenai club akan ditampilkan di sini.';
    $wilayah = collect([$clubData?->city, $clubData?->province])->filter()->implode(', ');
    $kontributor = $clubData?->user?->nama ?? 'Kontributor';
@endphp

@section('title', $namaClub . ' - Airunovin')

@section('content')
<div class="container py-4 py-lg-5">
    <nav aria-label="Breadcrumb" class="mb-4">
        <a href="{{ route('club') }}" class="text-decoration-none">Daftar Club</a>
        <span class="text-muted mx-2">/</span>
        <span class="text-muted">{{ $namaClub }}</span>
    </nav>

    <div class="row g-4 g-lg-5 align-items-start">
        <div class="col-12 col-lg-5">
            <div class="bg-light rounded overflow-hidden d-flex align-items-center justify-content-center" style="min-height: 360px;">
                <img src="{{ $clubData?->logo_url ?? asset('img/images.png') }}"
                     alt="Logo {{ $namaClub }}"
                     class="img-fluid"
                     style="max-height: 360px; object-fit: contain;">
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <p class="text-uppercase text-muted small fw-semibold mb-2">Profil Club</p>
            <h1 class="display-6 fw-bold mb-3">{{ $namaClub }}</h1>

            <dl class="row mb-4">
                <dt class="col-sm-4">Wilayah</dt>
                <dd class="col-sm-8">{{ $wilayah ?: 'Belum tersedia' }}</dd>
                <dt class="col-sm-4">Organisasi induk</dt>
                <dd class="col-sm-8">{{ $clubData?->induk_organisasi ?: 'Mandiri' }}</dd>
            </dl>

            <div class="d-flex flex-wrap gap-2 mb-4">
                @if ($clubData?->gform_link)
                    <a href="{{ $clubData->gform_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
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

            <section aria-labelledby="deskripsi-club">
                <h2 id="deskripsi-club" class="h5">Tentang Club</h2>
                <p class="mb-0" style="white-space: pre-line;">{{ $deskripsi }}</p>
            </section>

            <section class="mt-4" aria-labelledby="kontributor-club">
                <h2 id="kontributor-club" class="h5">Kontributor</h2>
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('img/blankPhotoProfile.png') }}"
                         alt="Profil {{ $kontributor }}"
                         width="40"
                         height="40"
                         class="rounded-circle object-fit-cover">
                    <span class="fw-semibold">{{ $kontributor }}</span>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
