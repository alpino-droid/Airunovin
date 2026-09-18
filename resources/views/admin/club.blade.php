@extends('layout.admin')

@section('title', 'Manajemen Club')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">{{ __('Club Management') }}</h1>
        <p class="subtitle mb-0">{{ __('Kelola profil komunitas, induk organisasi, dan keanggotaan club airsoft.') }}</p>
    </div>
    <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTambahClub">
        <i class="bi bi-plus-lg me-1"></i> {{ __('Tambah Club') }}
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
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue">◫</div>
                <span class="pill success">Aktif</span>
            </div>
            <div class="stat-label">{{ __('Total Club') }}</div>
            <p class="stat-value">{{ count($clubs) }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green">✓</div>
                <span class="pill success">100%</span>
            </div>
            <div class="stat-label">Club Terdaftar</div>
            <p class="stat-value">{{ count($clubs) }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange">📍</div>
                <span class="pill warning">{{ count($cities ?? []) }} Kota</span>
            </div>
            <div class="stat-label">Wilayah / Kota</div>
            <p class="stat-value">{{ count($cities ?? []) }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple">👥</div>
                <span class="pill success">Terverifikasi</span>
            </div>
            <div class="stat-label">Total Pengguna</div>
            <p class="stat-value">{{ count($users ?? []) }}</p>
        </div>
    </div>
</div>

{{-- Panel Tabel Club --}}
<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="panel-title mb-0">Daftar Club Airsoft</h2>
        
        {{-- Filter Ringkas --}}
        <form action="{{ route('admin.club') }}" method="GET" class="d-flex align-items-center gap-2">
            <select name="province" class="form-select form-select-sm" style="max-width: 180px;" onchange="this.form.submit()">
                <option value="">Semua Provinsi</option>
                @foreach($provinsi as $p)
                    <option value="{{ $p->id }}" {{ request('province') == $p->id ? 'selected' : '' }}>{{ $p->provinsi }}</option>
                @endforeach
            </select>
            @if(request('province'))
                <a href="{{ route('admin.club') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">Logo</th>
                        <th>Nama Club</th>
                        <th>Kota & Provinsi</th>
                        <th>Induk Organisasi</th>
                        <th>Pemilik</th>
                        <th>Status</th>
                        <th class="text-end" style="min-width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clubs as $club)
                    @php
                        $provObj = $provinsi->firstWhere('id', $club->id_provinsi);
                        $provNama = $provObj ? $provObj->provinsi : '-';
                        $logoUrl = $club->logo_url;
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ $logoUrl }}" alt="Logo" class="rounded object-fit-cover" style="width: 36px; height: 36px;" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                        </td>
                        <td>
                            <span class="member-name fw-bold text-dark">{{ $club->nama }}</span>
                            @if($club->gform_link)
                                <small class="d-block text-muted text-truncate" style="max-width: 220px;">
                                    <i class="bi bi-link-45deg"></i> {{ $club->gform_link }}
                                </small>
                            @endif
                        </td>
                        <td>
                            <div>{{ $club->city }}</div>
                            <small class="text-muted">{{ $provNama }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $club->induk_organisasi }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $club->user->nama ?? 'Admin' }}</small>
                        </td>
                        <td>
                            <span class="pill success">Aktif</span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-detail-club me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalDetailClub"
                                    data-nama="{{ $club->nama }}"
                                    data-induk="{{ $club->induk_organisasi }}"
                                    data-provinsi="{{ $provNama }}"
                                    data-city="{{ $club->city }}"
                                    data-pemilik="{{ $club->user->nama ?? '-' }}"
                                    data-gform="{{ $club->gform_link ?? '-' }}"
                                    data-deskripsi="{{ $club->deskripsi }}"
                                    data-logo="{{ $logoUrl }}"
                                    title="Detail Club">
                                <i class="bi bi-eye"></i> Detail
                            </button>

                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-club me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEditClub"
                                    data-id="{{ $club->id }}"
                                    data-nama="{{ $club->nama }}"
                                    data-induk="{{ $club->induk_organisasi }}"
                                    data-id-provinsi="{{ $club->id_provinsi }}"
                                    data-city="{{ $club->city }}"
                                    data-gform="{{ $club->gform_link }}"
                                    data-deskripsi="{{ $club->deskripsi }}"
                                    title="Edit Club">
                                <i class="bi bi-pencil"></i> Edit
                            </button>

                            <form action="{{ route('admin.club.destroy', $club->id) }}" method="POST" class="d-inline form-delete-club">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Club">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Tidak ada data club yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODALS CLUB                                               --}}
{{-- ======================================================== --}}

{{-- Modal Tambah Club --}}
<div class="modal fade" id="modalTambahClub" tabindex="-1" aria-labelledby="modalTambahClubLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.club.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahClubLabel">
                        <i class="bi bi-shield-plus text-primary me-1"></i> Tambah Club Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Club <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Ranger Squad Airsoft">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Induk Organisasi <span class="text-danger">*</span></label>
                        <input type="text" name="induk_organisasi" class="form-control" required placeholder="Contoh: PORGASI / FAI / INASOC">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Provinsi <span class="text-danger">*</span></label>
                        <select name="id_provinsi" class="form-select" required>
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kota / Daerah <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" required placeholder="Contoh: Jakarta Selatan">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Link Formulir Pendaftaran (GForm / Web)</label>
                        <input type="url" name="gform_link" class="form-control" placeholder="https://forms.gle/...">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Club <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="3" required placeholder="Profil singkat, visi misi, dan kegiatan skirmish club..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Logo Club</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, WEBP. Maksimal 2MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Club</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Club --}}
<div class="modal fade" id="modalEditClub" tabindex="-1" aria-labelledby="modalEditClubLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditClub" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalEditClubLabel">
                        <i class="bi bi-pencil-square text-primary me-1"></i> Edit Data Club
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Club <span class="text-danger">*</span></label>
                        <input type="text" id="editClubNama" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Induk Organisasi <span class="text-danger">*</span></label>
                        <input type="text" id="editClubInduk" name="induk_organisasi" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Provinsi <span class="text-danger">*</span></label>
                        <select id="editClubProvinsi" name="id_provinsi" class="form-select" required>
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kota / Daerah <span class="text-danger">*</span></label>
                        <input type="text" id="editClubCity" name="city" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Link Formulir Pendaftaran</label>
                        <input type="url" id="editClubGform" name="gform_link" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Club <span class="text-danger">*</span></label>
                        <textarea id="editClubDeskripsi" name="deskripsi" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Ganti Logo Club (opsional)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah logo club saat ini.</div>
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

{{-- Modal Detail Club --}}
<div class="modal fade" id="modalDetailClub" tabindex="-1" aria-labelledby="modalDetailClubLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalDetailClubLabel">
                    <i class="bi bi-info-circle text-primary me-1"></i> Rincian Club
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center" id="detailClubLogoContainer" style="display: none;">
                    <img id="detailClubLogo" src="" alt="Logo" class="img-fluid rounded border p-1" style="max-height: 120px; object-fit: contain;">
                </div>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 140px;">Nama Club</td>
                            <td class="fw-bold" id="detailClubNama">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Induk Organisasi</td>
                            <td id="detailClubInduk">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kota & Provinsi</td>
                            <td id="detailClubWilayah">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pemilik / User</td>
                            <td id="detailClubPemilik">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Link GForm</td>
                            <td>
                                <a id="detailClubGform" href="#" target="_blank" rel="noopener noreferrer" class="text-break">-</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted align-top">Deskripsi</td>
                            <td id="detailClubDeskripsi" style="white-space: pre-line;">-</td>
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
        // 1. Tangani pengisian data Modal Edit Club
        const formEditClub = document.getElementById('formEditClub');
        document.querySelectorAll('.btn-edit-club').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                formEditClub.action = "{{ url('admin/club') }}/" + id;

                document.getElementById('editClubNama').value = this.dataset.nama || '';
                document.getElementById('editClubInduk').value = this.dataset.induk || '';
                document.getElementById('editClubProvinsi').value = this.dataset.idProvinsi || '';
                document.getElementById('editClubCity').value = this.dataset.city || '';
                document.getElementById('editClubGform').value = this.dataset.gform || '';
                document.getElementById('editClubDeskripsi').value = this.dataset.deskripsi || '';
            });
        });

        // 2. Tangani pengisian data Modal Detail Club
        document.querySelectorAll('.btn-detail-club').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('detailClubNama').textContent = this.dataset.nama || '-';
                document.getElementById('detailClubInduk').textContent = this.dataset.induk || '-';
                document.getElementById('detailClubWilayah').textContent = (this.dataset.city || '-') + ' (' + (this.dataset.provinsi || '-') + ')';
                document.getElementById('detailClubPemilik').textContent = this.dataset.pemilik || '-';

                const gform = this.dataset.gform;
                const gformLink = document.getElementById('detailClubGform');
                if (gform && gform !== '-') {
                    gformLink.href = gform;
                    gformLink.textContent = gform;
                    gformLink.style.display = 'inline';
                } else {
                    gformLink.textContent = '-';
                    gformLink.removeAttribute('href');
                }

                document.getElementById('detailClubDeskripsi').textContent = this.dataset.deskripsi || '-';

                const logo = this.dataset.logo;
                const logoContainer = document.getElementById('detailClubLogoContainer');
                const logoImg = document.getElementById('detailClubLogo');
                if (logo) {
                    logoImg.src = logo;
                    logoContainer.style.display = 'block';
                } else {
                    logoContainer.style.display = 'none';
                }
            });
        });

        // 3. Konfirmasi sebelum Hapus Club
        document.querySelectorAll('.form-delete-club').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!confirm('Apakah Anda yakin ingin menghapus data club ini? Tindakan ini tidak dapat dibatalkan.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
