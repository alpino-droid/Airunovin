@extends('layout/dashboard')

@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="container text-start pt-3 pb-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Marketplace</p>
            <h1 class="h2 fw-bold mb-1">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}</h1>
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

    <form action="{{ isset($product) ? route('product.update', $product) : route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <section class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-1">Informasi produk</h2>
                        <p class="text-muted small mb-4">Data utama yang akan dilihat calon pembeli.</p>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="nama">Nama produk</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $product->nama ?? '') }}" placeholder="Contoh: M4 Custom AEG" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="id_marketplace">Marketplace</label>
                                <select class="form-select @error('id_marketplace') is-invalid @enderror" id="id_marketplace" name="id_marketplace" required>
                                    <option value="">Pilih marketplace</option>
                                    @foreach($marketplaces as $marketplace)
                                        <option value="{{ $marketplace->id }}" @selected((string) old('id_marketplace', $product->id_marketplace ?? '') === (string) $marketplace->id)>{{ $marketplace->nama }}</option>
                                    @endforeach
                                </select>
                                @error('id_marketplace')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @if($marketplaces->isEmpty())<div class="form-text text-danger">Buat marketplace terlebih dahulu sebelum menambahkan produk.</div>@endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="harga">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $product->harga ?? '') }}" min="0" placeholder="2850000" required>
                                </div>
                                @error('harga')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="stok">Stok</label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok', $product->stok ?? 1) }}" min="0" required>
                                @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="merk">Merek</label>
                                <input type="text" class="form-control @error('merk') is-invalid @enderror" id="merk" name="merk" value="{{ old('merk', $product->merk ?? '') }}" placeholder="Contoh: RCW" required>
                                @error('merk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="jenis">Jenis produk</label>
                                <select class="form-select @error('jenis') is-invalid @enderror" id="jenis" name="jenis" required>
                                    <option value="">Pilih jenis</option>
                                    @foreach (['Unit', 'Sparepart', 'Accessories', 'Perlengkapan'] as $jenis)
                                        <option value="{{ $jenis }}" @selected(old('jenis', $product->jenis ?? '') === $jenis)>{{ $jenis }}</option>
                                    @endforeach
                                </select>
                                @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="kondisi">Kondisi</label>
                                <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                                    <option value="">Pilih kondisi</option>
                                    @foreach (['Baru', 'Sangat baik', 'Baik', 'Bekas'] as $kondisi)
                                        <option value="{{ $kondisi }}" @selected(old('kondisi', $product->kondisi ?? '') === $kondisi)>{{ $kondisi }}</option>
                                    @endforeach
                                </select>
                                @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="lokasi">Lokasi</label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi', $product->lokasi ?? '') }}" placeholder="Contoh: Bandung" required>
                                @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="deskripsi">Deskripsi produk</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5" maxlength="1000" placeholder="Jelaskan kondisi, fitur, dan kelengkapan produk.">{{ old('deskripsi', $product->deskripsi ?? '') }}</textarea>
                                <div class="form-text text-end"><span id="deskripsiCounter">{{ strlen(old('deskripsi', $product->deskripsi ?? '')) }}</span>/1000 karakter</div>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <fieldset>
                                    <legend class="form-label fw-semibold mb-2">Metode pembayaran yang diterima</legend>
                                    <div class="row g-2">
                                        @foreach (['QRIS', 'DANA', 'GoPay', 'Transfer Bank', 'COD'] as $paymentMethod)
                                            <div class="col-12 col-sm-6">
                                                <div class="form-check border rounded p-2">
                                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="payment_methods[]" value="{{ $paymentMethod }}" id="payment{{ str_replace(' ', '', $paymentMethod) }}" @checked(in_array($paymentMethod, old('payment_methods', $product->payment_methods ?? []), true))>
                                                    <label class="form-check-label" for="payment{{ str_replace(' ', '', $paymentMethod) }}">{{ $paymentMethod }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('payment_methods')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    @error('payment_methods.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-1">Foto produk</h2>
                        <p class="text-muted small mb-3">Saran ukuran gambar: 800 x 800 px (rasio 1:1). Format JPG, PNG, atau WEBP, maksimal 2 MB.</p>
                        <div class="product-image-frame rounded border mb-3">
                            <img id="productPreview" src="{{ isset($product) && $product->gambar ? asset('storage/' . $product->gambar) : asset('img/balnkLogo.png') }}" alt="Pratinjau produk" class="img-fluid" style="object-fit: contain;">
                        </div>
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-primary" id="uploadBtn">Upload File</button>
                            <input type="file" class="d-none @error('gambar') is-invalid @enderror" id="gambar" name="gambar" accept="image/jpeg,image/png,image/webp">
                        </div>
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1" aria-hidden="true"></i>{{ isset($product) ? 'Simpan Perubahan' : 'Tambah produk' }}</button>
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
