@extends('layout.admin')

@section('title', 'Manajemen Event')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">{{ __('Event Management') }}</h1>
        <p class="subtitle mb-0">{{ __('Monitor events that are published, running, or awaiting approval.') }}</p>
    </div>
    <button class="btn btn-primary px-3">+ {{ __('Create Event') }}</button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue">✦</div>
                <span class="pill success">+10%</span>
            </div>
            <div class="stat-label">Event Aktif</div>
            <p class="stat-value">46</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green">✓</div>
                <span class="pill success">7</span>
            </div>
            <div class="stat-label">Disetujui</div>
            <p class="stat-value">32</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange">⏳</div>
                <span class="pill warning">3</span>
            </div>
            <div class="stat-label">Pending</div>
            <p class="stat-value">11</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple">👥</div>
                <span class="pill danger">-0.2%</span>
            </div>
            <div class="stat-label">Peserta</div>
            <p class="stat-value">1.6K</p>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h2 class="panel-title">Agenda Event</h2>
        <button class="btn btn-sm btn-outline-primary">Lihat semua</button>
    </div>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Pendaftar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="member-name">Airsoft Challenge 2026</span></td>
                        <td>Surabaya</td>
                        <td>21 Agu 2026</td>
                        <td><span class="pill success">Aktif</span></td>
                        <td>240</td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Weekend Ops</span></td>
                        <td>Jakarta</td>
                        <td>08 Sep 2026</td>
                        <td><span class="pill warning">Pending</span></td>
                        <td>108</td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Marshall Battle</span></td>
                        <td>Bandung</td>
                        <td>18 Sep 2026</td>
                        <td><span class="pill success">Disetujui</span></td>
                        <td>182</td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Night Training</span></td>
                        <td>Semarang</td>
                        <td>26 Sep 2026</td>
                        <td><span class="pill danger">Review</span></td>
                        <td>54</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
