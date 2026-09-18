@extends('layout/app')

@section('title', $notification->title . ' - Notifikasi')

@section('content')
<div class="container py-4 py-lg-5">
    {{-- Breadcrumb --}}
    <nav aria-label="Breadcrumb" class="mb-4 small">
        <a href="{{ $mode === 'seller' ? route('notifications.seller') : ($mode === 'buyer' ? route('notifications.buyer') : route('notifications')) }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i> Notifikasi
        </a>
        <span class="text-muted mx-2">/</span>
        <span class="text-muted">{{ $notification->type }}</span>
        <span class="text-muted mx-2">/</span>
        <span class="text-dark fw-semibold text-truncate d-inline-block align-bottom" style="max-width: 250px;">
            {{ $notification->title }}
        </span>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <article class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    {{-- Header Notifikasi --}}
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <span class="badge {{ $notification->type_badge_class }} mb-2 px-3 py-2">
                                <i class="bi {{ $notification->type_icon }} me-1"></i>{{ $notification->type }}
                            </span>
                            <h1 class="h3 fw-bold mb-0 text-dark">{{ $notification->title }}</h1>
                        </div>
                        <small class="text-muted text-nowrap">
                            {{ $notification->created_at->translatedFormat('d M Y, H:i') }}
                            <span class="d-block text-end small">({{ $notification->created_at->diffForHumans() }})</span>
                        </small>
                    </div>

                    {{-- Isi Pesan --}}
                    <div class="p-3 bg-light rounded-3 mb-4 border">
                        <p class="text-secondary mb-0" style="white-space: pre-line; line-height: 1.6; font-size: 1.05rem;">
                            {{ $notification->message }}
                        </p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3 border-top">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ $mode === 'seller' ? route('notifications.seller') : ($mode === 'buyer' ? route('notifications.buyer') : route('notifications')) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Notifikasi
                            </a>

                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Halaman Terkait
                                </a>
                            @endif
                        </div>

                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" title="Hapus notifikasi ini">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection
