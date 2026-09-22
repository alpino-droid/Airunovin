@extends('layout.admin')

@section('title', 'Marketplace (Toko) Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">{{ __('Marketplace (Toko) Admin') }}</h1>
        <p class="subtitle mb-0">{{ __('Kelola daftar toko marketplace mitra, verifikasi pendaftaran toko, dan kelola akun penjual.') }}</p>
    </div>
    <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTambahMarketplace">
        <i class="bi bi-plus-lg me-1"></i> {{ __('+ Toko Baru') }}
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
    $totalStores = count($marketplaces);
    $pendingStores = $marketplaces->where('status', 'panding')->count();
    $acceptedStores = $marketplaces->filter(fn($m) => in_array($m->status, ['diterima', 'terimakasih']))->count();
    $rejectedStores = $marketplaces->where('status', 'tolak')->count();
@endphp
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue"><i class="bi bi-shop fs-4"></i></div>
                <span class="pill success">Total</span>
            </div>
            <div class="stat-label">Total Toko</div>
            <p class="stat-value">{{ $totalStores }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green"><i class="bi bi-check-circle fs-4"></i></div>
                <span class="pill success">Aktif</span>
            </div>
            <div class="stat-label">Toko Disetujui</div>
            <p class="stat-value">{{ $acceptedStores }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange"><i class="bi bi-hourglass-split fs-4"></i></div>
                <span class="pill warning">Review</span>
            </div>
            <div class="stat-label">Menunggu (Pending)</div>
            <p class="stat-value">{{ $pendingStores }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple"><i class="bi bi-x-circle fs-4"></i></div>
                <span class="pill danger">Ditolak</span>
            </div>
            <div class="stat-label">Toko Ditolak</div>
            <p class="stat-value">{{ $rejectedStores }}</p>
        </div>
    </div>
</div>

{{-- Panel Tabel Toko Marketplace --}}
<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h2 class="panel-title mb-0">Daftar Toko Marketplace</h2>
        <span class="text-muted small">Total: {{ $totalStores }} toko</span>
    </div>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 65px;">Logo</th>
                        <th>Nama Toko & Deskripsi</th>
                        <th>Pemilik Akun</th>
                        <th>Kontak / No HP</th>
                        <th>Total Produk</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th class="text-end" style="min-width: 250px;">Aksi & Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marketplaces as $m)
                    @php
                        $logoUrl = $m->logo
                            ? (str_starts_with($m->logo, 'http') ? $m->logo : asset('storage/' . $m->logo))
                            : asset('img/balnkLogo.png');
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ $logoUrl }}" alt="{{ $m->nama }}" class="rounded-circle object-fit-cover border p-1 bg-light" style="width: 46px; height: 46px;" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                        </td>
                        <td>
                            <span class="member-name fw-bold text-dark d-block">
                                {{ $m->nama }}
                            </span>
                            <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;" title="{{ $m->deskripsi }}">
                                {{ $m->deskripsi ?: 'Tidak ada deskripsi.' }}
                            </small>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $m->user->nama ?? '-' }}</div>
                            <small class="text-muted">{{ $m->user->email ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="small text-muted">{{ $m->user->phone ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge text-bg-secondary">{{ $m->products_count ?? 0 }} produk</span>
                        </td>
                        <td>
                            @if($m->status === 'diterima' || $m->status === 'terimakasih')
                                <span class="pill success">Diterima</span>
                            @elseif($m->status === 'tolak')
                                <span class="pill danger">Ditolak</span>
                            @else
                                <span class="pill warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $m->created_at ? $m->created_at->translatedFormat('d M Y') : '-' }}</small>
                        </td>
                        <td class="text-end">
                            {{-- Moderasi Status Cepat --}}
                            @if(!in_array($m->status, ['diterima', 'terimakasih']))
                                <form action="{{ route('admin.marketplace.status', $m->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="submit" class="btn btn-sm btn-success me-1" title="Terima / Setujui Toko">
                                        <i class="bi bi-check-lg"></i> Terima
                                    </button>
                                </form>
                            @endif

                            @if($m->status !== 'tolak')
                                <form action="{{ route('admin.marketplace.status', $m->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="tolak">
                                    <button type="submit" class="btn btn-sm btn-outline-danger me-1" title="Tolak Toko" onclick="return confirm('Apakah Anda yakin ingin menolak toko ini?')">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>
                                </form>
                            @endif

                            <button type="button" class="btn btn-sm btn-outline-secondary btn-detail-marketplace me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalDetailMarketplace"
                                    data-nama="{{ $m->nama }}"
                                    data-pemilik="{{ $m->user->nama ?? '-' }}"
                                    data-email="{{ $m->user->email ?? '-' }}"
                                    data-phone="{{ $m->user->phone ?? '-' }}"
                                    data-status="{{ $m->status }}"
                                    data-produk-count="{{ $m->products_count ?? 0 }}"
                                    data-deskripsi="{{ $m->deskripsi ?? 'Tidak ada deskripsi.' }}"
                                    data-logo="{{ $logoUrl }}"
                                    data-terdaftar="{{ $m->created_at ? $m->created_at->translatedFormat('d F Y') : '-' }}"
                                    title="Detail Toko">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-marketplace me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEditMarketplace"
                                    data-id="{{ $m->id }}"
                                    data-nama="{{ $m->nama }}"
                                    data-id-user="{{ $m->id_user }}"
                                    data-status="{{ $m->status }}"
                                    data-deskripsi="{{ $m->deskripsi }}"
                                    title="Edit Toko">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form action="{{ route('admin.marketplace.destroy', $m->id) }}" method="POST" class="d-inline form-delete-marketplace">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Toko">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-shop fs-2 d-block mb-2"></i>
                            Belum ada toko marketplace terdaftar. Klik "+ Toko Baru" untuk menambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODALS MARKETPLACE (TOKO)                                 --}}
{{-- ======================================================== --}}

{{-- Modal Tambah Toko --}}
<div class="modal fade" id="modalTambahMarketplace" tabindex="-1" aria-labelledby="modalTambahMarketplaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.marketplace.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahMarketplaceLabel">
                        <i class="bi bi-shop text-primary me-1"></i> Tambah Toko Marketplace Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Toko Marketplace <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Tactical Airsoft Station">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Moderasi</label>
                        <select name="status" class="form-select">
                            <option value="diterima">Diterima (Aktif)</option>
                            <option value="panding">Pending</option>
                            <option value="tolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Pemilik Toko (Akun Pengguna)</label>
                        <select name="id_user" class="form-select">
                            <option value="">Pilih Akun Pengguna...</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->nama }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Logo Toko</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, WEBP. Maksimal 2MB.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Toko</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Ceritakan profil toko, spesialisasi unit, garansi, atau kebijakan toko..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Toko</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Toko --}}
<div class="modal fade" id="modalEditMarketplace" tabindex="-1" aria-labelledby="modalEditMarketplaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditMarketplace" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalEditMarketplaceLabel">
                        <i class="bi bi-pencil-square text-primary me-1"></i> Edit Data Toko Marketplace
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Toko Marketplace <span class="text-danger">*</span></label>
                        <input type="text" id="editMarketplaceNama" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Moderasi</label>
                        <select id="editMarketplaceStatus" name="status" class="form-select">
                            <option value="diterima">Diterima (Aktif)</option>
                            <option value="panding">Pending</option>
                            <option value="tolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Pemilik Toko</label>
                        <select id="editMarketplaceIdUser" name="id_user" class="form-select">
                            <option value="">Pilih Akun Pengguna...</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->nama }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ganti Logo Toko (opsional)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah logo saat ini.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Toko</label>
                        <textarea id="editMarketplaceDeskripsi" name="deskripsi" class="form-control" rows="4"></textarea>
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

{{-- Modal Detail Toko --}}
<div class="modal fade" id="modalDetailMarketplace" tabindex="-1" aria-labelledby="modalDetailMarketplaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalDetailMarketplaceLabel">
                    <i class="bi bi-shop-window text-primary me-1"></i> Rincian Toko Marketplace
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <img id="detailMarketplaceLogo" src="" alt="Logo Toko" class="img-fluid rounded-circle border p-2 bg-light" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 130px;">Nama Toko</td>
                            <td class="fw-bold" id="detailMarketplaceNama">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pemilik</td>
                            <td class="fw-semibold" id="detailMarketplacePemilik">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td id="detailMarketplaceEmail">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. WhatsApp</td>
                            <td id="detailMarketplacePhone">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Produk</td>
                            <td id="detailMarketplaceProduk">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td id="detailMarketplaceStatus">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terdaftar Pada</td>
                            <td id="detailMarketplaceTerdaftar">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted align-top">Deskripsi</td>
                            <td id="detailMarketplaceDeskripsi" style="white-space: pre-line;">-</td>
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
        // 1. Tangani pengisian data Modal Edit Toko
        const formEditMarketplace = document.getElementById('formEditMarketplace');
        document.querySelectorAll('.btn-edit-marketplace').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                formEditMarketplace.action = "{{ url('admin/marketplace') }}/" + id;

                document.getElementById('editMarketplaceNama').value = this.dataset.nama || '';
                document.getElementById('editMarketplaceStatus').value = this.dataset.status || 'panding';
                document.getElementById('editMarketplaceIdUser').value = this.dataset.idUser || '';
                document.getElementById('editMarketplaceDeskripsi').value = this.dataset.deskripsi || '';
            });
        });

        // 2. Tangani pengisian data Modal Detail Toko
        document.querySelectorAll('.btn-detail-marketplace').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('detailMarketplaceNama').textContent = this.dataset.nama || '-';
                document.getElementById('detailMarketplacePemilik').textContent = this.dataset.pemilik || '-';
                document.getElementById('detailMarketplaceEmail').textContent = this.dataset.email || '-';
                document.getElementById('detailMarketplacePhone').textContent = this.dataset.phone || '-';
                document.getElementById('detailMarketplaceProduk').textContent = (this.dataset.produkCount || '0') + ' produk';
                document.getElementById('detailMarketplaceTerdaftar').textContent = this.dataset.terdaftar || '-';
                document.getElementById('detailMarketplaceDeskripsi').textContent = this.dataset.deskripsi || '-';

                const status = this.dataset.status || 'panding';
                let statusBadge = '<span class="pill warning">Pending</span>';
                if (status === 'diterima' || status === 'terimakasih') {
                    statusBadge = '<span class="pill success">Diterima</span>';
                } else if (status === 'tolak') {
                    statusBadge = '<span class="pill danger">Ditolak</span>';
                }
                document.getElementById('detailMarketplaceStatus').innerHTML = statusBadge;

                const logo = this.dataset.logo;
                const imgElem = document.getElementById('detailMarketplaceLogo');
                if (logo) {
                    imgElem.src = logo;
                }
            });
        });

        // 3. Konfirmasi sebelum Hapus Toko
        document.querySelectorAll('.form-delete-marketplace').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!confirm('Apakah Anda yakin ingin menghapus toko marketplace ini? Semua produk yang tertaut akan terpengaruh.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
