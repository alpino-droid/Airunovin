@extends('layout/dashboard')

@section('title', 'Profil Marketplace')

@push('styles')
<style>
   @media (min-width: 1200px) {
      .sticky-sidebar-container {
         align-self: stretch;
      }
      .sticky-store-card {
         position: -webkit-sticky;
         position: sticky;
         top: 80px;
         z-index: 10;
         max-height: calc(100vh - 95px);
         overflow-y: auto;
         scrollbar-width: thin;
      }
   }
</style>
@endpush

@section('content')
   <div class="container text-start pt-3">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
         <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">{{ __('Dashboard Marketplace') }}</p>
            <h1 class="h2 fw-bold mb-1">{{ __('Profile Marketplace') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage your store identity and sales activity.') }}</p>
         </div>
         <a href="{{ route('marketplace') }}" class="btn btn-primary">{{ __('View marketplace') }}</a>
      </div>

      @if(session('success'))
         <div class="alert alert-success" role="alert">{{ session('success') }}</div>
      @endif

      <div class="row g-4">
         <div class="col-12 col-xl-4 sticky-sidebar-container">
            <section class="card border-0 shadow-sm sticky-store-card">
               <div class="card-body p-4">
                  <div class="col-4">
                     <div class="mt-3">
                        <img src="{{ $marketplace?->logo ? asset('storage/' . $marketplace->logo) : asset('img/balnkLogo.png') }}"
                             class="img-thumbnail"
                             alt="Logo Marketplace"
                             id="marketplaceLogoPreview"
                             style="width: 100%; height: auto; object-fit: cover; border-radius: 8px;"
                             onerror="this.src='{{ asset('img/blankPhotoProfile.png') }}'">
                     </div>

                     <div class="mb-3 mt-5 d-grid">
                        <label class="btn btn-outline-primary mb-0" for="logo">Upload Logo</label>
                        <input type="file" id="logo" name="logo" form="marketplaceCreateForm" class="d-none @error('logo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        @error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div class="form-text text-center">Format JPG, PNG, atau WEBP, maksimal 2 MB.</div>
                     </div>
                  </div>
                  <h2 class="h4 fw-bold mb-1">{{ $marketplace?->nama ?? 'Marketplace ' . (Auth::user()->nama ?? 'Anda') }}</h2>
                  <p class="text-muted mb-3">{{ $marketplace?->deskripsi ?: 'Toko pribadi' }}</p>
                     
                   <hr class="my-4">
                   <div class="row text-center">
                      <div class="col-4 border-end"><strong class="d-block h5 mb-1">{{ $products->count() }}</strong><small class="text-muted">Produk</small></div>
                      <div class="col-4 border-end"><strong class="d-block h5 mb-1">{{ $totalItemsSold ?? 0 }}</strong><small class="text-muted">Terjual</small></div>
                      <div class="col-4"><strong class="d-block h5 mb-1">0</strong><small class="text-muted">Ulasan</small></div>
                   </div> 
               </div>
            </section>
         </div>

         <div class="col-12 col-xl-8">
            <section class="card border-0 shadow-sm mb-4">
               <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                     <div>
                        <h2 class="h5 fw-bold mb-1">{{ $marketplace ? 'Informasi Marketplace' : 'Buat Marketplace' }}</h2>
                        <p class="text-muted small mb-0">{{ $marketplace ? 'Perbarui informasi marketplace Anda.' : 'Informasi ini akan tampil sebagai profil marketplace Anda.' }}</p>
                     </div>
                     <span class="text-muted small">Profil publik</span>
                  </div>

                  <form id="marketplaceCreateForm" class="row g-3" action="{{ $marketplace ? route('marketplace.update', $marketplace) : route('marketplace.store') }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     @if($marketplace)
                        @method('PUT')
                     @endif
                     <div class="col-md-6">
                           <label class="form-label fw-semibold" for="storeName">Nama toko</label>
                           <input type="text" class="form-control @error('nama') is-invalid @enderror" id="storeName" name="nama" value="{{ old('nama', $marketplace->nama ?? 'Marketplace ' . (Auth::user()->nama ?? 'Anda')) }}" placeholder="Contoh: Airsoft Gear Store" required>
                           @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                           <label class="form-label fw-semibold" for="ownerName">Pemilik</label>
                           <input type="text" class="form-control" id="ownerName" value="{{ Auth::user()->nama ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6">
                           <label class="form-label fw-semibold" for="email">Email</label>
                           <input type="email" class="form-control" id="email" value="{{ Auth::user()->email ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6">
                           <label class="form-label fw-semibold" for="status">Status marketplace</label>
                           <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                              <option value="panding" @selected(old('status', $marketplace->status ?? 'panding') === 'panding')>Pending</option>
                              <option value="diterima" @selected(in_array(old('status', $marketplace->status ?? 'panding'), ['diterima', 'terimakasih']))>Diterima</option>
                              <option value="tolak" @selected(old('status', $marketplace->status ?? 'panding') === 'tolak')>Ditolak</option>
                           </select>
                           @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                           <label class="form-label fw-semibold" for="description">Deskripsi toko</label>
                           <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="description" name="deskripsi" rows="3" maxlength="1000" placeholder="Ceritakan produk yang Anda jual">{{ old('deskripsi', $marketplace->deskripsi ?? '') }}</textarea>
                           @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                           <label class="form-label fw-semibold" for="phone">Nomor WhatsApp</label>
                           <input type="tel" class="form-control" id="phone" value="{{ Auth::user()->phone ?? '-' }}" readonly>
                           <div class="form-text">Diambil dari profil akun.</div>
                        </div>
                        <div class="col-md-8">
                           <label class="form-label fw-semibold" for="alamat">Alamat Toko / Penjual</label>
                           <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap toko atau penjual">{{ old('alamat', Auth::user()->alamat ?? '') }}</textarea>
                           @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                           <div class="form-text">Alamat operasional toko (tersimpan ke profil pengguna).</div>
                        </div>
                     <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1" aria-hidden="true"></i>{{ $marketplace ? 'Simpan Perubahan' : 'Buat Marketplace' }}</button>
                     </div>
                  </form>
               </div>
            </section>

            <section class="card border-0 shadow-sm">
               <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                     <div>
                        <h2 class="h5 fw-bold mb-1">{{ __('Your Products') }}</h2>
                        <p class="text-muted small mb-0">{{ __('List of products displayed in the marketplace.') }}</p>
                     </div>
                     <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Tambah produk</a>
                  </div>
                  <div class="row g-3">
                     @forelse ($products as $product)
                        <div class="col-6 col-md-4">
                           <div class="border rounded overflow-hidden h-100">
                              <div class="product-image-frame rounded-0">
                                 @if ($product->gambar)
                                    <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="img-fluid w-100 h-100" style="object-fit: contain;">
                                 @else
                                    <img src="{{ asset('img/balnkLogo.png') }}" alt="{{ $product->nama }}" class="img-fluid w-100 h-100" style="object-fit: contain;">
                                 @endif
                              </div>
                              <div class="p-3">
                                 <strong class="d-block text-truncate" title="{{ $product->nama }}">{{ $product->nama }}</strong>
                                 <small class="text-muted d-block">Rp{{ number_format($product->harga, 0, ',', '.') }}</small>
                                 <small class="text-muted d-block text-truncate">{{ $product->marketplace?->nama ?? 'Tanpa marketplace' }}</small>
                                 <div class="d-flex gap-2 mt-2">
                                    <a href="{{ route('product.edit', $product) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('product.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?');">
                                       @csrf
                                       @method('DELETE')
                                       <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                 </div>
                              </div>
                           </div>
                        </div>
                     @empty
                        <div class="col-12"><p class="text-muted mb-0">Belum ada produk. Tambahkan produk pertama Anda.</p></div>
                     @endforelse
                  </div>
               </div>
            </section>
         </div>
      </div>

   <div class="mt-5">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
         <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Penjualan Toko</p>
            <h2 class="h3 fw-bold mb-1">Produk Terjual & Pesanan Masuk</h2>
            <p class="text-muted mb-0">Pantau produk yang laku terjual, proses pesanan pembeli, dan kelola status pengiriman.</p>
         </div>
         <div class="d-flex gap-2">
            <span class="badge bg-primary px-3 py-2 fs-6 fw-normal">{{ $orders->count() }} Total Pesanan</span>
            @if($pendingOrdersCount > 0)
               <span class="badge bg-warning text-dark px-3 py-2 fs-6 fw-normal">{{ $pendingOrdersCount }} Menunggu Diproses</span>
            @endif
         </div>
      </div>

      {{-- STATISTIK PENJUALAN --}}
      <div class="row g-3 mb-4">
         <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
               <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                     <small class="text-muted fw-semibold">Total Produk Terjual</small>
                     <span class="badge bg-primary-subtle text-primary p-2 rounded-circle"><i class="bi bi-box-seam fs-5"></i></span>
                  </div>
                  <div class="d-flex align-items-baseline gap-2">
                     <strong class="display-6 fw-bold text-dark">{{ $totalItemsSold ?? 0 }}</strong>
                     <span class="text-muted">unit</span>
                  </div>
                  <p class="text-muted small mb-0 mt-2">Dari {{ $soldProducts->count() }} macam produk yang berhasil terjual.</p>
               </div>
            </div>
         </div>
         <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
               <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                     <small class="text-muted fw-semibold">Menunggu Diproses</small>
                     <span class="badge bg-warning-subtle text-warning p-2 rounded-circle"><i class="bi bi-hourglass-split fs-5"></i></span>
                  </div>
                  <div class="d-flex align-items-baseline gap-2">
                     <strong class="display-6 fw-bold text-warning">{{ $pendingOrdersCount ?? 0 }}</strong>
                     <span class="text-muted">pesanan</span>
                  </div>
                  <p class="text-muted small mb-0 mt-2">Pesanan baru yang perlu Anda konfirmasi dan proses.</p>
               </div>
            </div>
         </div>
         <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
               <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                     <small class="text-muted fw-semibold">Pesanan Selesai</small>
                     <span class="badge bg-success-subtle text-success p-2 rounded-circle"><i class="bi bi-check2-circle fs-5"></i></span>
                  </div>
                  <div class="d-flex align-items-baseline gap-2">
                     <strong class="display-6 fw-bold text-success">{{ $completedOrdersCount ?? 0 }}</strong>
                     <span class="text-muted">pesanan</span>
                  </div>
                  <p class="text-muted small mb-0 mt-2">Pesanan yang telah tuntas diterima oleh pembeli.</p>
               </div>
            </div>
         </div>
      </div>

      <div class="row g-4">
         {{-- KOLOM KIRI: DAFTAR PESANAN MASUK (CARD DAFTAR PESANAN) --}}
         <div class="col-12 col-xl-8">
            <section class="card border-0 shadow-sm h-100">
               <div class="card-body p-4">
                  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                     <div>
                        <h3 class="h5 fw-bold mb-1">Daftar Pesanan</h3>
                        <p class="text-muted small mb-0">Rincian pesanan pembeli untuk produk di marketplace Anda.</p>
                     </div>
                     <div class="btn-group btn-group-sm" role="group" id="orderFilterGroup">
                        <button type="button" class="btn btn-outline-primary active" data-filter="all">Semua ({{ $orders->count() }})</button>
                        <button type="button" class="btn btn-outline-primary" data-filter="menunggu diproses">Diproses ({{ $pendingOrdersCount }})</button>
                        <button type="button" class="btn btn-outline-primary" data-filter="selesai">Selesai ({{ $completedOrdersCount }})</button>
                     </div>
                  </div>

                  <div class="d-flex flex-column gap-3" id="ordersListContainer">
                     @forelse ($orders as $order)
                        @php
                           $statusClass = match (strtolower(trim($order->status ?? ''))) {
                              'selesai', 'success' => 'bg-success',
                              'menunggu diproses', 'diproses', 'proses', 'pending' => 'bg-warning text-dark',
                              'siap diambil', 'dikirim' => 'bg-info text-dark',
                              'dibatalkan', 'batal' => 'bg-danger',
                              default => 'bg-secondary',
                           };

                           $pImg = $order->gambar_produk ?? $order->product?->gambar;
                           $imgSrc = $pImg
                              ? (str_starts_with($pImg, 'http') ? $pImg : asset('storage/' . $pImg))
                              : asset('img/balnkLogo.png');

                           $buyerPhoneClean = preg_replace('/[^0-9]/', '', $order->no_wa_pembeli ?? '');
                           if ($buyerPhoneClean && str_starts_with($buyerPhoneClean, '0')) {
                              $buyerPhoneClean = '62' . substr($buyerPhoneClean, 1);
                           }
                        @endphp
                        <article class="border rounded p-3 order-card" data-status="{{ strtolower(trim($order->status ?? '')) }}">
                           <div class="row g-3 align-items-center">
                              <div class="col-4 col-sm-3 col-md-2">
                                 <div class="product-image-frame rounded border bg-light text-muted small text-center overflow-hidden" style="aspect-ratio: 1/1; max-height: 100px;">
                                    <img src="{{ $imgSrc }}" alt="{{ $order->nama_produk }}" class="w-100 h-100 object-fit-cover" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                                 </div>
                              </div>
                              <div class="col-8 col-sm-9 col-md-10">
                                 <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                    <div>
                                       <strong class="d-block text-truncate" style="max-width: 280px;" title="{{ $order->nama_produk }}">
                                          @if($order->product)
                                             <a href="{{ route('isiMarketplace', $order->product->id) }}" class="text-decoration-none text-dark">{{ $order->nama_produk }}</a>
                                          @else
                                             {{ $order->nama_produk }}
                                          @endif
                                       </strong>
                                       <small class="text-muted">{{ $order->order_code }} &bull; {{ $order->created_at?->translatedFormat('d F Y, H:i') ?? date('d F Y') }}</small>
                                    </div>
                                    <span class="badge {{ $statusClass }} align-self-start">{{ $order->status }}</span>
                                 </div>

                                 <div class="row g-2 small mb-2">
                                    <div class="col-12 col-md-4">
                                       <span class="text-muted d-block">Pembeli</span>
                                       <strong>{{ $order->nama_pembeli }}</strong>
                                       @if($buyerPhoneClean)
                                          <a href="https://wa.me/{{ $buyerPhoneClean }}?text={{ urlencode('Halo ' . $order->nama_pembeli . ', terkait pesanan ' . $order->order_code . ' (' . $order->nama_produk . ') di marketplace...') }}" target="_blank" class="d-block text-success text-decoration-none" title="Hubungi pembeli via WhatsApp">
                                             <i class="bi bi-whatsapp me-1"></i>{{ $order->no_wa_pembeli }}
                                          </a>
                                       @else
                                          <span class="text-muted d-block">{{ $order->no_wa_pembeli }}</span>
                                       @endif
                                       @if($order->user?->alamat)
                                          <div class="text-muted mt-1" style="font-size: 0.8rem;" title="Alamat Pengiriman Pembeli">
                                             <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $order->user->alamat }}
                                          </div>
                                       @endif
                                    </div>
                                    <div class="col-6 col-md-2">
                                       <span class="text-muted d-block">Jumlah</span>
                                       <strong>{{ $order->jumlah }} unit</strong>
                                    </div>
                                    <div class="col-6 col-md-3">
                                       <span class="text-muted d-block">Harga Satuan</span>
                                       <span>Rp {{ number_format($order->harga_satuan, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-12 col-md-3">
                                       <span class="text-muted d-block">Total Tagihan</span>
                                       <strong class="text-success fs-6">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                                    </div>
                                 </div>

                                 <div class="d-flex flex-wrap justify-content-between align-items-center pt-2 border-top gap-2">
                                    <div class="small text-muted">
                                       <span class="me-3">
                                          <i class="fas fa-truck text-primary me-1"></i>
                                          <strong>{{ $order->metode_pengiriman ?: 'Ambil Sendiri' }}</strong>
                                       </span>
                                       <span>
                                          <i class="bi bi-credit-card text-secondary me-1"></i>
                                          {{ $order->metode_pembayaran ?: 'Transfer Bank' }}
                                       </span>
                                    </div>

                                    {{-- AKSI STATUS PESANAN UNTUK PENJUAL --}}
                                    <div class="d-flex align-items-center gap-2">
                                       @if(!in_array(strtolower(trim($order->status)), ['selesai', 'success']))
                                          <form method="POST" action="{{ route('marketplace.order.status', $order->id) }}" class="d-inline">
                                             @csrf
                                             @method('PATCH')
                                             <input type="hidden" name="status" value="Selesai">
                                             <button type="submit" class="btn btn-success btn-sm d-flex align-items-center gap-1" onclick="return confirm('Tandai pesanan {{ $order->order_code }} sebagai Selesai?');">
                                                <i class="bi bi-check2-circle"></i> Selesaikan Pesanan
                                             </button>
                                          </form>
                                       @else
                                          <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                                             <i class="bi bi-check-circle-fill me-1"></i>Pesanan Selesai
                                          </span>
                                       @endif
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </article>
                     @empty
                        <div class="text-center text-muted border rounded p-4">
                           <i class="bi bi-inbox fs-1 text-secondary d-block mb-2"></i>
                           <p class="mb-1 fw-semibold">Belum ada pesanan masuk.</p>
                           <small class="text-muted">Ketika pembeli memesan produk toko Anda, transaksi akan otomatis tampil di sini.</small>
                        </div>
                     @endforelse
                  </div>
               </div>
            </section>
         </div>

         {{-- KOLOM KANAN: RINGKASAN PRODUK APA SAJA YANG TERJUAL & METODE PEMENUHAN --}}
         <div class="col-12 col-xl-4 d-flex flex-column gap-4">
            {{-- PRODUK APA SAJA YANG TERJUAL --}}
            <section class="card border-0 shadow-sm">
               <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                     <div>
                        <h3 class="h5 fw-bold mb-1">Produk yang Terjual</h3>
                        <p class="text-muted small mb-0">Rincian produk toko yang telah dibeli.</p>
                     </div>
                     <span class="badge bg-primary-subtle text-primary">{{ $soldProducts->count() }} Produk</span>
                  </div>

                  <div class="d-flex flex-column gap-3">
                     @forelse ($soldProducts as $sp)
                        @php
                           $spImg = $sp->gambar;
                           $spImgSrc = $spImg
                              ? (str_starts_with($spImg, 'http') ? $spImg : asset('storage/' . $spImg))
                              : asset('img/balnkLogo.png');
                        @endphp
                        <div class="d-flex align-items-center gap-3 p-2 rounded border bg-light">
                           <div class="rounded border overflow-hidden bg-white flex-shrink-0" style="width: 52px; height: 52px;">
                              <img src="{{ $spImgSrc }}" alt="{{ $sp->nama }}" class="w-100 h-100 object-fit-cover" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}';">
                           </div>
                           <div class="flex-grow-1 min-w-0">
                              <strong class="d-block text-truncate small text-dark" title="{{ $sp->nama }}">
                                 @if($sp->id)
                                    <a href="{{ route('isiMarketplace', $sp->id) }}" class="text-decoration-none text-dark">{{ $sp->nama }}</a>
                                 @else
                                    {{ $sp->nama }}
                                 @endif
                              </strong>
                              <div class="d-flex justify-content-between align-items-center mt-1">
                                 <span class="badge bg-primary text-white small">{{ $sp->total_qty }} unit terjual</span>
                                 <span class="text-success small fw-semibold">Rp {{ number_format($sp->total_revenue, 0, ',', '.') }}</span>
                              </div>
                              @if($sp->product)
                                 <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Sisa stok: {{ $sp->product->stok }} unit</small>
                              @endif
                           </div>
                        </div>
                     @empty
                        <div class="text-center text-muted py-4 small">
                           <i class="bi bi-box-seam fs-3 text-secondary d-block mb-2"></i>
                           Belum ada produk yang terjual saat ini.
                        </div>
                     @endforelse
                  </div>
               </div>
            </section>

            {{-- METODE PEMENUHAN --}}
            <section class="card border-0 shadow-sm">
               <div class="card-body p-4">
                  <h3 class="h5 fw-bold mb-1">Metode Pemenuhan</h3>
                  <p class="text-muted small mb-3">Pilihan pengiriman yang didukung toko Anda.</p>

                  <div class="border rounded p-3 mb-3 bg-light">
                     <div class="d-flex align-items-center gap-3">
                        <span class="rounded-circle bg-primary-subtle text-primary p-2 fs-5"><i class="fas fa-truck"></i></span>
                        <div>
                           <strong class="d-block small">Kurir Reguler / Pengiriman</strong>
                           <small class="text-muted">Pesanan dikirimkan melalui jasa ekspedisi atau kurir ke alamat pembeli.</small>
                        </div>
                     </div>
                  </div>

                  <div class="border rounded p-3 bg-light">
                     <div class="d-flex align-items-center gap-3">
                        <span class="rounded-circle bg-success-subtle text-success p-2 fs-5"><i class="fas fa-store"></i></span>
                        <div>
                           <strong class="d-block small">Ambil di Lokasi (COD)</strong>
                           <small class="text-muted">Pembeli mengambil pesanan secara langsung di lokasi fisik toko penjual.</small>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
         </div>
      </div>
   </div>
   </div>

   @push('scripts')
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const filterBtns = document.querySelectorAll('#orderFilterGroup [data-filter]');
         const orderCards = document.querySelectorAll('.order-card');

         filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
               filterBtns.forEach(b => b.classList.remove('active'));
               this.classList.add('active');

               const filter = this.getAttribute('data-filter');

               orderCards.forEach(card => {
                  const status = card.getAttribute('data-status');
                  if (filter === 'all') {
                     card.classList.remove('d-none');
                  } else if (filter === 'menunggu diproses') {
                     if (['menunggu diproses', 'diproses', 'proses', 'pending'].includes(status)) {
                        card.classList.remove('d-none');
                     } else {
                        card.classList.add('d-none');
                     }
                  } else if (filter === 'selesai') {
                     if (['selesai', 'success'].includes(status)) {
                        card.classList.remove('d-none');
                     } else {
                        card.classList.add('d-none');
                     }
                  }
               });
            });
         });
      });
   </script>
   @endpush
@endsection
