@extends('layout/app')

@php
    $produk = [
        'nama' => 'M4 Custom AEG',
        'harga' => 2850000,
        'merk' => 'M4',
        'jenis' => 'Unit',
        'kondisi' => 'Sangat baik',
        'stok' => 1,
        'lokasi' => 'Bandung',
        'penjual' => 'M4 Airsoft Store',
        'gambar' => asset('img/imgStatik/GambarProduk/G.S.P2.jpg'),
    ];

    $gambarProduk = [$produk['gambar'], $produk['gambar'], $produk['gambar']];
@endphp

@section('title', $produk['nama'] . ' - Marketplace')

@push('styles')
<style>
    .product-detail__media,
    .product-detail__summary,
    .product-detail__panel {
        border: 1px solid #dfe5e8;
        background: #fff;
        box-shadow: 0 8px 24px rgba(31, 49, 58, 0.06);
    }

    .product-detail__main-image {
        height: clamp(280px, 42vw, 500px);
        background: #f3f6f5;
    }

    .product-detail__main-image img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .product-detail__thumb {
        width: 68px;
        height: 68px;
        border: 2px solid transparent;
        background: #f3f6f5;
        padding: 3px;
    }

    .product-detail__thumb.active,
    .product-detail__thumb:hover {
        border-color: #0d6efd;
    }

    .product-detail__thumb img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .product-detail__eyebrow {
        letter-spacing: .08em;
    }

    .product-detail__price {
        color: #087f5b;
        font-size: clamp(1.8rem, 4vw, 2.6rem);
    }

    .product-detail__seller-avatar {
        width: 44px;
        height: 44px;
        background: #d9f2e8;
        color: #087f5b;
    }

    @media (max-width: 575.98px) {
        .product-detail__thumb { width: 56px; height: 56px; }
    }
</style>
@endpush

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="Breadcrumb" class="mb-4 small">
            <a href="{{ route('marketplace') }}" class="text-decoration-none">Marketplace</a>
            <span class="text-muted mx-2">/</span>
            <span class="text-muted">{{ $produk['jenis'] }}</span>
        </nav>

        <div class="row g-4 g-xl-5 align-items-start">
            <div class="col-12 col-lg-6">
                <div class="product-detail__media rounded-3 overflow-hidden">
                    <div class="product-detail__main-image d-flex align-items-center justify-content-center p-3">
                        <img id="productMainImage" src="{{ $produk['gambar'] }}" alt="{{ $produk['nama'] }}">
                    </div>
                    <div class="d-flex gap-2 p-3 border-top" aria-label="Pilih foto produk">
                        @foreach ($gambarProduk as $gambar)
                            <button type="button" class="product-detail__thumb rounded-2 " data-image="{{ $gambar }}" aria-label="Foto">
                                <img src="{{ $gambar }}" alt="">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="product-detail__summary rounded-3 p-4 p-lg-5">
                    <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
                        <div>
                            <p class="product-detail__eyebrow text-uppercase text-primary small fw-bold mb-2">{{ $produk['jenis'] }}</p>
                            <h1 class="h2 fw-bold mb-0">{{ $produk['nama'] }}</h1>
                        </div>
                        <span class="badge text-bg-success rounded-pill px-3 py-2">Tersedia</span>
                    </div>

                    <p class="product-detail__price fw-bold mb-4">Rp {{ number_format($produk['harga'], 0, ',', '.') }}</p>

                    <div class="row g-3 border-top border-bottom py-3 mb-4 small">
                        <div class="col-6"><span class="text-muted d-block">Merek</span><strong>{{ $produk['merk'] }}</strong></div>
                        <div class="col-6"><span class="text-muted d-block">Kondisi</span><strong>{{ $produk['kondisi'] }}</strong></div>
                        <div class="col-6"><span class="text-muted d-block">Stok</span><strong>{{ $produk['stok'] }} unit</strong></div>
                        <div class="col-6"><span class="text-muted d-block">Lokasi</span><strong>{{ $produk['lokasi'] }}</strong></div>
                    </div>

                    <h2 class="h5 fw-bold">Tentang produk</h2>
                    <p class="text-muted mb-4">M4 Custom AEG dengan kondisi sangat baik, cocok untuk latihan, skirmish, maupun koleksi. Pesan langsung melalui checkout untuk melanjutkan pembelian.</p>

                    <div class="d-grid gap-2 d-sm-flex">
                        <button type="button" class="btn btn-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#buyProductModal">Beli sekarang</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#shareProductModal">Bagikan</button>
                    </div>
                </div>

                <div class="product-detail__panel rounded-3 p-3 mt-3 d-flex align-items-center gap-3">
                    <div class="product-detail__seller-avatar rounded-circle d-flex align-items-center justify-content-center fw-bold">RA</div>
                    <div class="flex-grow-1">
                        <span class="text-muted small d-block">Dijual oleh</span>
                        <strong>{{ $produk['penjual'] }}</strong>
                    </div>
                    <span class="badge text-bg-light border">Penjual aktif</span>
                </div>
            </div>
        </div>

        <section class="mt-5" aria-labelledby="produk-serupa">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div><p class="text-uppercase text-muted small fw-semibold mb-1">Lihat lainnya</p><h2 id="produk-serupa" class="h4 fw-bold mb-0">Produk serupa</h2></div>
                <a href="{{ route('marketplace') }}" class="small text-decoration-none">Lihat semua</a>
            </div>
            @php
                $products = [
                    ['image' => 'G.S.P1.jpg', 'name' => 'Tactical Vest', 'price' => 'Rp 450.000', 'brand' => 'Condor', 'category' => 'Perlengkapan', 'region' => 'Jakarta'],
                    ['image' => 'G.S.P2.jpg', 'name' => 'AEG M4', 'price' => 'Rp 2.750.000', 'brand' => 'Specna Arms', 'category' => 'Unit', 'region' => 'Bandung'],
                    ['image' => 'G.S.P3.jpg', 'name' => 'AEG M4 NEW RAKIT', 'price' => 'Rp 3.250.000', 'brand' => 'Custom Build', 'category' => 'Unit', 'region' => 'Surabaya'],
                    ['image' => 'G.S.P4.jpg', 'name' => 'Dcobra SIG556 custom', 'price' => 'Rp 900.000', 'brand' => 'Dcobra', 'category' => 'Unit', 'region' => 'Yogyakarta'],
                    ['image' => 'G.S.P5.jpg', 'name' => 'Kokang Metal Hybrid-X Dcobra MAK47L', 'price' => 'Rp 1.250.000', 'brand' => 'RCW', 'category' => 'Part', 'region' => 'Malang'],
                    ['image' => 'G.S.P6.jpg', 'name' => 'Maple Leaf Hop Up Chamber Assembly for Tokyo Marui / KJ Works / WE / 1911 GBB', 'price' => 'Rp 325.000', 'brand' => 'Maple Leaf', 'category' => 'Part', 'region' => 'Depok'],
                ];
            @endphp
            <div class="row justify-content-center g-2">
                @foreach ($products as $product)
                    <div class="col-auto mb-5">
                        <a href="{{ route('isiMarketplace') }}" class="text-decoration-none text-dark">
                            <div class="card h-100" style="width: 13rem;">
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 280px; overflow: hidden; border-radius: 8px 8px 0 0;">
                                    <img src="{{ asset('img/imgStatik/GambarProduk/' . $product['image']) }}" alt="{{ $product['name'] }}" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;" loading="lazy">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold text-truncate" title="{{ $product['name'] }}">{{ $product['name'] }}</h5>
                                    <ul class="list-unstyled small mb-2">
                                        <li class="text-success fw-semibold mb-1">{{ $product['price'] }}</li>
                                        <li><i class="bi bi-tag" aria-hidden="true"></i> <span class="text-muted">{{ $product['brand'] }}</span></li>
                                        <li><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> <span class="text-muted">{{ $product['category'] }}</span></li>
                                    </ul>
                                    <div class="mt-auto pt-2 border-top text-muted"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $product['region'] }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <div class="modal fade" id="shareProductModal" tabindex="-1" aria-labelledby="shareProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h2 class="modal-title h5" id="shareProductLabel">Bagikan produk</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div><div class="modal-body"><label for="productUrl" class="form-label small text-muted">Tautan produk</label><input id="productUrl" class="form-control" value="{{ url()->current() }}" readonly></div><div class="modal-footer"><button type="button" class="btn btn-primary" data-bs-dismiss="modal">Selesai</button></div></div></div>
    </div>

    <div class="modal fade" id="buyProductModal" tabindex="-1" aria-labelledby="buyProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="buyProductLabel">Checkout produk</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex gap-3 align-items-center border-bottom pb-3 mb-3">
                        <img src="{{ $produk['gambar'] }}" alt="{{ $produk['nama'] }}" width="64" height="64" class="rounded-2 bg-light" style="object-fit: contain;">
                        <div><strong class="d-block">{{ $produk['nama'] }}</strong><span class="text-success fw-semibold">Rp {{ number_format($produk['harga'], 0, ',', '.') }}</span></div>
                    </div>
                    <div class="mb-3">
                        <label for="productQuantity" class="form-label fw-semibold">Jumlah</label>
                        <input type="number" class="form-control" id="productQuantity" value="1" min="1" max="{{ $produk['stok'] }}">
                    </div>
                    <div class="mb-3">
                        <label for="deliveryMethod" class="form-label fw-semibold">Metode pengiriman</label>
                        <select class="form-select" id="deliveryMethod">
                            <option value="0">Ambil di lokasi penjual - Gratis</option>
                            <option value="25000">Kurir lokal - Rp 25.000</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-3 fw-bold"><span>Total pembayaran</span><span class="text-success" id="productTotal">Rp 525.000</span></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-primary w-100" id="confirmPurchase">Konfirmasi pembelian</button></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.product-detail__thumb').forEach(function (thumbnail) {
        thumbnail.addEventListener('click', function () {
            document.getElementById('productMainImage').src = thumbnail.dataset.image;
            document.querySelectorAll('.product-detail__thumb').forEach(function (item) { item.classList.remove('active'); });
            thumbnail.classList.add('active');
        });
    });

    const quantityInput = document.getElementById('productQuantity');
    const deliveryMethod = document.getElementById('deliveryMethod');
    const productTotal = document.getElementById('productTotal');
    const productPrice = {{ $produk['harga'] }};

    function updateProductTotal() {
        const quantity = Math.max(1, Number(quantityInput.value) || 1);
        const delivery = Number(deliveryMethod.value) || 0;
        productTotal.textContent = 'Rp ' + (quantity * productPrice + delivery).toLocaleString('id-ID');
    }

    quantityInput.addEventListener('input', updateProductTotal);
    deliveryMethod.addEventListener('change', updateProductTotal);
    document.getElementById('confirmPurchase').addEventListener('click', function () {
        this.textContent = 'Pesanan siap diproses';
        this.classList.replace('btn-primary', 'btn-success');
        this.disabled = true;
    });
</script>
@endpush