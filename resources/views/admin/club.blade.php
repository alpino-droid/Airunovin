@extends('layout.admin')

@section('title', 'Manajemen Club')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">{{ __('Club Management') }}</h1>
        <p class="subtitle mb-0">{{ __('Manage clubs, members, and active community status.') }}</p>
    </div>
    <button class="btn btn-primary px-3">+ {{ __('Add Club') }}</button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue">◫</div>
                <span class="pill success">+8.5%</span>
            </div>
            <div class="stat-label">{{ __('Total Club') }}</div>
            <p class="stat-value">128</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green">✓</div>
                <span class="pill success">+12%</span>
            </div>
            <div class="stat-label">Aktif</div>
            <p class="stat-value">96</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange">⏳</div>
                <span class="pill warning">4</span>
            </div>
            <div class="stat-label">Review</div>
            <p class="stat-value">14</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple">👥</div>
                <span class="pill danger">-1.3%</span>
            </div>
            <div class="stat-label">Anggota</div>
            <p class="stat-value">2.4K</p>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h2 class="panel-title">Daftar Club</h2>
        <button class="btn btn-sm btn-outline-primary">Filter</button>
    </div>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama Club</th>
                        <th>Region</th>
                        <th>Anggota</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="member-name">Ranger Squad</span></td>
                        <td>Bandung</td>
                        <td>320</td>
                        <td><span class="pill success">Aktif</span></td>
                        <td><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Jakarta Tactical</span></td>
                        <td>Jakarta</td>
                        <td>540</td>
                        <td><span class="pill success">Aktif</span></td>
                        <td><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Night Ops Team</span></td>
                        <td>Semarang</td>
                        <td>180</td>
                        <td><span class="pill danger">Review</span></td>
                        <td><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Blue Horizon</span></td>
                        <td>Surabaya</td>
                        <td>260</td>
                        <td><span class="pill warning">Pending</span></td>
                        <td><button class="btn btn-sm btn-outline-primary">Detail</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
