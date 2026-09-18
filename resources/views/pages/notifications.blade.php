@extends('layout/app')

@section('title', 'Pusat Notifikasi - Airunovin')

@push('styles')
<style>
    .notification-card {
        border-radius: 12px;
        padding: 1.25rem;
        background: #ffffff;
        border: 1px solid #edf2f7;
        margin-bottom: 0.85rem;
        transition: all 0.2s ease;
        position: relative;
    }

    .notification-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }

    .notification-card.unread {
        background: #f8fafc;
        border-left: 4px solid var(--deep-red, #8b1e1e);
    }

    .notification-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--deep-red, #8b1e1e);
        display: inline-block;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5">
    {{-- Notifikasi Flash Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header Halaman & Filter --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Pemberitahuan</p>
            <h1 class="h2 fw-bold mb-0">Pusat Notifikasi</h1>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <div class="btn-group shadow-sm" role="group" aria-label="Filter notifikasi">
                <a href="{{ route('notifications') }}" class="btn btn-sm {{ $mode === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Semua
                </a>
                <a href="{{ route('notifications.seller') }}" class="btn btn-sm {{ $mode === 'seller' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Penjual
                </a>
                <a href="{{ route('notifications.buyer') }}" class="btn btn-sm {{ $mode === 'buyer' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Pembeli
                </a>
            </div>

            @if($unreadTotal > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Tandai semua notifikasi sudah dibaca">
                        <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Daftar Notifikasi --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark">
                            @if($mode === 'seller')
                                Notifikasi Penjual
                            @elseif($mode === 'buyer')
                                Notifikasi Pembeli
                            @else
                                Semua Notifikasi
                            @endif
                        </h5>
                        @if($unreadActive > 0)
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                {{ $unreadActive }} belum dibaca
                            </span>
                        @else
                            <span class="badge bg-light text-muted border rounded-pill px-3 py-1 small">
                                Semua sudah dibaca
                            </span>
                        @endif
                    </div>

                    <div class="notification-list">
                        @forelse ($notifications as $item)
                            <div class="notification-card {{ $item->isUnread() ? 'unread' : '' }}">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        @if($item->isUnread())
                                            <span class="notification-dot" title="Belum dibaca"></span>
                                        @endif
                                        <span class="badge {{ $item->type_badge_class }}">
                                            <i class="bi {{ $item->type_icon }} me-1"></i>{{ $item->type }}
                                        </span>
                                        <strong class="text-dark">{{ $item->title }}</strong>
                                    </div>
                                    <small class="text-muted text-nowrap">{{ $item->created_at->diffForHumans() }}</small>
                                </div>

                                <p class="mb-2 mt-2 text-muted small" style="line-height: 1.5;">
                                    {{ $item->message }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('notifications.detail', ['id' => $item->id, 'mode' => $mode]) }}" class="text-primary fw-semibold text-decoration-none small">
                                            Lihat detail <i class="bi bi-arrow-right"></i>
                                        </a>

                                        @if($item->link)
                                            <a href="{{ $item->link }}" class="text-secondary text-decoration-none small">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka halaman
                                            </a>
                                        @endif
                                    </div>

                                    @if($item->isUnread())
                                        <form action="{{ route('notifications.markRead', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-muted p-0 text-decoration-none small" title="Tandai telah dibaca">
                                                <i class="bi bi-check2"></i> Tandai dibaca
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary"></i>
                                <p class="mb-0">Tidak ada notifikasi dalam kategori ini.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Paginasi jika lebih dari 1 halaman --}}
                    @if($notifications->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Ringkasan Status --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Ringkasan Notifikasi</h5>
                    <div class="d-grid gap-3">
                        <div class="bg-light rounded-3 p-3 border">
                            <div class="small text-muted mb-1">Belum Dibaca</div>
                            <div class="fs-3 fw-bold text-danger">{{ $unreadTotal }}</div>
                        </div>

                        <div class="bg-light rounded-3 p-3 border">
                            <div class="small text-muted mb-1">Total Notifikasi</div>
                            <div class="fs-3 fw-bold text-primary">{{ $totalNotifications }}</div>
                        </div>

                        <div class="bg-light rounded-3 p-3 border">
                            <div class="small text-muted mb-1">Filter Kategori Aktif</div>
                            <div class="fs-5 fw-bold text-dark text-capitalize">
                                @if($mode === 'seller')
                                    Penjual ({{ $sellerCount }})
                                @elseif($mode === 'buyer')
                                    Pembeli ({{ $buyerCount }})
                                @else
                                    Semua Kategori
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
