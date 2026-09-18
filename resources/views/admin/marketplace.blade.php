@extends('layout.admin')

@section('title', 'Marketplace Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">{{ __('Marketplace Admin') }}</h1>
        <p class="subtitle mb-0">{{ __('Kelola katalog produk, stok unit airsoft, sparepart, dan perlengkapan taktis.') }}</p>
    </div>
    <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTambahProduct">
        <i class="bi bi-plus-lg me-1"></i> {{ __('+ Produk Baru') }}
    </button>
</div>

{{-- Notifikasi Flash Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            <div><strong>Berhasil!</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><strong>Gagal!</strong> {{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><strong>Terjadi kesalahan input:</strong></div>
        </div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Stat Cards --}}
@php
    $totalStock = $products->sum('stok');
    $uniqueCategories = $products->pluck('jenis')->filter()->unique()->count();
    $uniqueSellers = $products->pluck('id_user')->unique()->count();
@endphp
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue">📦</div>
                <span class="pill success">Katalog</span>
            </div>
            <div class="stat-label">Total Produk</div>
            <p class="stat-value">{{ count($products) }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green">✦</div>
                <span class="pill success">Unit</span>
            </div>
            <div class="stat-label">Total Stok Tersedia</div>
            <p class="stat-value">{{ $totalStock }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange">🏷</div>
                <span class="pill warning">Jenis</span>
            </div>
            <div class="stat-label">Kategori Aktif</div>
            <p class="stat-value">{{ $uniqueCategories }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple">🏪</div>
                <span class="pill success">Mitra</span>
            </div>
            <div class="stat-label">Toko / Penjual</div>
            <p class="stat-value">{{ $uniqueSellers }}</p>
        </div>
    </div>
</div>

{{-- Panel Tabel Produk Marketplace --}}
<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h2 class="panel-title mb-0">Daftar Produk Marketplace</h2>
        <span class="text-muted small">Total: {{ count($products) }} item</span>
    </div>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">Foto</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Merk & Kondisi</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Penjual / Toko</th>
                        <th>Status</th>
                        <th class="text-end" style="min-width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $prod)
                    @php
                        $imgUrl = $prod->gambar
                            ? (str_starts_with($prod->gambar, 'http') ? $prod->gambar : asset('storage/' . $prod->gambar))
                            : asset('img/balnkLogo.png');
                        $storeName = $prod->marketplace->nama ?? $prod->user->nama ?? 'Penjual';
                        $payments = is_array($prod->payment_methods)
                            ? $prod->payment_methods
                            : (json_decode($prod->payment_methods ?? '[]', true) ?: []);
                        $paymentsJson = json_encode($payments);
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ $imgUrl }}" alt="{{ $prod->nama }}" class="rounded object-fit-contain border p-1 bg-light" style="width: 48px; height: 48px;" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                        </td>
                        <td>
                            <span class="member-name fw-bold text-dark text-truncate d-block" style="max-width: 200px;" title="{{ $prod->nama }}">
                                {{ $prod->nama }}
                            </span>
                            <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $prod->lokasi }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $prod->jenis }}</span>
                        </td>
                        <td>
                            <div>{{ $prod->merk }}</div>
                            <small class="text-muted">{{ $prod->kondisi }}</small>
                        </td>
                        <td>
                            <span class="fw-bold text-success">Rp {{ number_format($prod->harga, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            @if($prod->stok > 3)
                                <span class="badge text-bg-success">{{ $prod->stok }} unit</span>
                            @elseif($prod->stok > 0)
                                <span class="badge text-bg-warning">{{ $prod->stok }} unit</span>
                            @else
                                <span class="badge text-bg-danger">Habis</span>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">{{ $storeName }}</div>
                            <small class="text-muted">{{ $prod->user->nama ?? '-' }}</small>
                        </td>
                        <td>
                            @if($prod->stok > 0)
                                <span class="pill success">Tersedia</span>
                            @else
                                <span class="pill danger">Kosong</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-detail-product me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalDetailProduct"
                                    data-nama="{{ $prod->nama }}"
                                    data-harga="Rp {{ number_format($prod->harga, 0, ',', '.') }}"
                                    data-merk="{{ $prod->merk }}"
                                    data-jenis="{{ $prod->jenis }}"
                                    data-kondisi="{{ $prod->kondisi }}"
                                    data-stok="{{ $prod->stok }}"
                                    data-lokasi="{{ $prod->lokasi }}"
                                    data-toko="{{ $storeName }}"
                                    data-penjual="{{ $prod->user->nama ?? '-' }}"
                                    data-deskripsi="{{ $prod->deskripsi ?? 'Tidak ada deskripsi.' }}"
                                    data-gambar="{{ $imgUrl }}"
                                    data-payments='{{ $paymentsJson }}'
                                    title="Detail Produk">
                                <i class="bi bi-eye"></i> Detail
                            </button>

                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-product me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEditProduct"
                                    data-id="{{ $prod->id }}"
                                    data-nama="{{ $prod->nama }}"
                                    data-harga="{{ $prod->harga }}"
                                    data-merk="{{ $prod->merk }}"
                                    data-jenis="{{ $prod->jenis }}"
                                    data-kondisi="{{ $prod->kondisi }}"
                                    data-stok="{{ $prod->stok }}"
                                    data-lokasi="{{ $prod->lokasi }}"
                                    data-id-marketplace="{{ $prod->id_marketplace }}"
                                    data-deskripsi="{{ $prod->deskripsi }}"
                                    data-payments='{{ $paymentsJson }}'
                                    title="Edit Produk">
                                <i class="bi bi-pencil"></i> Edit
                            </button>

                            <form action="{{ route('admin.marketplace.destroy', $prod->id) }}" method="POST" class="d-inline form-delete-product">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Produk">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada produk di marketplace. Klik "+ Produk Baru" untuk menambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODALS MARKETPLACE / PRODUCT                              --}}
{{-- ======================================================== --}}

{{-- Modal Tambah Produk --}}
<div class="modal fade" id="modalTambahProduct" tabindex="-1" aria-labelledby="modalTambahProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.marketplace.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahProductLabel">
                        <i class="bi bi-box-seam text-primary me-1"></i> Tambah Produk Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: M4 Carbine AEG M-LOK Tactical Edition">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" class="form-control" required min="0" placeholder="Contoh: 2850000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Merek / Produsen <span class="text-danger">*</span></label>
                        <input type="text" name="merk" class="form-control" required placeholder="Contoh: Specna Arms / Tokyo Marui / Condor">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori / Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select" required>
                            <option value="">Pilih Kategori...</option>
                            <option value="Unit">Unit Airsoft</option>
                            <option value="Sparepart">Sparepart / Upgrade</option>
                            <option value="Perlengkapan">Perlengkapan / Tactical Gear</option>
                            <option value="Aksesoris">Aksesoris / Optic & Scope</option>
                            <option value="Amunisi">Amunisi BB & Gas</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                        <select name="kondisi" class="form-select" required>
                            <option value="Baru">Baru</option>
                            <option value="Sangat baik">Sangat baik (Like New)</option>
                            <option value="Bekas">Bekas (Second Layak Pakai)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stok Unit <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control" required min="0" value="1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Lokasi / Kota <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi" class="form-control" required placeholder="Contoh: Surabaya">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Toko / Marketplace Mitra</label>
                        <select name="id_marketplace" class="form-select">
                            <option value="">Pilih Toko Penjual...</option>
                            @foreach($marketplaces as $m)
                                <option value="{{ $m->id }}">{{ $m->nama }} ({{ $m->user->nama ?? 'Admin' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Metode Pembayaran yang Diterima</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach(['Transfer Bank', 'QRIS', 'COD', 'DANA', 'GoPay'] as $pm)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="payment_methods[]" value="{{ $pm }}" id="add_pm_{{ \Illuminate\Support\Str::slug($pm) }}" {{ in_array($pm, ['Transfer Bank', 'QRIS']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="add_pm_{{ \Illuminate\Support\Str::slug($pm) }}">{{ $pm }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Rincian spesifikasi unit, material, FPS, kelengkapan dalam boks..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Foto Produk</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, WEBP. Maksimal 2MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Produk --}}
<div class="modal fade" id="modalEditProduct" tabindex="-1" aria-labelledby="modalEditProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditProduct" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalEditProductLabel">
                        <i class="bi bi-pencil-square text-primary me-1"></i> Edit Data Produk
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" id="editProductNama" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="editProductHarga" name="harga" class="form-control" required min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Merek <span class="text-danger">*</span></label>
                        <input type="text" id="editProductMerk" name="merk" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori / Jenis <span class="text-danger">*</span></label>
                        <select id="editProductJenis" name="jenis" class="form-select" required>
                            <option value="Unit">Unit Airsoft</option>
                            <option value="Sparepart">Sparepart / Upgrade</option>
                            <option value="Perlengkapan">Perlengkapan / Tactical Gear</option>
                            <option value="Aksesoris">Aksesoris / Optic & Scope</option>
                            <option value="Amunisi">Amunisi BB & Gas</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                        <select id="editProductKondisi" name="kondisi" class="form-select" required>
                            <option value="Baru">Baru</option>
                            <option value="Sangat baik">Sangat baik</option>
                            <option value="Bekas">Bekas</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stok Unit <span class="text-danger">*</span></label>
                        <input type="number" id="editProductStok" name="stok" class="form-control" required min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" id="editProductLokasi" name="lokasi" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Toko / Marketplace Mitra</label>
                        <select id="editProductIdMarketplace" name="id_marketplace" class="form-select">
                            <option value="">Pilih Toko Penjual...</option>
                            @foreach($marketplaces as $m)
                                <option value="{{ $m->id }}">{{ $m->nama }} ({{ $m->user->nama ?? 'Admin' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Metode Pembayaran</label>
                        <div class="d-flex flex-wrap gap-3" id="editPaymentMethodsContainer">
                            @foreach(['Transfer Bank', 'QRIS', 'COD', 'DANA', 'GoPay'] as $pm)
                                <div class="form-check">
                                    <input class="form-check-input edit-pm-checkbox" type="checkbox" name="payment_methods[]" value="{{ $pm }}" id="edit_pm_{{ \Illuminate\Support\Str::slug($pm) }}">
                                    <label class="form-check-label" for="edit_pm_{{ \Illuminate\Support\Str::slug($pm) }}">{{ $pm }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Produk</label>
                        <textarea id="editProductDeskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Ganti Foto Produk (opsional)</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar produk saat ini.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Detail Produk --}}
<div class="modal fade" id="modalDetailProduct" tabindex="-1" aria-labelledby="modalDetailProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalDetailProductLabel">
                    <i class="bi bi-info-circle text-primary me-1"></i> Rincian Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <img id="detailProductGambar" src="" alt="Foto Produk" class="img-fluid rounded border p-2 bg-light" style="max-height: 180px; object-fit: contain;">
                </div>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 130px;">Nama Produk</td>
                            <td class="fw-bold" id="detailProductNama">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Harga</td>
                            <td class="fw-bold text-success fs-6" id="detailProductHarga">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kategori / Jenis</td>
                            <td id="detailProductJenis">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Merek & Kondisi</td>
                            <td id="detailProductMerk">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Stok Unit</td>
                            <td id="detailProductStok">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Lokasi</td>
                            <td id="detailProductLokasi">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Toko / Penjual</td>
                            <td id="detailProductToko">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pembayaran</td>
                            <td id="detailProductPayments">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted align-top">Deskripsi</td>
                            <td id="detailProductDeskripsi" style="white-space: pre-line;">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Tangani pengisian data Modal Edit Produk
        const formEditProduct = document.getElementById('formEditProduct');
        document.querySelectorAll('.btn-edit-product').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                formEditProduct.action = "{{ url('admin/marketplace') }}/" + id;

                document.getElementById('editProductNama').value = this.dataset.nama || '';
                document.getElementById('editProductHarga').value = this.dataset.harga || '';
                document.getElementById('editProductMerk').value = this.dataset.merk || '';
                document.getElementById('editProductJenis').value = this.dataset.jenis || '';
                document.getElementById('editProductKondisi').value = this.dataset.kondisi || '';
                document.getElementById('editProductStok').value = this.dataset.stok || '';
                document.getElementById('editProductLokasi').value = this.dataset.lokasi || '';
                document.getElementById('editProductIdMarketplace').value = this.dataset.idMarketplace || '';
                document.getElementById('editProductDeskripsi').value = this.dataset.deskripsi || '';

                // Reset & centang checkbox payment methods
                const payments = JSON.parse(this.dataset.payments || '[]');
                document.querySelectorAll('.edit-pm-checkbox').forEach(cb => {
                    cb.checked = payments.includes(cb.value);
                });
            });
        });

        // 2. Tangani pengisian data Modal Detail Produk
        document.querySelectorAll('.btn-detail-product').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('detailProductNama').textContent = this.dataset.nama || '-';
                document.getElementById('detailProductHarga').textContent = this.dataset.harga || '-';
                document.getElementById('detailProductJenis').textContent = this.dataset.jenis || '-';
                document.getElementById('detailProductMerk').textContent = (this.dataset.merk || '-') + ' (' + (this.dataset.kondisi || '-') + ')';
                document.getElementById('detailProductStok').textContent = (this.dataset.stok || '0') + ' unit';
                document.getElementById('detailProductLokasi').textContent = this.dataset.lokasi || '-';
                document.getElementById('detailProductToko').textContent = (this.dataset.toko || '-') + ' (Akun: ' + (this.dataset.penjual || '-') + ')';
                document.getElementById('detailProductDeskripsi').textContent = this.dataset.deskripsi || '-';

                const payments = JSON.parse(this.dataset.payments || '[]');
                const paymentsTd = document.getElementById('detailProductPayments');
                if (payments && payments.length > 0) {
                    paymentsTd.innerHTML = payments.map(p => `<span class="badge bg-light text-dark border me-1">${p}</span>`).join('');
                } else {
                    paymentsTd.textContent = '-';
                }

                const gambar = this.dataset.gambar;
                const imgElem = document.getElementById('detailProductGambar');
                if (gambar) {
                    imgElem.src = gambar;
                }
            });
        });

        // 3. Konfirmasi sebelum Hapus Produk
        document.querySelectorAll('.form-delete-product').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari marketplace?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
