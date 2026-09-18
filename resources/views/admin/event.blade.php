@extends('layout.admin')

@section('title', 'Manajemen Event')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">{{ __('Event Management') }}</h1>
        <p class="subtitle mb-0">{{ __('Kelola agenda turnamen, skirmish, dan kejuaraan airsoft.') }}</p>
    </div>
    <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTambahEvent">
        <i class="bi bi-plus-lg me-1"></i> {{ __('Buat Event') }}
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
    $today = \Carbon\Carbon::today()->toDateString();
    $upcomingCount = $events->where('tanggal', '>=', $today)->count();
    $pastCount = $events->where('tanggal', '<', $today)->count();
    $uniqueCities = $events->pluck('kota')->filter()->unique()->count();
@endphp
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue">✦</div>
                <span class="pill success">Total</span>
            </div>
            <div class="stat-label">Total Event</div>
            <p class="stat-value">{{ count($events) }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green">✓</div>
                <span class="pill success">Aktif</span>
            </div>
            <div class="stat-label">Mendatang</div>
            <p class="stat-value">{{ $upcomingCount }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange">⏳</div>
                <span class="pill warning">Selesai</span>
            </div>
            <div class="stat-label">Terlaksana</div>
            <p class="stat-value">{{ $pastCount }}</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple">📍</div>
                <span class="pill success">Wilayah</span>
            </div>
            <div class="stat-label">Kota Pelaksana</div>
            <p class="stat-value">{{ $uniqueCities }}</p>
        </div>
    </div>
</div>

{{-- Panel Tabel Agenda Event --}}
<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h2 class="panel-title mb-0">Agenda Event & Turnamen</h2>
        <span class="text-muted small">Total: {{ count($events) }} kegiatan</span>
    </div>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">Poster</th>
                        <th>Nama Event</th>
                        <th>Penyelenggara</th>
                        <th>Lokasi & Kota</th>
                        <th>Tanggal</th>
                        <th>HTM</th>
                        <th>Status</th>
                        <th class="text-end" style="min-width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $evt)
                    @php
                        $posters = $evt->poster ?? [];
                        $posterThumb = !empty($posters) && is_array($posters) && count($posters) > 0
                            ? asset('storage/' . $posters[0])
                            : null;
                        $postersJson = json_encode(!empty($posters) && is_array($posters)
                            ? array_map(fn($p) => asset('storage/' . $p), $posters)
                            : []);
                        $isUpcoming = $evt->tanggal >= $today;
                        $provObj = isset($provinsi) ? $provinsi->firstWhere('id', $evt->id_provinsi) : null;
                        $provNama = $provObj ? $provObj->provinsi : '-';
                    @endphp
                    <tr>
                        <td>
                            @if($posterThumb)
                                <img src="{{ $posterThumb }}" alt="Poster" class="rounded object-fit-cover" style="width: 44px; height: 60px;" onerror="this.onerror=null; this.replaceWith(document.createElement('div'))">
                            @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted" style="width: 44px; height: 60px; font-size: 0.75rem;">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="member-name fw-bold text-dark">{{ $evt->nama }}</span>
                            @if($evt->kelasPertandingan)
                                <small class="d-block text-muted"><i class="bi bi-trophy"></i> {{ $evt->kelasPertandingan }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="text-dark">{{ $evt->penyelenggara }}</span>
                        </td>
                        <td>
                            <div>{{ $evt->kota }}</div>
                            <small class="text-muted">{{ $evt->lokasi ?: $provNama }}</small>
                        </td>
                        <td>
                            <div>{{ $evt->tanggal ? \Carbon\Carbon::parse($evt->tanggal)->translatedFormat('d M Y') : '-' }}</div>
                            <small class="text-muted">{{ $evt->tanggal ? \Carbon\Carbon::parse($evt->tanggal)->diffForHumans() : '' }}</small>
                        </td>
                        <td>
                            @if($evt->htm === null || $evt->htm == 0)
                                <span class="badge text-bg-success">Gratis</span>
                            @else
                                <span class="fw-semibold text-dark">Rp {{ number_format($evt->htm, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($isUpcoming)
                                <span class="pill success">Mendatang</span>
                            @else
                                <span class="pill warning">Selesai</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-detail-event me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalDetailEvent"
                                    data-nama="{{ $evt->nama }}"
                                    data-tanggal="{{ $evt->tanggal ? \Carbon\Carbon::parse($evt->tanggal)->translatedFormat('d F Y') : '-' }}"
                                    data-penyelenggara="{{ $evt->penyelenggara }}"
                                    data-kota="{{ $evt->kota }}"
                                    data-lokasi="{{ $evt->lokasi ?? '-' }}"
                                    data-provinsi="{{ $provNama }}"
                                    data-htm="{{ $evt->htm !== null && $evt->htm > 0 ? 'Rp ' . number_format($evt->htm, 0, ',', '.') : 'Gratis' }}"
                                    data-kelas="{{ $evt->kelasPertandingan ?? '-' }}"
                                    data-sumber="{{ $evt->sumber ?? '-' }}"
                                    data-deskripsi="{{ $evt->deskripsi ?? 'Tidak ada deskripsi.' }}"
                                    data-posters='{{ $postersJson }}'
                                    title="Detail Event">
                                <i class="bi bi-eye"></i> Detail
                            </button>

                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-event me-1"
                                    data-bs-toggle="modal" data-bs-target="#modalEditEvent"
                                    data-id="{{ $evt->id }}"
                                    data-nama="{{ $evt->nama }}"
                                    data-tanggal="{{ $evt->tanggal }}"
                                    data-penyelenggara="{{ $evt->penyelenggara }}"
                                    data-lokasi="{{ $evt->lokasi }}"
                                    data-id-provinsi="{{ $evt->id_provinsi }}"
                                    data-kota="{{ $evt->kota }}"
                                    data-sumber="{{ $evt->sumber }}"
                                    data-htm="{{ $evt->htm }}"
                                    data-kelas="{{ $evt->kelasPertandingan }}"
                                    data-deskripsi="{{ $evt->deskripsi }}"
                                    title="Edit Event">
                                <i class="bi bi-pencil"></i> Edit
                            </button>

                            <form action="{{ route('admin.event.destroy', $evt->id) }}" method="POST" class="d-inline form-delete-event">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Event">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                            Belum ada agenda event yang tersimpan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODALS EVENT                                              --}}
{{-- ======================================================== --}}

{{-- Modal Tambah Event --}}
<div class="modal fade" id="modalTambahEvent" tabindex="-1" aria-labelledby="modalTambahEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahEventLabel">
                        <i class="bi bi-calendar-plus text-primary me-1"></i> Tambah Event Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Java Airsoft Tactical Skirmish 2026">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Event <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" name="penyelenggara" class="form-control" required placeholder="Contoh: Komunitas Airsoft Surabaya">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Provinsi</label>
                        <select name="id_provinsi" class="form-select">
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kota <span class="text-danger">*</span></label>
                        <input type="text" name="kota" class="form-control" required placeholder="Contoh: Surabaya">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi / Arena Lengkap</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lapangan Tembak Kodam V Brawijaya">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">HTM / Biaya Masuk (Rp)</label>
                        <input type="number" name="htm" class="form-control" min="0" placeholder="Kosongkan jika gratis">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kelas Pertandingan</label>
                        <input type="text" name="kelasPertandingan" class="form-control" placeholder="Contoh: CQB Open, Woodland 5v5, Sniper Rifle">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Sumber / Link Pendaftaran</label>
                        <input type="url" name="sumber" class="form-control" placeholder="https://forms.gle/... atau https://instagram.com/...">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Event</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Informasi detail aturan, regulasi FPS unit, dan jadwal acara..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Poster Event (Maksimal 3 file)</label>
                        <input type="file" name="poster[]" class="form-control" multiple accept="image/*">
                        <div class="form-text">Format: JPG, PNG, WEBP. Maksimal 2MB per foto.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Event --}}
<div class="modal fade" id="modalEditEvent" tabindex="-1" aria-labelledby="modalEditEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditEvent" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalEditEventLabel">
                        <i class="bi bi-pencil-square text-primary me-1"></i> Edit Data Event
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" id="editEventNama" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="editEventTanggal" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" id="editEventPenyelenggara" name="penyelenggara" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Provinsi</label>
                        <select id="editEventProvinsi" name="id_provinsi" class="form-select">
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kota <span class="text-danger">*</span></label>
                        <input type="text" id="editEventKota" name="kota" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi Lengkap</label>
                        <input type="text" id="editEventLokasi" name="lokasi" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">HTM (Rp)</label>
                        <input type="number" id="editEventHtm" name="htm" class="form-control" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kelas Pertandingan</label>
                        <input type="text" id="editEventKelas" name="kelasPertandingan" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Sumber / Link Pendaftaran</label>
                        <input type="url" id="editEventSumber" name="sumber" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea id="editEventDeskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Ganti Poster (Maks. 3 file, opsional)</label>
                        <input type="file" name="poster[]" class="form-control" multiple accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengganti poster yang sudah ada.</div>
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

{{-- Modal Detail Event --}}
<div class="modal fade" id="modalDetailEvent" tabindex="-1" aria-labelledby="modalDetailEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalDetailEventLabel">
                    <i class="bi bi-info-circle text-primary me-1"></i> Rincian Event
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div id="detailEventPostersContainer" class="d-flex flex-wrap gap-2 justify-content-center bg-light p-2 rounded border">
                            <span class="text-muted small">Tidak ada poster</span>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted" style="width: 140px;">Nama Event</td>
                                    <td class="fw-bold" id="detailEventNama">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal</td>
                                    <td id="detailEventTanggal">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Penyelenggara</td>
                                    <td id="detailEventPenyelenggara">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kota & Provinsi</td>
                                    <td id="detailEventWilayah">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Lokasi</td>
                                    <td id="detailEventLokasi">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">HTM</td>
                                    <td id="detailEventHtm" class="fw-semibold text-success">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kelas</td>
                                    <td id="detailEventKelas">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Link Registrasi</td>
                                    <td>
                                        <a id="detailEventSumber" href="#" target="_blank" rel="noopener noreferrer" class="text-break">-</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted align-top">Deskripsi</td>
                                    <td id="detailEventDeskripsi" style="white-space: pre-line;">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
        // 1. Tangani pengisian data Modal Edit Event
        const formEditEvent = document.getElementById('formEditEvent');
        document.querySelectorAll('.btn-edit-event').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                formEditEvent.action = "{{ url('admin/event') }}/" + id;

                document.getElementById('editEventNama').value = this.dataset.nama || '';
                document.getElementById('editEventTanggal').value = this.dataset.tanggal || '';
                document.getElementById('editEventPenyelenggara').value = this.dataset.penyelenggara || '';
                document.getElementById('editEventLokasi').value = this.dataset.lokasi || '';
                document.getElementById('editEventProvinsi').value = this.dataset.idProvinsi || '';
                document.getElementById('editEventKota').value = this.dataset.kota || '';
                document.getElementById('editEventSumber').value = this.dataset.sumber || '';
                document.getElementById('editEventHtm').value = this.dataset.htm || '';
                document.getElementById('editEventKelas').value = this.dataset.kelas || '';
                document.getElementById('editEventDeskripsi').value = this.dataset.deskripsi || '';
            });
        });

        // 2. Tangani pengisian data Modal Detail Event
        document.querySelectorAll('.btn-detail-event').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('detailEventNama').textContent = this.dataset.nama || '-';
                document.getElementById('detailEventTanggal').textContent = this.dataset.tanggal || '-';
                document.getElementById('detailEventPenyelenggara').textContent = this.dataset.penyelenggara || '-';
                document.getElementById('detailEventWilayah').textContent = (this.dataset.kota || '-') + ' (' + (this.dataset.provinsi || '-') + ')';
                document.getElementById('detailEventLokasi').textContent = this.dataset.lokasi || '-';
                document.getElementById('detailEventHtm').textContent = this.dataset.htm || '-';
                document.getElementById('detailEventKelas').textContent = this.dataset.kelas || '-';

                const sumber = this.dataset.sumber;
                const sumberLink = document.getElementById('detailEventSumber');
                if (sumber && sumber !== '-') {
                    sumberLink.href = sumber;
                    sumberLink.textContent = sumber;
                    sumberLink.style.display = 'inline';
                } else {
                    sumberLink.textContent = '-';
                    sumberLink.removeAttribute('href');
                }

                document.getElementById('detailEventDeskripsi').textContent = this.dataset.deskripsi || '-';

                // Poster gallery rendering
                const postersContainer = document.getElementById('detailEventPostersContainer');
                postersContainer.innerHTML = '';
                try {
                    const posters = JSON.parse(this.dataset.posters || '[]');
                    if (posters && posters.length > 0) {
                        posters.forEach(src => {
                            const img = document.createElement('img');
                            img.src = src;
                            img.alt = 'Poster';
                            img.className = 'rounded object-fit-cover shadow-sm';
                            img.style.width = '100px';
                            img.style.height = '140px';
                            postersContainer.appendChild(img);
                        });
                    } else {
                        postersContainer.innerHTML = '<span class="text-muted small py-4">Tidak ada poster untuk event ini.</span>';
                    }
                } catch (e) {
                    postersContainer.innerHTML = '<span class="text-muted small py-4">Gagal memuat poster.</span>';
                }
            });
        });

        // 3. Konfirmasi sebelum Hapus Event
        document.querySelectorAll('.form-delete-event').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!confirm('Apakah Anda yakin ingin menghapus data event ini? Tindakan ini akan menghapus seluruh data dan poster terkait.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
