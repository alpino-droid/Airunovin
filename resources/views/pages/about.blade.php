@extends('layout/app')

@section('title', 'Tentang Kami')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <p class="text-uppercase text-primary fw-semibold small mb-2">Airunovin</p>
            <h1 class="display-5 fw-bold mb-4">Tentang Kami</h1>
            <p class="lead text-muted mb-5">Airunovin membantu komunitas airsoft menemukan event, club, dan kebutuhan perlengkapan dalam satu tempat.</p>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="border rounded p-4 h-100">
                        <i class="bi bi-calendar-event fs-2 text-primary"></i>
                        <h2 class="h5 fw-bold mt-3">Event</h2>
                        <p class="text-muted mb-0">Temukan jadwal kompetisi dan kegiatan airsoft dari berbagai daerah.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 h-100">
                        <i class="bi bi-people fs-2 text-primary"></i>
                        <h2 class="h5 fw-bold mt-3">Club</h2>
                        <p class="text-muted mb-0">Kenali club dan komunitas untuk membangun koneksi baru.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 h-100">
                        <i class="bi bi-shop fs-2 text-primary"></i>
                        <h2 class="h5 fw-bold mt-3">Marketplace</h2>
                        <p class="text-muted mb-0">Jelajahi perlengkapan dan kebutuhan airsoft dari komunitas.</p>
                    </div>
                </div>
            </div>

            <h2 class="h4 fw-bold">Misi kami</h2>
            <p class="text-muted">Kami membangun ruang digital yang informatif, mudah digunakan, dan mendukung pertumbuhan komunitas airsoft Indonesia secara positif dan bertanggung jawab.</p>
        </div>
    </div>
</div>
@endsection
