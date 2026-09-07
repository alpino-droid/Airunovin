@extends('layout/app')

@section('title', $item['title'])

@section('content')
<div class="container py-4 py-lg-5">
    <nav aria-label="Breadcrumb" class="mb-4">
        <a href="{{ route('notifications', ['mode' => $mode]) }}" class="text-decoration-none">Notifikasi</a>
        <span class="text-muted mx-2">/</span>
        <span class="text-muted">Lihat detail</span>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <article class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <span class="badge bg-primary-subtle text-primary mb-2">{{ $item['type'] }}</span>
                            <h1 class="h3 fw-bold mb-0">{{ $item['title'] }}</h1>
                        </div>
                        <small class="text-muted text-nowrap">{{ $item['time'] }}</small>
                    </div>

                    <p class="text-muted mb-4">{{ $item['message'] }}</p>

                    <a href="{{ route('notifications', ['mode' => $mode]) }}" class="btn btn-outline-primary">
                        Kembali ke notifikasi
                    </a>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection
