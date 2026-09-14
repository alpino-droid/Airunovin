@extends('layout/dashboard')

@section('title', 'Tambah Produk')

@section('content')
<div class="container text-start pt-3 pb-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Marketplace</p>
            <h1 class="h2 fw-bold mb-1">Tambah Produk</h1>
            <p class="text-muted mb-0">Lengkapi informasi produk agar tampil di marketplace.</p>
        </div>
        <a href="{{ route('dashboardMarketplace') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Periksa kembali data produk.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <section class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-1">Informasi produk</h2>
                        <p class="text-muted small mb-4">Data utama yang akan dilihat calon pembeli.</p>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="nama">Nama produk</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: M4 Custom AEG" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="harga">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga') }}" min="0" placeholder="2850000" required>
                                </div>
                                @error('harga')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="stok">Stok</label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok', 1) }}" min="0" required>
                                @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="merk">Merek</label>
                                <input type="text" class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk" value="{{ old('merk') }}" placeholder="Contoh: RCW" required>
                                @error('merk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="jenis">Jenis produk</label>
                                <select class="form-select @error('jenis') is-invalid @enderror" id="jenis" name="jenis" required>
                                    <option value="">Pilih jenis</option>
                                    @foreach (['Unit', 'Sparepart', 'Accessories', 'Perlengkapan'] as $jenis)
                                        <option value="{{ $jenis }}" @selected(old('jenis') === $jenis)>{{ $jenis }}</option>
                                    @endforeach
                                </select>
                                @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="kondisi">Kondisi</label>
                                <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                                    <option value="">Pilih kondisi</option>
                                    @foreach (['Baru', 'Sangat baik', 'Baik', 'Bekas'] as $kondisi)
                                        <option value="{{ $kondisi }}" @selected(old('kondisi') === $kondisi)>{{ $kondisi }}</option>
                                    @endforeach
                                </select>
                                @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="lokasi">Lokasi</label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Bandung" required>
                                @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="deskripsi">Deskripsi produk</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5" maxlength="1000" placeholder="Jelaskan kondisi, fitur, dan kelengkapan produk.">{{ old('deskripsi') }}</textarea>
                                <div class="form-text text-end"><span id="deskripsiCounter">{{ strlen(old('deskripsi', '')) }}</span>/1000 karakter</div>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-1">Foto produk</h2>
                        <p class="text-muted small mb-3">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                        <div class="ratio ratio-1x1 rounded bg-light border d-flex align-items-center justify-content-center overflow-hidden mb-3">
                            <img id="productPreview" src="{{ asset('img/balnkLogo.png') }}" alt="Pratinjau produk" class="img-fluid" style="object-fit: contain;">
                        </div>
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-primary" id="uploadBtn">Upload File</button>
                            <input type="file" class="d-none @error('gambar') is-invalid @enderror" id="gambar" name="gambar" accept="image/jpeg,image/png,image/webp">
                        </div>
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Tambah produk</button>
                            <a href="{{ route('dashboardMarketplace') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const productImageInput = document.getElementById('gambar');
    const uploadButton = document.getElementById('uploadBtn');
    const productPreview = document.getElementById('productPreview');
    const descriptionInput = document.getElementById('deskripsi');
    const descriptionCounter = document.getElementById('deskripsiCounter');

    uploadButton.addEventListener('click', function () {
        productImageInput.click();
    });

    productImageInput.addEventListener('change', function () {
        const [file] = this.files;
        if (file) {
            productPreview.src = URL.createObjectURL(file);
        }
    });

    descriptionInput.addEventListener('input', function () {
        descriptionCounter.textContent = this.value.length;
    });
</script>
@endpush
