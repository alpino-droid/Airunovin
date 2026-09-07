@extends('layout.admin')

@section('title', 'Pengaturan Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Pengaturan</h1>
        <p class="subtitle mb-0">Atur konfigurasi platform, keamanan, dan preferensi admin.</p>
    </div>
    <button class="btn btn-primary px-3">Simpan Perubahan</button>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="panel-header">
                <h2 class="panel-title">Umum</h2>
            </div>
            <div class="panel-body">
                <div class="mb-3">
                    <label class="form-label">Nama situs</label>
                    <input type="text" class="form-control" value="Club & Event Platform">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email admin</label>
                    <input type="email" class="form-control" value="admin@domain.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Zona waktu</label>
                    <select class="form-select">
                        <option selected>Asia/Jakarta</option>
                        <option>UTC</option>
                        <option>Asia/Singapore</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel h-100">
            <div class="panel-header">
                <h2 class="panel-title">Keamanan</h2>
            </div>
            <div class="panel-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" checked>
                    <label class="form-check-label">Aktifkan verifikasi dua langkah</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" checked>
                    <label class="form-check-label">Kirim notifikasi login baru</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox">
                    <label class="form-check-label">Batas akses admin dari IP tertentu</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Preferensi Notifikasi</h2>
            </div>
            <div class="panel-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Notifikasi pendaftaran baru</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Notifikasi event baru</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Notifikasi marketplace</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Notifikasi sistem maintenance</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
