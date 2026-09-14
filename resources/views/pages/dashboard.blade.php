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
            <input type="file" id="fileInput" class="d-none" accept="image/*">
         </div>
      </div>

      <div class="col-8">
         <div class="mt-3">
            <form id="profileForm" class="row g-3" method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
               @csrf
               @method('PUT')

               {{-- Hidden input untuk upload file --}}
               <input type="file" 
                      id="profile_picture" 
                      name="profile_picture" 
                      class="d-none" 
                      accept="image/*">

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
                  <label for="province" class="form-label">{{ __('Province') }}</label>
                  <select id="province" name="province" class="form-select @error('province') is-invalid @enderror">
                     <option value="">Pilih Provinsi...</option>
                     <option value="Aceh" {{ old('province', $user->province) == 'Aceh' ? 'selected' : '' }}>Aceh</option>
                     <option value="Bali" {{ old('province', $user->province) == 'Bali' ? 'selected' : '' }}>Bali</option>
                     <option value="Banten" {{ old('province', $user->province) == 'Banten' ? 'selected' : '' }}>Banten</option>
                     <option value="Bengkulu" {{ old('province', $user->province) == 'Bengkulu' ? 'selected' : '' }}>Bengkulu</option>
                     <option value="DKI Jakarta" {{ old('province', $user->province) == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                     <option value="Jambi" {{ old('province', $user->province) == 'Jambi' ? 'selected' : '' }}>Jambi</option>
                     <option value="Jawa Barat" {{ old('province', $user->province) == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                     <option value="Jawa Tengah" {{ old('province', $user->province) == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                     <option value="Jawa Timur" {{ old('province', $user->province) == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                     <option value="Kalimantan Barat" {{ old('province', $user->province) == 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                     <option value="Kalimantan Selatan" {{ old('province', $user->province) == 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                     <option value="Kalimantan Tengah" {{ old('province', $user->province) == 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                     <option value="Kalimantan Timur" {{ old('province', $user->province) == 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                     <option value="Kalimantan Utara" {{ old('province', $user->province) == 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                     <option value="Kepulauan Bangka Belitung" {{ old('province', $user->province) == 'Kepulauan Bangka Belitung' ? 'selected' : '' }}>Kepulauan Bangka Belitung</option>
                     <option value="Kepulauan Riau" {{ old('province', $user->province) == 'Kepulauan Riau' ? 'selected' : '' }}>Kepulauan Riau</option>
                     <option value="Lampung" {{ old('province', $user->province) == 'Lampung' ? 'selected' : '' }}>Lampung</option>
                     <option value="Maluku" {{ old('province', $user->province) == 'Maluku' ? 'selected' : '' }}>Maluku</option>
                     <option value="Maluku Utara" {{ old('province', $user->province) == 'Maluku Utara' ? 'selected' : '' }}>Maluku Utara</option>
                     <option value="Nusa Tenggara Barat" {{ old('province', $user->province) == 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                     <option value="Nusa Tenggara Timur" {{ old('province', $user->province) == 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>
                     <option value="Papua" {{ old('province', $user->province) == 'Papua' ? 'selected' : '' }}>Papua</option>
                     <option value="Papua Barat" {{ old('province', $user->province) == 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                     <option value="Riau" {{ old('province', $user->province) == 'Riau' ? 'selected' : '' }}>Riau</option>
                     <option value="Sulawesi Barat" {{ old('province', $user->province) == 'Sulawesi Barat' ? 'selected' : '' }}>Sulawesi Barat</option>
                     <option value="Sulawesi Selatan" {{ old('province', $user->province) == 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                     <option value="Sulawesi Tengah" {{ old('province', $user->province) == 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                     <option value="Sulawesi Tenggara" {{ old('province', $user->province) == 'Sulawesi Tenggara' ? 'selected' : '' }}>Sulawesi Tenggara</option>
                     <option value="Sulawesi Utara" {{ old('province', $user->province) == 'Sulawesi Utara' ? 'selected' : '' }}>Sulawesi Utara</option>
                     <option value="Sumatera Barat" {{ old('province', $user->province) == 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                     <option value="Sumatera Selatan" {{ old('province', $user->province) == 'Sumatera Selatan' ? 'selected' : '' }}>Sumatera Selatan</option>
                     <option value="Sumatera Utara" {{ old('province', $user->province) == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                     <option value="Yogyakarta" {{ old('province', $user->province) == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                  </select>
                  @error('province')
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
                  <article class="border rounded p-3">
                     <div class="row g-3 align-items-center">
                        <div class="col-4 col-sm-3">
                           <div class="ratio ratio-1x1 rounded bg-light border d-flex align-items-center justify-content-center text-muted small text-center"><img src="{{ asset('img/imgStatik/GambarProduk/G.S.P1.jpg') }}" alt=""></div>
                        </div>
                        <div class="col-8 col-sm-9">
                           <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                              <div><strong class="d-block">Airsoft Tactical Vest</strong><small class="text-muted">#BELI-2048 · 26 Agustus 2026</small></div>
                              <span class="badge bg-success align-self-start">Selesai</span>
                           </div>
                           <div class="row g-2 small">
                              <div class="col-12 col-md-5"><span class="text-muted d-block">Penjual</span><strong>Gear Tactical Store</strong></div>
                              <div class="col-6 col-md-2"><span class="text-muted d-block">Jumlah</span><strong>1 item</strong></div>
                              <div class="col-6 col-md-5"><span class="text-muted d-block">Total</span><strong>Rp450.000</strong></div>
                              <div class="col-12 pt-2 border-top mt-2"><i class="fas fa-motorcycle text-primary me-1"></i><span class="text-muted">Pengiriman:</span> <strong>Kurir lokal</strong></div>
                           </div>
                        </div>
                     </div>
                  </article>  
   
                  <article class="border rounded p-3">
                     <div class="row g-3 align-items-center">
                        <div class="col-4 col-sm-3">
                           <div class="ratio ratio-1x1 rounded bg-light border d-flex align-items-center justify-content-center text-muted small text-center"><img src="{{ asset('img/imgStatik/GambarProduk/G.S.P6.jpg') }}" alt=""></div>
                        </div>
                        <div class="col-8 col-sm-9">
                           <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                              <div><strong class="d-block">Maple Leaf Hop Up Chamber</strong><small class="text-muted">#BELI-2047 · 24 Agustus 2026</small></div>
                              <span class="badge bg-info text-dark align-self-start">Siap diambil</span>
                           </div>
                           <div class="row g-2 small">
                              <div class="col-12 col-md-5"><span class="text-muted d-block">Penjual</span><strong>Surabaya Airsoft Hub</strong></div>
                              <div class="col-6 col-md-2"><span class="text-muted d-block">Jumlah</span><strong>1 item</strong></div>
                              <div class="col-6 col-md-5"><span class="text-muted d-block">Total</span><strong>Rp350.000</strong></div>
                              <div class="col-12 pt-2 border-top mt-2"><i class="fas fa-store text-primary me-1"></i><span class="text-muted">Pengambilan:</span> <strong>Lokasi penjual</strong></div>
                           </div>
                        </div>
                     </div>
                  </article>
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
                              <div class="ratio ratio-1x1 rounded bg-light border d-flex align-items-center justify-content-center text-muted small text-center overflow-hidden">
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