@extends('layout/dashboard')

@section('title', isset($marketplace) ? 'Edit Marketplace' : 'Buat Marketplace')

@section('content')
<div class="container text-start pt-3 pb-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Marketplace</p>
            <h1 class="h2 fw-bold mb-1">{{ isset($marketplace) ? 'Edit Marketplace' : 'Buat Marketplace' }}</h1>
            <p class="text-muted mb-0">Atur identitas toko yang akan menaungi produk Anda.</p>
        </div>
        <a href="{{ route('dashboardMarketplace') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <form action="{{ isset($marketplace) ? route('marketplace.update', $marketplace) : route('marketplace.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($marketplace)) @method('PUT') @endif
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="nama" class="form-label fw-semibold">Nama marketplace</label>
                        <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $marketplace->nama ?? '') }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(['active' => 'Aktif', 'inactive' => 'Nonaktif'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $marketplace->status ?? 'active') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="logo" class="form-label fw-semibold">Logo marketplace</label>
                        <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" maxlength="1000" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $marketplace->deskripsi ?? '') }}</textarea>
                        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('dashboardMarketplace') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">{{ isset($marketplace) ? 'Simpan Perubahan' : 'Buat Marketplace' }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
