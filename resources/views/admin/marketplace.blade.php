@extends('layout.admin')

@section('title', 'Marketplace Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Marketplace</h1>
        <p class="subtitle mb-0">Kelola produk, katalog, dan transaksi penjualan di platform.</p>
    </div>
    <button class="btn btn-primary px-3">+ Produk Baru</button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon blue">◌</div>
                <span class="pill success">+14%</span>
            </div>
            <div class="stat-label">Produk</div>
            <p class="stat-value">482</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon green">₩</div>
                <span class="pill success">+9.2%</span>
            </div>
            <div class="stat-label">Pendapatan</div>
            <p class="stat-value">Rp 84.1J</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon orange">📦</div>
                <span class="pill warning">29</span>
            </div>
            <div class="stat-label">Pesanan</div>
            <p class="stat-value">153</p>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon purple">⚠</div>
                <span class="pill danger">8</span>
            </div>
            <div class="stat-label">Komplain</div>
            <p class="stat-value">12</p>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header d-flex justify-content-between align-items-center">
        <h2 class="panel-title">Produk Terbaru</h2>
        <button class="btn btn-sm btn-outline-primary">Semua produk</button>
    </div>
    <div class="panel-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Penjual</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="member-name">Tactical Vest</span></td>
                        <td>Equipment</td>
                        <td>Rp 450.000</td>
                        <td>Gear Tactical</td>
                        <td><span class="pill success">Tersedia</span></td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Red Dot Sight</span></td>
                        <td>Optic</td>
                        <td>Rp 320.000</td>
                        <td>Battle Store</td>
                        <td><span class="pill success">Tersedia</span></td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Airsoft Pistol</span></td>
                        <td>Weapon</td>
                        <td>Rp 1.250.000</td>
                        <td>Urban Ops</td>
                        <td><span class="pill warning">Pending</span></td>
                    </tr>
                    <tr>
                        <td><span class="member-name">Gloves Tactical</span></td>
                        <td>Apparel</td>
                        <td>Rp 180.000</td>
                        <td>Elite Gear</td>
                        <td><span class="pill danger">Review</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
