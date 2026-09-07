@extends('layout/app')

@section('title', 'Notifikasi')

@section('content')
    @php
        $mode = Request::segment(2) ?? 'all';

        $sellerNotifications = [
            ['title' => 'Pembelian baru', 'type' => 'Penjualan', 'typeClass' => 'bg-success-subtle text-success', 'message' => 'Produk "Rantai Matic" berhasil dibeli oleh pelanggan.', 'time' => '2 menit lalu', 'unread' => true],
            ['title' => 'Pesanan dikirim', 'type' => 'Pengiriman', 'typeClass' => 'bg-warning-subtle text-warning', 'message' => 'Status pengiriman untuk order #MK-2048 sudah diperbarui.', 'time' => '1 jam lalu', 'unread' => true],
            ['title' => 'Stok menipis', 'type' => 'Inventaris', 'typeClass' => 'bg-danger-subtle text-danger', 'message' => 'Stok produk "Ban Dalam 14 inch" tersisa 3 unit.', 'time' => '3 jam lalu', 'unread' => false],
            ['title' => 'Review pelanggan', 'type' => 'Feedback', 'typeClass' => 'bg-info-subtle text-info', 'message' => 'Ada review baru dengan rating 5 dari pembeli.', 'time' => '1 hari lalu', 'unread' => false],
        ];

        $buyerNotifications = [
            ['title' => 'Pembayaran berhasil', 'type' => 'Transaksi', 'typeClass' => 'bg-success-subtle text-success', 'message' => 'Pembayaran untuk produk "Helm Fullface" berhasil diproses.', 'time' => '10 menit lalu', 'unread' => true],
            ['title' => 'Barang dikirim', 'type' => 'Pengiriman', 'typeClass' => 'bg-primary-subtle text-primary', 'message' => 'Pesanan Anda sedang dikirim dengan nomor resi JNE-2048.', 'time' => '2 jam lalu', 'unread' => true],
            ['title' => 'Promo terbaru', 'type' => 'Promo', 'typeClass' => 'bg-warning-subtle text-warning', 'message' => 'Diskon 20% untuk sparepart motor hari ini.', 'time' => '5 jam lalu', 'unread' => false],
            ['title' => 'Pesanan diterima', 'type' => 'Status', 'typeClass' => 'bg-secondary-subtle text-secondary', 'message' => 'Barang telah diterima dengan kondisi baik.', 'time' => '2 hari lalu', 'unread' => false],
        ];

        $allNotifications = array_merge($sellerNotifications, $buyerNotifications);

        $items = $mode === 'penjual' ? $sellerNotifications : ($mode === 'pembeli' ? $buyerNotifications : $allNotifications);
        $pageTitle = $mode === 'penjual' ? 'Notifikasi Penjual' : ($mode === 'pembeli' ? 'Notifikasi Pembeli' : 'Semua Notifikasi');
        $badgeCount = 0;
        foreach ($items as $item) {
            if ($item['unread']) {
                $badgeCount++;
            }
        }
    @endphp

    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <p class="text-uppercase text-primary fw-semibold small mb-1">Notifikasi</p>
                <h1 class="h2 fw-bold mb-0">Pusat Notifikasi</h1>
            </div>
            <div class="btn-group" role="group" aria-label="Filter notifikasi">
                <a href="{{ url('/notifikasi') }}" class="btn btn-sm {{ $mode === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
                <a href="{{ url('/notifikasi/penjual') }}" class="btn btn-sm {{ $mode === 'penjual' ? 'btn-primary' : 'btn-outline-primary' }}">Penjual</a>
                <a href="{{ url('/notifikasi/pembeli') }}" class="btn btn-sm {{ $mode === 'pembeli' ? 'btn-primary' : 'btn-outline-primary' }}">Pembeli</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">{{ $pageTitle }}</h5>
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">{{ $badgeCount }} baru</span>
                        </div>

                        <div class="notification-list">
                            @foreach ($items as $index => $item)
                                <div class="notification-card {{ $item['unread'] ? 'unread' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <span class="badge {{ $item['typeClass'] }} me-2">{{ $item['type'] }}</span>
                                            <strong>{{ $item['title'] }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $item['time'] }}</small>
                                    </div>
                                    <p class="mb-1 mt-3 text-muted">{{ $item['message'] }}</p>
                                    <a href="{{ route('notifications.detail', ['mode' => $mode, 'index' => $index]) }}" class="text-primary fw-semibold text-decoration-none">Lihat detail</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Ringkasan</h5>
                        <div class="d-grid gap-3">
                            <div class="bg-light rounded-4 p-3">
                                <div class="small text-muted">Belum dibaca</div>
                                <div class="fs-3 fw-bold text-primary">{{ $badgeCount }}</div>
                            </div>
                            <div class="bg-light rounded-4 p-3">
                                <div class="small text-muted">Transaksi hari ini</div>
                                <div class="fs-3 fw-bold text-success">12</div>
                            </div>
                            <div class="bg-light rounded-4 p-3">
                                <div class="small text-muted">Status aktif</div>
                                <div class="fs-3 fw-bold text-info">{{ $mode === 'penjual' ? 'Penjual' : ($mode === 'pembeli' ? 'Pembeli' : 'Semua') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
