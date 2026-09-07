@extends('layout.admin')

@section('title', 'Registrasi Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Registrasi</h1>
        <p class="subtitle mb-0">Kelola pendaftar baru, verifikasi akun, dan approval anggota.</p>
    </div>
    <button class="btn btn-primary px-3">Export Data</button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue">👤</div>
                <span class="pill success">+11%</span>
            </div>
            <div class="stat-label">Pendaftar</div>
            <p class="stat-value">342</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green">✓</div>
                <span class="pill success">81%</span>
            </div>
            <div class="stat-label">Disetujui</div>
            <p class="stat-value">278</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange">⏳</div>
                <span class="pill warning">18</span>
            </div>
            <div class="stat-label">Pending</div>
            <p class="stat-value">46</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple">⚠</div>
                <span class="pill danger">7</span>
            </div>
            <div class="stat-label">Ditolak</div>
            <p class="stat-value">18</p>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h2 class="panel-title">Daftar Pendaftar</h2>
        <button class="btn btn-sm btn-outline-primary">Verifikasi Massal</button>
    </div>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Club</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="member-name">Aditya Pratama</span></td>
                        <td>aditya@mail.com</td>
                        <td>Ranger Squad</td>
                        <td><span class="pill warning">Pending</span></td>
                        <td><button class="btn btn-sm btn-outline-primary">Review</button></td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Rizky Ananda</span></td>
                        <td>rizky@mail.com</td>
                        <td>Jakarta Tactical</td>
                        <td><span class="pill success">Disetujui</span></td>
                        <td><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Nadia Sari</span></td>
                        <td>nadia@mail.com</td>
                        <td>Night Ops Team</td>
                        <td><span class="pill danger">Ditolak</span></td>
                        <td><button class="btn btn-sm btn-outline-primary">Lihat</button></td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Fajar Yusuf</span></td>
                        <td>fajar@mail.com</td>
                        <td>Blue Horizon</td>
                        <td><span class="pill warning">Pending</span></td>
                        <td><button class="btn btn-sm btn-outline-primary">Review</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
