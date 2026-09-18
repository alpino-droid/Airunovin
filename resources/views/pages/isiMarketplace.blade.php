@extends('layout/app')

@section('title', $product->nama . ' - Marketplace')

@push('styles')
<style>
    .product-detail__media {
        background: #ffffff;
        border: 1px solid #e9ecef;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .product-detail__main-image {
        aspect-ratio: 1 / 1;
        width: 100%;
        max-height: 480px;
        background: #f8f9fa;
        overflow: hidden;
    }

    .product-detail__main-image img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .product-detail__main-image:hover img {
        transform: scale(1.03);
    }

    .product-detail__thumb {
        width: 54px;
        height: 54px;
        border: 2px solid transparent;
        background: #f8f9fa;
        padding: 2px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .product-detail__thumb img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .product-detail__thumb:hover {
        border-color: var(--deep-red, #8b1e1e);
        transform: translateY(-2px);
    }

    .product-detail__thumb.active {
        border-color: var(--deep-red, #8b1e1e);
        box-shadow: 0 0 0 2px rgba(139, 30, 30, 0.2);
    }

    .product-detail__summary,
    .product-detail__panel {
        background: #ffffff;
        border: 1px solid #e9ecef;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .product-detail__price {
        color: var(--deep-red, #8b1e1e);
        font-size: clamp(1.8rem, 4vw, 2.5rem);
    }

    .product-detail__seller-avatar {
        width: 48px;
        height: 48px;
        background: rgba(139, 30, 30, 0.1);
        color: var(--deep-red, #8b1e1e);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .payment-method-label {
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-check:checked + .btn-outline-primary {
        background-color: var(--deep-red, #8b1e1e) !important;
        border-color: var(--deep-red, #8b1e1e) !important;
        color: #fff !important;
    }

    .qty-btn {
        width: 38px;
    }
</style>
@endpush

@section('content')
    <div class="container py-4 py-lg-5">
        {{-- ==================== BREADCRUMB ==================== --}}
        <nav aria-label="Breadcrumb" class="mb-4 small">
            <a href="{{ route('marketplace') }}" class="text-decoration-none text-muted">Marketplace</a>
            <span class="text-muted mx-2">/</span>
            <span class="text-muted">{{ $product->jenis }}</span>
            <span class="text-muted mx-2">/</span>
            <span class="text-dark fw-semibold text-truncate d-inline-block align-bottom" style="max-width: 250px;">{{ $product->nama }}</span>
        </nav>

        <div class="row g-4 g-xl-5 align-items-start">
            {{-- ==================== KOLOM KIRI: GAMBAR PRODUK ==================== --}}
            <div class="col-12 col-lg-6">
                @php
                    $imgUrl = $product->gambar
                        ? (str_starts_with($product->gambar, 'http') ? $product->gambar : asset('storage/' . $product->gambar))
                        : asset('img/balnkLogo.png');
                @endphp

                <div class="product-detail__media rounded-3 overflow-hidden">
                    <div class="product-detail__main-image d-flex align-items-center justify-content-center p-3 position-relative">
                        <img id="productMainImage" src="{{ $imgUrl }}" alt="{{ $product->nama }}" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                    </div>

                    <div class="d-flex flex-wrap gap-2 p-3 border-top bg-light" aria-label="Pilih foto produk">
                        <button type="button"
                                class="product-detail__thumb active"
                                data-image="{{ $imgUrl }}"
                                aria-label="Foto utama {{ $product->nama }}">
                            <img src="{{ $imgUrl }}" alt="{{ $product->nama }}" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                        </button>
                    </div>
                </div>
            </div>

            {{-- ==================== KOLOM KANAN: DETAIL PRODUK ==================== --}}
            <div class="col-12 col-lg-6">
                <div class="product-detail__summary rounded-3 p-4 p-lg-5">
                    <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
                        <div>
                            <p class="product-detail__eyebrow text-uppercase text-primary small fw-bold mb-1">{{ $product->jenis }}</p>
                            <h1 class="h2 fw-bold mb-0 text-dark">{{ $product->nama }}</h1>
                        </div>

                        {{-- Status Ketersediaan Stok --}}
                        <div>
                            @if ($product->stok > 3)
                                <span class="badge text-bg-success rounded-pill px-3 py-2">
                                    <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Tersedia
                                </span>
                            @elseif ($product->stok > 0)
                                <span class="badge text-bg-warning rounded-pill px-3 py-2 text-dark">
                                    <i class="bi bi-exclamation-circle me-1" aria-hidden="true"></i>Sisa {{ $product->stok }} Unit
                                </span>
                            @else
                                <span class="badge text-bg-danger rounded-pill px-3 py-2">
                                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Stok Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Harga Produk --}}
                    <p class="product-detail__price fw-bold mb-4">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>

                    {{-- Spesifikasi Cepat --}}
                    <div class="row g-3 border-top border-bottom py-3 mb-4 small">
                        <div class="col-6 col-sm-3">
                            <span class="text-muted d-block">Merek</span>
                            <strong>{{ $product->merk }}</strong>
                        </div>
                        <div class="col-6 col-sm-3">
                            <span class="text-muted d-block">Kondisi</span>
                            <strong>{{ $product->kondisi }}</strong>
                        </div>
                        <div class="col-6 col-sm-3">
                            <span class="text-muted d-block">Stok</span>
                            <strong>{{ $product->stok }} unit</strong>
                        </div>
                        <div class="col-6 col-sm-3">
                            <span class="text-muted d-block">Lokasi</span>
                            <strong>{{ $product->lokasi }}</strong>
                        </div>
                    </div>

                    {{-- Metode Pembayaran Diterima --}}
                    @php
                        $acceptedPayments = is_array($product->payment_methods)
                            ? $product->payment_methods
                            : (json_decode($product->payment_methods ?? '[]', true) ?: ['Transfer Bank', 'QRIS', 'COD']);
                    @endphp

                    @if(!empty($acceptedPayments))
                        <div class="mb-4">
                            <span class="text-muted small d-block mb-2 fw-semibold">Metode Pembayaran Tersedia:</span>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($acceptedPayments as $payment)
                                    <span class="badge bg-light text-dark border px-2 py-1 small fw-normal">
                                        <i class="bi bi-credit-card text-primary me-1" aria-hidden="true"></i>{{ $payment }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Deskripsi Produk --}}
                    <h2 class="h5 fw-bold mb-2">Tentang produk</h2>
                    <p class="text-muted mb-4" style="white-space: pre-line; line-height: 1.6;">{{ $product->deskripsi ?: 'Tidak ada deskripsi untuk produk ini.' }}</p>

                    {{-- Tombol Tindakan --}}
                    <div class="d-grid gap-2 d-sm-flex">
                        @if ($product->stok > 0)
                            <button type="button" class="btn btn-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#buyProductModal">
                                <i class="bi bi-bag-check me-1" aria-hidden="true"></i> Beli sekarang
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary flex-grow-1" disabled>
                                <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Stok Habis
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#shareProductModal">
                            <i class="bi bi-share me-1" aria-hidden="true"></i> Bagikan
                        </button>
                    </div>
                </div>

                {{-- ==================== KARTU PENJUAL ==================== --}}
                @php
                    $sellerStore = $product->marketplace->nama ?? null;
                    $sellerUser = $product->user->nama ?? 'Penjual Airsoft';
                    $displayName = $sellerStore ?: $sellerUser;
                    $words = preg_split('/\s+/', trim($displayName));
                    $initials = count($words) >= 2
                        ? strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1))
                        : strtoupper(mb_substr($displayName, 0, 2));
                    $sellerPhone = $product->user->phone ?? null;
                    $cleanPhone = $sellerPhone ? preg_replace('/[^0-9]/', '', $sellerPhone) : null;
                    if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                @endphp

                <div class="product-detail__panel rounded-3 p-3 mt-3 d-flex align-items-center gap-3">
                    <a href="{{ route('market') }}" class="text-decoration-none" title="Lihat profil penjual">
                        @if ($product->marketplace && $product->marketplace->logo)
                            <img src="{{ asset('storage/' . $product->marketplace->logo) }}" alt="{{ $displayName }}" class="product-detail__seller-avatar rounded-circle object-fit-cover" onerror="this.onerror=null; this.replaceWith(document.createElement('div'))">
                        @else
                            <div class="product-detail__seller-avatar rounded-circle d-flex align-items-center justify-content-center fw-bold">
                                {{ $initials }}
                            </div>
                        @endif
                    </a>

                    <div class="flex-grow-1">
                        <span class="text-muted small d-block">Dijual oleh</span>
                        <strong class="text-dark d-block">{{ $displayName }}</strong>
                        @if ($sellerStore && $sellerUser)
                            <span class="text-muted small">{{ $sellerUser }} &bull; <i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $product->lokasi }}</span>
                        @else
                            <span class="text-muted small"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $product->lokasi }}</span>
                        @endif
                    </div>

                    @if ($cleanPhone)
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo ' . $displayName . ', saya tertarik dengan produk ' . $product->nama . ' di Airunovin Marketplace: ' . url()->current()) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-outline-success btn-sm d-flex align-items-center gap-1"
                           title="Chat penjual via WhatsApp">
                            <i class="bi bi-whatsapp" aria-hidden="true"></i>
                            <span class="d-none d-sm-inline">Chat</span>
                        </a>
                    @else
                        <span class="badge text-bg-light border">Penjual aktif</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ==================== PRODUK SERUPA ==================== --}}
        <section class="mt-5" aria-labelledby="produk-serupa">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div>
                    <p class="text-uppercase text-muted small fw-semibold mb-1">Lihat lainnya</p>
                    <h2 id="produk-serupa" class="h4 fw-bold mb-0">Produk serupa</h2>
                </div>
                <a href="{{ route('marketplace') }}" class="small text-decoration-none">Lihat semua</a>
            </div>

            <div class="row justify-content-center g-2">
                @php $hasSimilar = false; @endphp
                @foreach ($products as $produk)
                    @if ($produk->id !== $product->id)
                        @php $hasSimilar = true; @endphp
                        <div class="col-auto mb-5">
                            <a href="{{ route('isiMarketplace', $produk->id) }}" class="text-decoration-none text-dark">
                                <div class="card h-100" style="width: 13rem;">
                                    <div class="card-img-top product-image-frame">
                                        @if ($produk->gambar)
                                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                                        @else
                                            <img src="{{ asset('img/balnkLogo.png') }}" alt="{{ $produk->nama }}" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold text-truncate" title="{{ $produk->nama }}">{{ $produk->nama }}</h5>
                                        <ul class="list-unstyled small mb-2">
                                            <li class="text-success fw-semibold mb-1">Rp {{ number_format($produk->harga, 0, ',', '.') }}</li>
                                            <li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">{{ $produk->merk }}</span></li>
                                            <li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">{{ $produk->jenis }}</span></li>
                                        </ul>
                                        <div class="mt-auto pt-2 border-top text-muted small"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $produk->lokasi }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach

                @if (!$hasSimilar)
                    <div class="col-12 text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                        Tidak ada produk serupa lainnya saat ini
                    </div>
                @endif
            </div>
        </section>
    </div>

    {{-- ==================== MODAL BAGIKAN PRODUK ==================== --}}
    <div class="modal fade" id="shareProductModal" tabindex="-1" aria-labelledby="shareProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="shareProductLabel">
                        <i class="bi bi-share me-1 text-primary" aria-hidden="true"></i> Bagikan Produk
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Bagikan tautan produk ini ke teman atau komunitas airsoft Anda:</p>

                    <label for="productUrl" class="form-label small fw-semibold text-muted">Tautan Produk</label>
                    <div class="input-group mb-3">
                        <input id="productUrl" class="form-control bg-light" value="{{ url()->current() }}" readonly>
                        <button type="button" class="btn btn-outline-primary" id="btnCopyProductUrl" title="Salin ke papan klip">
                            <i class="bi bi-clipboard" id="copyIcon" aria-hidden="true"></i>
                            <span id="copyBtnText" class="ms-1">Salin</span>
                        </button>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode('Cek produk airsoft ini: ' . $product->nama . ' seharga Rp ' . number_format($product->harga, 0, ',', '.') . ' di Airunovin: ' . url()->current()) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-success">
                            <i class="bi bi-whatsapp me-1" aria-hidden="true"></i> Bagikan ke WhatsApp
                        </a>

                        <button type="button" class="btn btn-outline-secondary d-none" id="btnNativeShare">
                            <i class="bi bi-send me-1" aria-hidden="true"></i> Bagikan via Aplikasi Lain
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL CHECKOUT PRODUK ==================== --}}
    <div class="modal fade" id="buyProductModal" tabindex="-1" aria-labelledby="buyProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h2 class="modal-title h5 fw-bold" id="buyProductLabel">
                        <i class="bi bi-cart-check me-1 text-primary" aria-hidden="true"></i> Checkout Produk
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    {{-- Ringkasan Produk --}}
                    <div class="d-flex gap-3 align-items-center border-bottom pb-3 mb-3">
                        <img src="{{ $imgUrl }}" alt="{{ $product->nama }}" width="64" height="64" class="rounded-2 bg-light border p-1" style="object-fit: contain;" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                        <div class="flex-grow-1">
                            <strong class="d-block text-dark text-truncate" style="max-width: 280px;" title="{{ $product->nama }}">{{ $product->nama }}</strong>
                            <span class="text-success fw-bold">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <span class="text-muted small d-block">Stok tersedia: {{ $product->stok }} unit</span>
                        </div>
                    </div>

                    <form id="purchaseForm" novalidate>
                        {{-- Data Pembeli --}}
                        <div class="mb-3">
                            <label for="buyerName" class="form-label fw-semibold small">Nama Pembeli <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="buyerName" placeholder="Masukkan nama lengkap Anda" required autocomplete="name">
                            <div class="invalid-feedback">Silakan masukkan nama Anda.</div>
                        </div>

                        <div class="mb-3">
                            <label for="buyerPhone" class="form-label fw-semibold small">Nomor WhatsApp Pembeli <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="buyerPhone" required autocomplete="tel" placeholder="Contoh: 081234567890">
                            <div class="invalid-feedback">Silakan masukkan nomor WhatsApp yang aktif.</div>
                        </div>

                        {{-- Jumlah Pesanan dengan Stepper --}}
                        <div class="mb-3">
                            <label for="productQuantity" class="form-label fw-semibold small">Jumlah Unit</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary qty-btn" type="button" id="btnQtyMinus" aria-label="Kurangi kuantitas">
                                    <i class="bi bi-dash" aria-hidden="true"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="productQuantity" value="1" min="1" max="{{ max(1, $product->stok) }}" required>
                                <button class="btn btn-outline-secondary qty-btn" type="button" id="btnQtyPlus" aria-label="Tambah kuantitas">
                                    <i class="bi bi-plus" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="form-text">Maksimal {{ $product->stok }} unit per pemesanan.</div>
                        </div>

                        {{-- Metode Pengiriman --}}
                        <div class="mb-3">
                            <label for="deliveryMethod" class="form-label fw-semibold small">Metode Pengiriman</label>
                            <select class="form-select" id="deliveryMethod">
                                <option value="0" data-label="Ambil di lokasi penjual (COD)">Ambil di lokasi penjual (COD) - Gratis</option>
                                <option value="25000" data-label="Kurir Reguler">Kurir Reguler - Rp 25.000</option>
                                <option value="40000" data-label="Kurir Express / Kargo Taktikal">Kurir Express / Kargo Taktikal - Rp 40.000</option>
                            </select>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <fieldset class="mb-3">
                            <legend class="form-label fw-semibold small mb-2">Metode Pembayaran <span class="text-danger">*</span></legend>
                            <div class="row g-2">
                                @forelse ($acceptedPayments as $index => $method)
                                    @php $methodSlug = \Illuminate\Support\Str::slug($method, '_'); @endphp
                                    <div class="col-6">
                                        <input class="btn-check" type="radio" name="paymentMethod" id="payment_{{ $methodSlug }}" value="{{ $method }}" {{ $index === 0 ? 'checked' : '' }} required>
                                        <label class="btn btn-outline-primary w-100 text-start small py-2 text-truncate" for="payment_{{ $methodSlug }}">
                                            <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>{{ $method }}
                                        </label>
                                    </div>
                                @empty
                                    <div class="col-6">
                                        <input class="btn-check" type="radio" name="paymentMethod" id="payment_transfer" value="Transfer Bank" checked required>
                                        <label class="btn btn-outline-primary w-100 text-start small py-2" for="payment_transfer">
                                            <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Transfer Bank
                                        </label>
                                    </div>
                                @endforelse
                            </div>
                        </fieldset>

                        {{-- Rincian Biaya --}}
                        <div class="bg-light rounded-3 p-3 mb-3 border">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Subtotal Produk</span>
                                <span id="productSubtotal" class="fw-semibold">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">Biaya Pengiriman</span>
                                <span id="deliveryCost" class="fw-semibold">Gratis</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2 fw-bold">
                                <span>Total Pembayaran</span>
                                <span class="text-success fs-5" id="productTotal">Rp 0</span>
                            </div>
                        </div>

                        {{-- Notifikasi Sukses / Pesan Konfirmasi --}}
                        <div class="alert alert-success d-none mb-0" id="checkoutSuccessAlert" role="alert">
                            <i class="bi bi-check-circle me-1" aria-hidden="true"></i> Pesanan sedang dialihkan ke WhatsApp penjual...
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="purchaseForm" class="btn btn-primary" id="confirmPurchase">
                        <i class="bi bi-whatsapp me-1" aria-hidden="true"></i> Pesan via WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Ganti gambar utama saat thumbnail diklik
        const mainImage = document.getElementById('productMainImage');
        const thumbnails = document.querySelectorAll('.product-detail__thumb');

        thumbnails.forEach(function (thumbnail) {
            thumbnail.addEventListener('click', function () {
                if (mainImage && thumbnail.dataset.image) {
                    mainImage.src = thumbnail.dataset.image;
                    thumbnails.forEach(function (item) { item.classList.remove('active'); });
                    thumbnail.classList.add('active');
                }
            });
        });

        // 2. Perhitungan subtotal dan total dinamis di Modal Checkout
        const quantityInput = document.getElementById('productQuantity');
        const deliveryMethod = document.getElementById('deliveryMethod');
        const productSubtotal = document.getElementById('productSubtotal');
        const deliveryCost = document.getElementById('deliveryCost');
        const productTotal = document.getElementById('productTotal');
        const btnQtyMinus = document.getElementById('btnQtyMinus');
        const btnQtyPlus = document.getElementById('btnQtyPlus');

        const productPrice = {{ (int) $product->harga }};
        const productStock = {{ max(1, (int) $product->stok) }};

        function updateTotals() {
            if (!quantityInput) return;
            let qty = parseInt(quantityInput.value, 10);
            if (isNaN(qty) || qty < 1) qty = 1;
            if (qty > productStock) qty = productStock;
            quantityInput.value = qty;

            const delivery = deliveryMethod ? (parseInt(deliveryMethod.value, 10) || 0) : 0;
            const subtotal = qty * productPrice;
            const total = subtotal + delivery;

            if (productSubtotal) productSubtotal.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            if (deliveryCost) deliveryCost.textContent = delivery === 0 ? 'Gratis' : 'Rp ' + delivery.toLocaleString('id-ID');
            if (productTotal) productTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        if (quantityInput) {
            quantityInput.addEventListener('input', updateTotals);
            quantityInput.addEventListener('change', updateTotals);
        }

        if (deliveryMethod) {
            deliveryMethod.addEventListener('change', updateTotals);
        }

        if (btnQtyMinus) {
            btnQtyMinus.addEventListener('click', function () {
                let current = parseInt(quantityInput.value, 10) || 1;
                if (current > 1) {
                    quantityInput.value = current - 1;
                    updateTotals();
                }
            });
        }

        if (btnQtyPlus) {
            btnQtyPlus.addEventListener('click', function () {
                let current = parseInt(quantityInput.value, 10) || 1;
                if (current < productStock) {
                    quantityInput.value = current + 1;
                    updateTotals();
                }
            });
        }

        updateTotals();

        // 3. Salin tautan produk (Modal Share)
        const btnCopy = document.getElementById('btnCopyProductUrl');
        const copyInput = document.getElementById('productUrl');
        const copyText = document.getElementById('copyBtnText');
        const copyIcon = document.getElementById('copyIcon');

        if (btnCopy && copyInput) {
            btnCopy.addEventListener('click', async function () {
                try {
                    await navigator.clipboard.writeText(copyInput.value);
                } catch (e) {
                    copyInput.select();
                    document.execCommand('copy');
                }

                if (copyText) copyText.textContent = 'Tersalin!';
                if (copyIcon) {
                    copyIcon.className = 'bi bi-check-lg text-success';
                }
                btnCopy.classList.remove('btn-outline-primary');
                btnCopy.classList.add('btn-success');

                setTimeout(function () {
                    if (copyText) copyText.textContent = 'Salin';
                    if (copyIcon) copyIcon.className = 'bi bi-clipboard';
                    btnCopy.classList.remove('btn-success');
                    btnCopy.classList.add('btn-outline-primary');
                }, 2000);
            });
        }

        // Web Share API jika didukung perangkat (Mobile)
        const btnNativeShare = document.getElementById('btnNativeShare');
        if (btnNativeShare && navigator.share) {
            btnNativeShare.classList.remove('d-none');
            btnNativeShare.addEventListener('click', async function () {
                try {
                    await navigator.share({
                        title: '{{ addslashes($product->nama) }}',
                        text: 'Cek {{ addslashes($product->nama) }} di Airunovin Marketplace!',
                        url: window.location.href,
                    });
                } catch (err) {
                    // Abaikan jika user membatalkan share
                }
            });
        }

        // 4. Penanganan Form Checkout / Pemesanan via WhatsApp
        const purchaseForm = document.getElementById('purchaseForm');
        const sellerPhoneTarget = '{{ $cleanPhone ?? "6281234567890" }}';
        const sellerDisplayName = '{{ addslashes($displayName) }}';
        const productName = '{{ addslashes($product->nama) }}';
        const productUrl = window.location.href;

        if (purchaseForm) {
            purchaseForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const buyerNameInput = document.getElementById('buyerName');
                const buyerPhoneInput = document.getElementById('buyerPhone');
                const selectedPayment = document.querySelector('input[name="paymentMethod"]:checked');

                let isValid = true;

                if (!buyerNameInput.value.trim()) {
                    buyerNameInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    buyerNameInput.classList.remove('is-invalid');
                }

                if (!buyerPhoneInput.value.trim()) {
                    buyerPhoneInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    buyerPhoneInput.classList.remove('is-invalid');
                }

                if (!isValid) return;

                const qty = parseInt(quantityInput.value, 10) || 1;
                const deliverySelected = deliveryMethod ? deliveryMethod.options[deliveryMethod.selectedIndex].getAttribute('data-label') || 'Ambil Sendiri' : 'Ambil Sendiri';
                const deliveryFee = deliveryMethod ? (parseInt(deliveryMethod.value, 10) || 0) : 0;
                const paymentMethodVal = selectedPayment ? selectedPayment.value : 'Transfer Bank';
                const totalCost = (qty * productPrice) + deliveryFee;

                // Susun pesan WhatsApp yang rapi
                const message =
                    `Halo ${sellerDisplayName}, saya ingin memesan unit/produk dari Airunovin Marketplace:\n\n` +
                    `*Data Pesanan:*\n` +
                    `- Produk: ${productName}\n` +
                    `- Jumlah: ${qty} unit\n` +
                    `- Harga Satuan: Rp ${productPrice.toLocaleString('id-ID')}\n` +
                    `- Pengiriman: ${deliverySelected} (${deliveryFee === 0 ? 'Gratis' : 'Rp ' + deliveryFee.toLocaleString('id-ID')})\n` +
                    `- Pembayaran: ${paymentMethodVal}\n` +
                    `- *Total Tagihan: Rp ${totalCost.toLocaleString('id-ID')}*\n\n` +
                    `*Data Pembeli:*\n` +
                    `- Nama: ${buyerNameInput.value.trim()}\n` +
                    `- No. WhatsApp: ${buyerPhoneInput.value.trim()}\n\n` +
                    `Tautan Produk: ${productUrl}\n\n` +
                    `Mohon konfirmasi ketersediaan dan nomor rekening/instruksi pembayarannya. Terima kasih!`;

                const waUrl = `https://wa.me/${sellerPhoneTarget}?text=${encodeURIComponent(message)}`;

                const successAlert = document.getElementById('checkoutSuccessAlert');
                if (successAlert) {
                    successAlert.classList.remove('d-none');
                }

                setTimeout(function () {
                    window.open(waUrl, '_blank');
                }, 400);
            });
        }
    });
</script>
@endpush