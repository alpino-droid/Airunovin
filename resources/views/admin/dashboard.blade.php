@extends('layout.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">{{ __('Dashboard Admin') }}</h1>
        <p class="subtitle mb-0">{{ __('Summary and management of club and event platform activity today.') }}</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTambahEvent">
            + {{ __('Tambah Event') }}
        </button>
        <button type="button" class="btn btn-outline-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTambahClub">
            + {{ __('Tambah Club') }}
        </button>
    </div>
</div>

{{-- Flash Notification Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <span>✓</span>
            <div><strong>Berhasil!</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <span>⚠</span>
            <div><strong>Gagal!</strong> {{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center gap-2 mb-1">
            <span>⚠</span>
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
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue">◫</div>
                <span class="pill success">+12.4%</span>
            </div>
            <div class="stat-label">{{ __('Total Club') }}</div>
            <p class="stat-value">{{ count($clubs) }}</p>
            <div class="trend up">▲ {{ count($clubs) }} club terdaftar</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green">✦</div>
                <span class="pill success">+8.2%</span>
            </div>
            <div class="stat-label">{{ __('Active Events') }}</div>
            <p class="stat-value">{{ count($events) }}</p>
            <div class="trend up">▲ {{ count($events) }} event aktif</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange">📦</div>
                <span class="pill warning">+5.1%</span>
            </div>
            <div class="stat-label">{{ __('Total Produk') }}</div>
            <p class="stat-value">{{ $totalProducts ?? 0 }}</p>
            <div class="trend up">▲ Produk marketplace</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple">👤</div>
                <span class="pill success">+11%</span>
            </div>
            <div class="stat-label">{{ __('Total Pendaftar') }}</div>
            <p class="stat-value">{{ $totalUsers ?? 0 }}</p>
            <div class="trend up">▲ Pengguna terdaftar</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="panel h-100">
            <div class="panel-header d-flex justify-content-between align-items-center">
                <h2 class="panel-title">{{ __('Activity trend') }}</h2>
                <span class="text-muted small">Juni 2026</span>
            </div>
            <div class="panel-body">
                <div class="bar-chart">
                    <div class="bar-col"><div class="bar" style="height: 35%;"></div><div class="bar-label">Jan</div></div>
                    <div class="bar-col"><div class="bar" style="height: 52%;"></div><div class="bar-label">Feb</div></div>
                    <div class="bar-col"><div class="bar" style="height: 46%;"></div><div class="bar-label">Mar</div></div>
                    <div class="bar-col"><div class="bar" style="height: 70%;"></div><div class="bar-label">Apr</div></div>
                    <div class="bar-col"><div class="bar" style="height: 66%;"></div><div class="bar-label">Mei</div></div>
                    <div class="bar-col"><div class="bar" style="height: 86%;"></div><div class="bar-label">Jun</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="panel h-100">
            <div class="panel-header">
                <h2 class="panel-title">{{ __('Recent activity') }}</h2>
            </div>
            <div class="panel-body">
                <ul class="activity-list">
                    @forelse($clubs->take(2) as $latestClub)
                        <li class="activity-item">
                            <span class="dot blue"></span>
                            <div>
                                <div class="activity-text">Club <strong>{{ $latestClub->nama }}</strong> aktif di {{ $latestClub->city }}.</div>
                                <div class="activity-time">{{ $latestClub->created_at ? $latestClub->created_at->diffForHumans() : 'Baru saja' }}</div>
                            </div>
                        </li>
                    @empty
                    @endforelse

                    @forelse($events->take(2) as $latestEvent)
                        <li class="activity-item">
                            <span class="dot green"></span>
                            <div>
                                <div class="activity-text">Event <strong>{{ $latestEvent->nama }}</strong> dijadwalkan di {{ $latestEvent->kota }}.</div>
                                <div class="activity-time">{{ $latestEvent->created_at ? $latestEvent->created_at->diffForHumans() : 'Baru saja' }}</div>
                            </div>
                        </li>
                    @empty
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- CRUD PANEL UNTUK EVENT & CLUB --}}
<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <h2 class="panel-title">{{ __('Manajemen Data (CRUD)') }}</h2>
            <ul class="nav nav-pills" id="crudTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-1 px-3" id="tab-events" data-bs-toggle="pill" data-bs-target="#content-events" type="button" role="tab" aria-controls="content-events" aria-selected="true">
                        Event ({{ count($events) }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-1 px-3" id="tab-clubs" data-bs-toggle="pill" data-bs-target="#content-clubs" type="button" role="tab" aria-controls="content-clubs" aria-selected="false">
                        Club ({{ count($clubs) }})
                    </button>
                </li>
            </ul>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahEvent">
                + Tambah Event
            </button>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahClub">
                + Tambah Club
            </button>
        </div>
    </div>

    <div class="panel-body p-0">
        <div class="tab-content" id="crudTabsContent">
            
            {{-- TAB 1: CRUD EVENT --}}
            <div class="tab-pane fade show active" id="content-events" role="tabpanel" aria-labelledby="tab-events">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Nama Event</th>
                                <th>Penyelenggara</th>
                                <th>Kota</th>
                                <th>Tanggal</th>
                                <th>HTM</th>
                                <th class="text-center" style="width: 190px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="member-name">{{ $event->nama }}</span>
                                    </div>
                                </td>
                                <td>{{ $event->penyelenggara }}</td>
                                <td>{{ $event->kota }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</td>
                                <td>
                                    @if($event->htm)
                                        Rp {{ number_format($event->htm, 0, ',', '.') }}
                                    @else
                                        <span class="pill success">Gratis</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-info btn-detail-event" 
                                            data-id="{{ $event->id }}"
                                            data-nama="{{ $event->nama }}"
                                            data-penyelenggara="{{ $event->penyelenggara }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($event->tanggal)->format('Y-m-d') }}"
                                            data-kota="{{ $event->kota }}"
                                            data-lokasi="{{ $event->lokasi ?? '-' }}"
                                            data-htm="{{ $event->htm ?? 0 }}"
                                            data-kelas="{{ $event->kelasPertandingan ?? '-' }}"
                                            data-sumber="{{ $event->sumber ?? '-' }}"
                                            data-deskripsi="{{ $event->deskripsi ?? '-' }}"
                                            data-posters="{{ json_encode($event->poster ?? []) }}">
                                            Detail
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-event"
                                            data-id="{{ $event->id }}"
                                            data-nama="{{ $event->nama }}"
                                            data-penyelenggara="{{ $event->penyelenggara }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($event->tanggal)->format('Y-m-d') }}"
                                            data-kota="{{ $event->kota }}"
                                            data-lokasi="{{ $event->lokasi }}"
                                            data-provinsi="{{ $event->id_provinsi }}"
                                            data-htm="{{ $event->htm }}"
                                            data-kelas="{{ $event->kelasPertandingan }}"
                                            data-sumber="{{ $event->sumber }}"
                                            data-deskripsi="{{ $event->deskripsi }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event \'{{ addslashes($event->nama) }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada data event. Klik "+ Tambah Event" untuk menambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: CRUD CLUB --}}
            <div class="tab-pane fade" id="content-clubs" role="tabpanel" aria-labelledby="tab-clubs">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Nama Club</th>
                                <th>Induk Organisasi</th>
                                <th>Kota</th>
                                <th>Pemilik / User</th>
                                <th class="text-center" style="width: 190px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clubs as $club)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($club->logo && $club->logo !== 'default_club.png')
                                            <img src="{{ asset('storage/' . $club->logo) }}" alt="Logo" style="width: 28px; height: 28px; border-radius: 6px; object-fit: cover;">
                                        @else
                                            <div style="width: 28px; height: 28px; border-radius: 6px; background: #e2e8f0; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;">CL</div>
                                        @endif
                                        <span class="member-name">{{ $club->nama }}</span>
                                    </div>
                                </td>
                                <td>{{ $club->induk_organisasi }}</td>
                                <td>{{ $club->city }}</td>
                                <td>{{ $club->user->nama ?? $club->user->username ?? 'Admin' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-info btn-detail-club"
                                            data-id="{{ $club->id }}"
                                            data-nama="{{ $club->nama }}"
                                            data-induk="{{ $club->induk_organisasi }}"
                                            data-city="{{ $club->city }}"
                                            data-provinsi="{{ $club->id_provinsi }}"
                                            data-gform="{{ $club->gform_link ?? '-' }}"
                                            data-deskripsi="{{ $club->deskripsi }}"
                                            data-pemilik="{{ $club->user->nama ?? '-' }}"
                                            data-logo="{{ $club->logo && $club->logo !== 'default_club.png' ? asset('storage/' . $club->logo) : '' }}">
                                            Detail
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-club"
                                            data-id="{{ $club->id }}"
                                            data-nama="{{ $club->nama }}"
                                            data-induk="{{ $club->induk_organisasi }}"
                                            data-city="{{ $club->city }}"
                                            data-provinsi="{{ $club->id_provinsi }}"
                                            data-gform="{{ $club->gform_link }}"
                                            data-deskripsi="{{ $club->deskripsi }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.club.destroy', $club->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus club \'{{ addslashes($club->nama) }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada data club. Klik "+ Tambah Club" untuk menambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

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
                    <h5 class="modal-title" id="modalTambahEventLabel">Tambah Event Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Tactical Airsoft Challenge">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" name="penyelenggara" class="form-control" required placeholder="Contoh: Komunitas Airsoft Jabar">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Provinsi</label>
                        <select name="id_provinsi" class="form-select">
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kota <span class="text-danger">*</span></label>
                        <input type="text" name="kota" class="form-control" required placeholder="Contoh: Bandung">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Lengkap</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lapangan Tembak Sentul">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">HTM (Rp)</label>
                        <input type="number" name="htm" class="form-control" min="0" placeholder="Kosongkan jika gratis">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kelas Pertandingan</label>
                        <input type="text" name="kelasPertandingan" class="form-control" placeholder="Contoh: Open, Junior, Pro">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Sumber / Tautan Pendaftaran</label>
                        <input type="text" name="sumber" class="form-control" placeholder="https://...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Rincian informasi event..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Poster Event (Maks. 3)</label>
                        <input type="file" name="poster[]" class="form-control" multiple accept="image/*">
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
                    <h5 class="modal-title" id="modalEditEventLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" id="editEventNama" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="editEventTanggal" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" id="editEventPenyelenggara" name="penyelenggara" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Provinsi</label>
                        <select id="editEventProvinsi" name="id_provinsi" class="form-select">
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kota <span class="text-danger">*</span></label>
                        <input type="text" id="editEventKota" name="kota" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Lengkap</label>
                        <input type="text" id="editEventLokasi" name="lokasi" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">HTM (Rp)</label>
                        <input type="number" id="editEventHtm" name="htm" class="form-control" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kelas Pertandingan</label>
                        <input type="text" id="editEventKelas" name="kelasPertandingan" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Sumber / Tautan Pendaftaran</label>
                        <input type="text" id="editEventSumber" name="sumber" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea id="editEventDeskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ganti Poster (Maks. 3, opsional)</label>
                        <input type="file" name="poster[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah poster saat ini.</small>
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
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailEventLabel">Detail Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center" id="detailEventPosterContainer" style="display: none;">
                    <img id="detailEventPoster" src="" alt="Poster" class="img-fluid rounded" style="max-height: 250px;">
                </div>
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 130px;">Nama Event</td>
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
                            <td class="text-muted">Kota</td>
                            <td id="detailEventKota">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Lokasi</td>
                            <td id="detailEventLokasi">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">HTM</td>
                            <td id="detailEventHtm">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kelas</td>
                            <td id="detailEventKelas">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Sumber</td>
                            <td id="detailEventSumber">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Deskripsi</td>
                            <td id="detailEventDeskripsi">-</td>
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
                    <h5 class="modal-title" id="modalTambahClubLabel">Tambah Club Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Club <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Ghost Squad Airsoft">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Induk Organisasi <span class="text-danger">*</span></label>
                        <input type="text" name="induk_organisasi" class="form-control" required placeholder="Contoh: Porgasi / FAI">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                        <select name="id_provinsi" class="form-select" required>
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kota / Daerah <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" required placeholder="Contoh: Surabaya">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Link Formulir Pendaftaran (GForm / Web)</label>
                        <input type="text" name="gform_link" class="form-control" placeholder="https://forms.gle/...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Club <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="3" required placeholder="Profil singkat dan aktivitas club..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Logo Club</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
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
                    <h5 class="modal-title" id="modalEditClubLabel">Edit Club</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Club <span class="text-danger">*</span></label>
                        <input type="text" id="editClubNama" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Induk Organisasi <span class="text-danger">*</span></label>
                        <input type="text" id="editClubInduk" name="induk_organisasi" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                        <select id="editClubProvinsi" name="id_provinsi" class="form-select" required>
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kota / Daerah <span class="text-danger">*</span></label>
                        <input type="text" id="editClubCity" name="city" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Link Formulir Pendaftaran</label>
                        <input type="text" id="editClubGform" name="gform_link" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Club <span class="text-danger">*</span></label>
                        <textarea id="editClubDeskripsi" name="deskripsi" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ganti Logo Club (opsional)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah logo club.</small>
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
                <h5 class="modal-title" id="modalDetailClubLabel">Detail Club</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center" id="detailClubLogoContainer" style="display: none;">
                    <img id="detailClubLogo" src="" alt="Logo" class="img-fluid rounded" style="max-height: 120px; object-fit: contain;">
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
                            <td class="text-muted">Kota</td>
                            <td id="detailClubCity">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pemilik / User</td>
                            <td id="detailClubPemilik">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Form Pendaftaran</td>
                            <td id="detailClubGform">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Deskripsi</td>
                            <td id="detailClubDeskripsi">-</td>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- EVENT: EDIT MODAL ---
    document.querySelectorAll('.btn-edit-event').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const form = document.getElementById('formEditEvent');
            form.action = `/admin/event/${id}`;

            document.getElementById('editEventNama').value = this.dataset.nama || '';
            document.getElementById('editEventTanggal').value = this.dataset.tanggal || '';
            document.getElementById('editEventPenyelenggara').value = this.dataset.penyelenggara || '';
            document.getElementById('editEventKota').value = this.dataset.kota || '';
            document.getElementById('editEventLokasi').value = this.dataset.lokasi || '';
            document.getElementById('editEventProvinsi').value = this.dataset.provinsi || '';
            document.getElementById('editEventHtm').value = this.dataset.htm || '';
            document.getElementById('editEventKelas').value = this.dataset.kelas || '';
            document.getElementById('editEventSumber').value = this.dataset.sumber || '';
            document.getElementById('editEventDeskripsi').value = this.dataset.deskripsi || '';

            const modal = new bootstrap.Modal(document.getElementById('modalEditEvent'));
            modal.show();
        });
    });

    // --- EVENT: DETAIL MODAL ---
    document.querySelectorAll('.btn-detail-event').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('detailEventNama').textContent = this.dataset.nama || '-';
            document.getElementById('detailEventTanggal').textContent = this.dataset.tanggal || '-';
            document.getElementById('detailEventPenyelenggara').textContent = this.dataset.penyelenggara || '-';
            document.getElementById('detailEventKota').textContent = this.dataset.kota || '-';
            document.getElementById('detailEventLokasi').textContent = this.dataset.lokasi || '-';
            
            const htm = parseInt(this.dataset.htm, 10);
            document.getElementById('detailEventHtm').textContent = (htm && htm > 0) ? 'Rp ' + htm.toLocaleString('id-ID') : 'Gratis';
            document.getElementById('detailEventKelas').textContent = this.dataset.kelas || '-';
            document.getElementById('detailEventSumber').textContent = this.dataset.sumber || '-';
            document.getElementById('detailEventDeskripsi').textContent = this.dataset.deskripsi || '-';

            const posterContainer = document.getElementById('detailEventPosterContainer');
            const posterImg = document.getElementById('detailEventPoster');
            let posters = [];
            try {
                posters = JSON.parse(this.dataset.posters || '[]');
            } catch(e) {
                posters = [];
            }
            if (posters && posters.length > 0) {
                posterImg.src = '/storage/' + posters[0];
                posterContainer.style.display = 'block';
            } else {
                posterContainer.style.display = 'none';
            }

            const modal = new bootstrap.Modal(document.getElementById('modalDetailEvent'));
            modal.show();
        });
    });

    // --- CLUB: EDIT MODAL ---
    document.querySelectorAll('.btn-edit-club').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const form = document.getElementById('formEditClub');
            form.action = `/admin/club/${id}`;

            document.getElementById('editClubNama').value = this.dataset.nama || '';
            document.getElementById('editClubInduk').value = this.dataset.induk || '';
            document.getElementById('editClubCity').value = this.dataset.city || '';
            document.getElementById('editClubProvinsi').value = this.dataset.provinsi || '';
            document.getElementById('editClubGform').value = this.dataset.gform || '';
            document.getElementById('editClubDeskripsi').value = this.dataset.deskripsi || '';

            const modal = new bootstrap.Modal(document.getElementById('modalEditClub'));
            modal.show();
        });
    });

    // --- CLUB: DETAIL MODAL ---
    document.querySelectorAll('.btn-detail-club').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('detailClubNama').textContent = this.dataset.nama || '-';
            document.getElementById('detailClubInduk').textContent = this.dataset.induk || '-';
            document.getElementById('detailClubCity').textContent = this.dataset.city || '-';
            document.getElementById('detailClubPemilik').textContent = this.dataset.pemilik || '-';
            
            const gform = this.dataset.gform;
            const gformEl = document.getElementById('detailClubGform');
            if (gform && gform !== '-' && gform.startsWith('http')) {
                gformEl.innerHTML = `<a href="${gform}" target="_blank" class="text-primary">${gform}</a>`;
            } else {
                gformEl.textContent = gform || '-';
            }
            
            document.getElementById('detailClubDeskripsi').textContent = this.dataset.deskripsi || '-';

            const logoContainer = document.getElementById('detailClubLogoContainer');
            const logoImg = document.getElementById('detailClubLogo');
            const logo = this.dataset.logo;
            if (logo) {
                logoImg.src = logo;
                logoContainer.style.display = 'block';
            } else {
                logoContainer.style.display = 'none';
            }

            const modal = new bootstrap.Modal(document.getElementById('modalDetailClub'));
            modal.show();
        });
    });
});
</script>
@endpush
@endsection
