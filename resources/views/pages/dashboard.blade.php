@extends('layout/dashboard')

@section('title', 'Dashboard - Laravel Blade')

@section('content')
<div class="container text-start pt-3">
   <div class="mt-5 bg-light row">
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
            <input type="file" id="fileInput" class="d-none" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
         </div>
      </div>

      <div class="col-8">
         <div class="mt-3">
            @if(session('success'))
               <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>
            @endif
            <form id="profileForm" class="row g-3" method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
               @csrf
               @method('PUT')

               {{-- Hidden input untuk upload file --}}
               <input type="file" 
                      id="profile_picture" 
                      name="profile_picture" 
                      class="d-none" 
                      accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
               @error('profile_picture')
                  <div class="col-12">
                     <div class="alert alert-danger mb-0">{{ $message }}</div>
                  </div>
               @enderror

               <div class="col-md-6">
                  <label for="username" class="form-label">{{ __('Username') }}</label>
                  <input type="text" 
                         class="form-control @error('username') is-invalid @enderror" 
                         id="username" 
                         name="username" 
                         value="{{ old('username', $user->username) }}">
                  @error('username')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="col-md-6">
                  <label for="nama" class="form-label">{{ __('Full Name') }}</label>
                  <input type="text" 
                         class="form-control @error('nama') is-invalid @enderror" 
                         id="nama" 
                         name="nama" 
                         value="{{ old('nama', $user->nama) }}">
                  @error('nama')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="col-md-6">
                  <label for="email" class="form-label">{{ __('Email Address') }}</label>
                  <input type="email" 
                         class="form-control @error('email') is-invalid @enderror" 
                         id="email" 
                         name="email" 
                         value="{{ old('email', $user->email) }}">
                  @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="col-md-6">
                  <label for="phone" class="form-label">{{ __('WhatsApp Number') }}</label>
                  <input type="text" 
                         class="form-control @error('phone') is-invalid @enderror" 
                         id="phone" 
                         name="phone" 
                         value="{{ old('phone', $user->phone) }}" 
                         placeholder="Masukkan nomor whatsapp">
                  @error('phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="col-md-6">
                  <label for="id_provinsi" class="form-label">{{ __('Province') }}</label>
                  <select id="id_provinsi" name="id_provinsi" class="form-select @error('id_provinsi') is-invalid @enderror">
                     <option value="">Pilih Provinsi...</option>
                     @foreach($provinsi ?? [] as $prov)
                        <option value="{{ $prov->id }}" {{ (string) old('id_provinsi', $user->id_provinsi) === (string) $prov->id || old('id_provinsi', $user->id_provinsi) === $prov->provinsi || old('province', $user->province) === $prov->provinsi ? 'selected' : '' }}>
                           {{ $prov->provinsi }}
                        </option>
                     @endforeach
                  </select>
                  @error('id_provinsi')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="col-md-6">
                  <label for="city" class="form-label">{{ __('City') }}</label>
                  <input type="text" 
                         class="form-control @error('city') is-invalid @enderror" 
                         id="city" 
                         name="city" 
                         value="{{ old('city', $user->city) }}" 
                         placeholder="Masukkan kota">
                  @error('city')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="col-12">
                  <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<div class="container text-start pt-3 pb-5">
   <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
      <div>
         <p class="text-uppercase text-primary fw-semibold small mb-1">{{ __('Account Activity') }}</p>
         <h2 class="h3 fw-bold mb-1">{{ __('My Dashboard') }}</h2>
         <p class="text-muted mb-0">{{ __('Track your purchases and created events in one place.') }}</p>
      </div>
      <a href="{{ route('event.create') }}" class="btn btn-primary">{{ __('Create Event Button') }}</a>
   </div>

   <div class="row g-4">
      <div class="col-12 col-xl-7">
         <section class="card h-100">
            <div class="card-body p-4">
               <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                  <div>
                     <h3 class="h5 fw-bold mb-1">{{ __('Product Purchases') }}</h3>
                     <p class="text-muted small mb-0">{{ __('Your product purchase history.') }}</p>
                  </div>
                  <a href="{{ route('marketplace') }}" class="btn btn-outline-primary btn-sm">{{ __('View marketplace') }}</a>
               </div>

               <div class="d-flex flex-column gap-3">
                  @forelse($purchases ?? [] as $purchase)
                     <article class="border rounded p-3">
                        <div class="row g-3 align-items-center">
                           <div class="col-4 col-sm-3">
                              <div class="product-image-frame rounded border text-muted small text-center overflow-hidden" style="aspect-ratio: 1/1; max-height: 120px;">
                                 @php
                                    $pImg = $purchase->gambar_produk ?? $purchase->product?->gambar;
                                    $imgSrc = $pImg
                                       ? (str_starts_with($pImg, 'http') ? $pImg : asset('storage/' . $pImg))
                                       : asset('img/balnkLogo.png');
                                 @endphp
                                 <img src="{{ $imgSrc }}" alt="{{ $purchase->nama_produk }}" class="w-100 h-100 object-fit-cover" onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}'">
                              </div>
                           </div>
                           <div class="col-8 col-sm-9">
                              <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                                 <div>
                                    <strong class="d-block text-truncate" style="max-width: 250px;">
                                       @if($purchase->product)
                                          <a href="{{ route('isiMarketplace', $purchase->product->id) }}" class="text-decoration-none text-dark">{{ $purchase->nama_produk }}</a>
                                       @else
                                          {{ $purchase->nama_produk }}
                                       @endif
                                    </strong>
                                    <small class="text-muted">{{ $purchase->order_code }} · {{ $purchase->created_at?->translatedFormat('d F Y') ?? date('d F Y') }}</small>
                                 </div>
                                 <span class="badge {{ $purchase->status_badge_class }} align-self-start">{{ $purchase->status }}</span>
                              </div>
                              <div class="row g-2 small">
                                 <div class="col-12 col-md-5"><span class="text-muted d-block">Penjual</span><strong>{{ $purchase->nama_penjual ?: 'Penjual Airsoft' }}</strong></div>
                                 <div class="col-6 col-md-2"><span class="text-muted d-block">Jumlah</span><strong>{{ $purchase->jumlah }} item</strong></div>
                                 <div class="col-6 col-md-5"><span class="text-muted d-block">Total</span><strong>Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</strong></div>
                                 <div class="col-12 pt-2 border-top mt-2">
                                    <i class="fas fa-truck text-primary me-1"></i>
                                    <span class="text-muted">Pengiriman:</span> <strong>{{ $purchase->metode_pengiriman ?: 'Kurir reguler' }}</strong>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </article>
                  @empty
                     <div class="text-center text-muted border rounded p-4">
                        <p class="mb-2">Belum ada riwayat pembelian produk.</p>
                        <a href="{{ route('marketplace') }}" class="btn btn-sm btn-primary">Belanja di Marketplace</a>
                     </div>
                  @endforelse
               </div>
            </div>
         </section>
      </div>

      <div class="col-12 col-xl-5">
         <section class="card h-100">
            <div class="card-body p-4">
               <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                  <div>
                     <h3 class="h5 fw-bold mb-1">{{ __('Events created') }}</h3>
                     <p class="text-muted small mb-0">{{ __('Manage the events you publish.') }}</p>
                  </div>
                  <span class="badge bg-secondary">{{ $events->count() }} event</span>
               </div>

               <div class="d-flex flex-column gap-3">
                  @forelse($events as $event)
                     <article class="border rounded p-3">
                        <div class="row g-3 align-items-center">
                           <div class="col-4">
                              <div class="standard-poster-frame rounded border text-muted small text-center">
                                 @if($event->poster)
                                    <img src="{{ asset('storage/' . $event->poster[0]) }}" alt="Poster {{ $event->nama }}" class="w-100 h-100 object-fit-cover">
                                 @else
                                    <span>Foto event</span>
                                 @endif
                              </div>
                           </div>
                           <div class="col-8">
                              <div class="d-flex justify-content-between gap-2 mb-2">
                                 <strong>{{ $event->nama }}</strong>
                                 <span class="badge bg-success align-self-start">Aktif</span>
                              </div>
                              <small class="text-muted d-block mb-2"><i class="fas fa-calendar-alt me-1"></i>{{ $event->tanggal?->format('d F Y') }}</small>
                              <small class="text-muted d-block"><i class="fas fa-map-marker-alt me-1"></i>{{ $event->kota }}</small>
                              <div class="d-flex flex-wrap gap-2 mt-3">
                                 <a href="{{ route('isiEvent', $event) }}" class="btn btn-outline-primary btn-sm">Lihat</a>
                                 <a href="{{ route('event.edit', $event) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                 <form action="{{ route('event.destroy', $event) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </article>
                  @empty
                     <div class="text-center text-muted border rounded p-4">Belum ada event yang dibuat.</div>
                  @endforelse
               </div>
            </div>
         </section>
      </div>
   </div>
</div>

{{-- TAMPILKAN PESAN SUKSES --}}
@if (session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif

{{-- TAMPILKAN PESAN ERROR --}}
@if ($errors->any())
    <script>
        alert('{{ $errors->first() }}');
    </script>
@endif


@endsection