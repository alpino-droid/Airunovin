@extends('layout/dashboard')

@section('title', 'Profil Marketplace')

@section('content')
@php($user = Auth::user())
   <div class="container text-start pt-3">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
         <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">{{ __('Dashboard Marketplace') }}</p>
            <h1 class="h2 fw-bold mb-1">{{ __('Profile Marketplace') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage your store identity and sales activity.') }}</p>
         </div>
         <a href="{{ route('marketplace') }}" class="btn btn-primary">{{ __('View marketplace') }}</a>
      </div>

      <div class="row g-4">
         <div class="col-12 col-xl-4">
            <section class="card border-0 shadow-sm h-100">
               <div class="card-body p-4">
                  <div class="col-4">
                     <div class="mt-3">
                        {{-- TAMPILKAN FOTO PROFIL DARI DATABASE --}}
                        <img src="{{ $user->profile_picture_url }}"
                             class="img-thumbnail"
                             alt="Profile Picture"
                             id="profileImage"
                             style="width: 100%; height: auto; object-fit: cover; border-radius: 8px;"
                             onerror="this.src='{{ asset('img/blankPhotoProfile.png') }}'">
                     </div>

                     <div class="mb-3 mt-5 d-grid">
                        <button class="btn btn-outline-primary" id="uploadBtn">{{ __('Upload File') }}</button>
                        <input type="file" id="fileInput" class="d-none" accept="image/*">
                     </div>
                  </div>
                  <h2 class="h4 fw-bold mb-1">Marketplace {{ Auth::user()->nama ?? 'Anda' }}</h2>
                  <p class="text-muted mb-3">Toko pribadi</p>
                  <span class="badge text-bg-success px-3 py-2">{{ __('Active') }}</span>
                  <hr class="my-4">
                  <div class="row text-center">
                     <div class="col-4 border-end"><strong class="d-block h5 mb-1">0</strong><small class="text-muted">Produk</small></div>
                     <div class="col-4 border-end"><strong class="d-block h5 mb-1">0</strong><small class="text-muted">Terjual</small></div>
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
                        <h2 class="h5 fw-bold mb-1">{{ __('Store Information') }}</h2>
                        <p class="text-muted small mb-0">{{ __('This information appears on the seller profile.') }}</p>
                     </div>
                     <span class="text-muted small">Profil publik</span>
                  </div>
                  <div class="row g-3">
                     <div class="col-md-6">
                        <label class="form-label fw-semibold" for="storeName">Nama toko</label>
                        <input type="text" class="form-control" id="storeName" value="Marketplace {{ Auth::user()->nama ?? 'Anda' }}">
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
                        <label class="form-label fw-semibold" for="phone">Nomor WhatsApp</label>
                        <input type="tel" class="form-control" id="phone" placeholder="Masukkan nomor WhatsApp">
                     </div>
                     <div class="col-12">
                        <label class="form-label fw-semibold" for="description">Deskripsi toko</label>
                        <textarea class="form-control" id="description" rows="3" placeholder="Ceritakan produk yang Anda jual"></textarea>
                     </div>
                  </div>
                  <div class="d-flex justify-content-end mt-4">
                     <button type="button" class="btn btn-primary">{{ __('Save Changes') }}</button>
                  </div>
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
                              <div class="ratio ratio-1x1 bg-light d-flex align-items-center justify-content-center">
                                 @if ($product->gambar)
                                    <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="img-fluid w-100 h-100" style="object-fit: contain;">
                                 @else
                                    <img src="{{ asset('img/balnkLogo.png') }}" alt="{{ $product->nama }}" class="img-fluid w-100 h-100" style="object-fit: contain;">
                                 @endif
                              </div>
                              <div class="p-3"><strong class="d-block text-truncate" title="{{ $product->nama }}">{{ $product->nama }}</strong><small class="text-muted">Rp{{ number_format($product->harga, 0, ',', '.') }}</small></div>
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

   <div class="mt-4">
      <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
         <div>
            <p class="text-uppercase text-primary fw-semibold small mb-1">Penjualan</p>
            <h2 class="h3 fw-bold mb-1">Produk terjual</h2>
            <p class="text-muted mb-0">Pantau pesanan dan pilihan pengiriman untuk setiap pembeli.</p>
         </div>
         <span class="badge bg-secondary px-3 py-2">3 pesanan aktif</span>
      </div>

      <div class="row g-3 mb-4">
         <div class="col-12 col-md-4">
            <div class="card h-100">
               <div class="card-body p-4">
                  <small class="text-muted d-block mb-2">Total produk terjual</small>
                  <strong class="display-6 fw-bold">12</strong>
                  <p class="text-muted small mb-0 mt-2">Produk terjual bulan ini.</p>
               </div>
            </div>
         </div>
         <div class="col-12 col-md-4">
            <div class="card h-100">
               <div class="card-body p-4">
                  <small class="text-muted d-block mb-2">Menunggu diproses</small>
                  <strong class="display-6 fw-bold">3</strong>
                  <p class="text-muted small mb-0 mt-2">Pesanan baru dari pembeli.</p>
               </div>
            </div>
         </div>
         <div class="col-12 col-md-4">
            <div class="card h-100">
               <div class="card-body p-4">
                  <small class="text-muted d-block mb-2">Pesanan selesai</small>
                  <strong class="display-6 fw-bold">9</strong>
                  <p class="text-muted small mb-0 mt-2">Pesanan yang telah diterima pembeli.</p>
               </div>
            </div>
         </div>
      </div>

      <div class="row g-4">
         <div class="col-12 col-xl-8">
            <section class="card h-100">
               <div class="card-body p-4">
                  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                     <div>
                        <h3 class="h5 fw-bold mb-1">Daftar pesanan</h3>
                        <p class="text-muted small mb-0">Produk yang berhasil terjual akan tampil di sini.</p>
                     </div>
                     <button type="button" class="btn btn-outline-primary btn-sm" disabled>Filter pesanan</button>
                  </div>

                  <div class="d-flex flex-column gap-3">
                     <article class="border rounded p-3">
                        <div class="row g-3 align-items-center">
                           <div class="col-3"><div class="ratio ratio-1x1 rounded bg-light d-flex align-items-center justify-content-center text-muted small text-center">Foto</div></div>
                           <div class="col-9">
                              <div class="d-flex flex-wrap justify-content-between gap-2 mb-3"><div><strong class="d-block">Airsoft Tactical Vest</strong><small class="text-muted">#ORD-1024 · 26 Agustus 2026</small></div><span class="badge bg-warning text-dark align-self-start">Menunggu diproses</span></div>
                              <div class="row g-2 small"><div class="col-12 col-md-5"><span class="text-muted d-block">Pembeli</span><strong>Raka Pratama</strong></div><div class="col-6 col-md-2"><span class="text-muted d-block">Jumlah</span><strong>1 item</strong></div><div class="col-6 col-md-5"><span class="text-muted d-block">Total</span><strong>Rp450.000</strong></div><div class="col-12 pt-2 border-top mt-2"><i class="fas fa-motorcycle text-primary me-1"></i><span class="text-muted">Metode:</span> <strong>Kurir lokal</strong></div></div>
                           </div>
                        </div>
                     </article>

                     <article class="border rounded p-3">
                        <div class="row g-3 align-items-center">
                           <div class="col-3"><div class="ratio ratio-1x1 rounded bg-light d-flex align-items-center justify-content-center text-muted small text-center">Foto</div></div>
                           <div class="col-9">
                              <div class="d-flex flex-wrap justify-content-between gap-2 mb-3"><div><strong class="d-block">Red Dot Sight 1x20</strong><small class="text-muted">#ORD-1023 · 25 Agustus 2026</small></div><span class="badge bg-info text-dark align-self-start">Siap diambil</span></div>
                              <div class="row g-2 small"><div class="col-12 col-md-5"><span class="text-muted d-block">Pembeli</span><strong>Dimas Saputra</strong></div><div class="col-6 col-md-2"><span class="text-muted d-block">Jumlah</span><strong>2 item</strong></div><div class="col-6 col-md-5"><span class="text-muted d-block">Total</span><strong>Rp700.000</strong></div><div class="col-12 pt-2 border-top mt-2"><i class="fas fa-store text-primary me-1"></i><span class="text-muted">Metode:</span> <strong>Ambil di lokasi penjual</strong></div></div>
                           </div>
                        </div>
                     </article>

                     <article class="border rounded p-3">
                        <div class="row g-3 align-items-center">
                           <div class="col-3"><div class="ratio ratio-1x1 rounded bg-light d-flex align-items-center justify-content-center text-muted small text-center">Foto</div></div>
                           <div class="col-9">
                              <div class="d-flex flex-wrap justify-content-between gap-2 mb-3"><div><strong class="d-block">BB Loader Magazine</strong><small class="text-muted">#ORD-1022 · 24 Agustus 2026</small></div><span class="badge bg-success align-self-start">Selesai</span></div>
                              <div class="row g-2 small"><div class="col-12 col-md-5"><span class="text-muted d-block">Pembeli</span><strong>Nadia Kusuma</strong></div><div class="col-6 col-md-2"><span class="text-muted d-block">Jumlah</span><strong>1 item</strong></div><div class="col-6 col-md-5"><span class="text-muted d-block">Total</span><strong>Rp125.000</strong></div><div class="col-12 pt-2 border-top mt-2"><i class="fas fa-motorcycle text-primary me-1"></i><span class="text-muted">Metode:</span> <strong>Kurir lokal</strong></div></div>
                           </div>
                        </div>
                     </article>
                  </div>
               </div>
            </section>
         </div>

         <div class="col-12 col-xl-4">
            <section class="card h-100">
               <div class="card-body p-4">
                  <h3 class="h5 fw-bold mb-1">Metode pemenuhan</h3>
                  <p class="text-muted small mb-4">Pilihan yang akan ditampilkan pada setiap pesanan.</p>

                  <div class="border rounded p-3 mb-3">
                     <div class="d-flex align-items-center gap-3">
                        <span class="rounded-circle bg-primary-subtle text-primary p-2"><i class="fas fa-motorcycle"></i></span>
                        <div>
                           <strong class="d-block">Kurir lokal</strong>
                           <small class="text-muted">Pesanan diantar ke alamat pembeli.</small>
                        </div>
                     </div>
                  </div>

                  <div class="border rounded p-3">
                     <div class="d-flex align-items-center gap-3">
                        <span class="rounded-circle bg-success-subtle text-success p-2"><i class="fas fa-store"></i></span>
                        <div>
                           <strong class="d-block">Ambil di lokasi penjual</strong>
                           <small class="text-muted">Pembeli mengambil pesanan langsung di lokasi toko.</small>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
         </div>
      </div>
   </div>
   </div>
   
            
@endsection

