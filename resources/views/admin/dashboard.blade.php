@extends('layout.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">{{ __('Dashboard Admin') }}</h1>
        <p class="subtitle mb-0">{{ __('Summary of club and event platform activity today.') }}</p>
    </div>
    <button class="btn btn-primary px-3">{{ __('Download report') }}</button>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue">◫</div>
                <span class="pill success">+12.4%</span>
            </div>
            <div class="stat-label">{{ __('Total Club') }}</div>
            <p class="stat-value">128</p>
            <div class="trend up">▲ 18 club baru bulan ini</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green">✦</div>
                <span class="pill success">+8.2%</span>
            </div>
            <div class="stat-label">{{ __('Active Events') }}</div>
            <p class="stat-value">46</p>
            <div class="trend up">▲ 9 event disetujui</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange">₩</div>
                <span class="pill warning">+5.1%</span>
            </div>
            <div class="stat-label">{{ __('Revenue') }}</div>
            <p class="stat-value">Rp 68.4J</p>
            <div class="trend up">▲ Rp 3.2J dari minggu lalu</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple">◌</div>
                <span class="pill danger">-2.3%</span>
            </div>
            <div class="stat-label">{{ __('Pending') }}</div>
            <p class="stat-value">21</p>
            <div class="trend down">▼ 3 butuh review</div>
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
                    <li class="activity-item">
                        <span class="dot blue"></span>
                        <div>
                            <div class="activity-text">Club <strong>Ranger Squad</strong> baru ditambahkan.</div>
                            <div class="activity-time">10 menit yang lalu</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <span class="dot green"></span>
                        <div>
                            <div class="activity-text">Event <strong>Airsoft Challenge</strong> disetujui.</div>
                            <div class="activity-time">1 jam yang lalu</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <span class="dot orange"></span>
                        <div>
                            <div class="activity-text">21 pesanan menunggu verifikasi pembayaran.</div>
                            <div class="activity-time">3 jam yang lalu</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h2 class="panel-title">{{ __('Latest list') }}</h2>
        <button class="btn btn-sm btn-outline-primary">{{ __('View all') }}</button>
    </div>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="member-name">Arjuna Tactical</span></td>
                        <td>Club</td>
                        <td><span class="pill success">Aktif</span></td>
                        <td>Bandung</td>
                        <td>20 Jun 2026</td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Jakarta Airsoft Expo</span></td>
                        <td>Event</td>
                        <td><span class="pill warning">Pending</span></td>
                        <td>Jakarta</td>
                        <td>22 Jun 2026</td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Gear Battle Store</span></td>
                        <td>Marketplace</td>
                        <td><span class="pill success">Verified</span></td>
                        <td>Surabaya</td>
                        <td>25 Jun 2026</td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Night Ops Team</span></td>
                        <td>Club</td>
                        <td><span class="pill danger">Review</span></td>
                        <td>Semarang</td>
                        <td>27 Jun 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
