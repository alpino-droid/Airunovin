@extends('layout/dashboard')

@section('title', 'Profil Club')

@section('content')
@php($user = Auth::user())
<div class="container-fluid px-3 px-lg-5 py-4">
   <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
      <div>
         <p class="text-uppercase text-primary fw-semibold small mb-1">{{ __('Dashboard Club') }}</p>
         <h1 class="h2 fw-bold mb-1">{{ __('Profile Club') }}</h1>
         <p class="text-muted mb-0">{{ __('Manage club information and community activity.') }}</p>
      </div>
      <a href="{{ route('club') }}" class="btn btn-primary">{{ __('View club') }}</a>
   </div>

   <div class="row g-4">
      <div class="col-12 col-xl-4">
         <section class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
               <div class="col-4">
                  <div class="mt-3">
                     {{-- TAMPILKAN LOGO CLUB DARI DATABASE --}}
                      <img src="{{ $club?->logo_url ?? asset('img/balnkLogo.png') }}"
                          class="img-thumbnail"
                          alt="Logo Club"
                          id="clubLogoPreview"
                          style="width: 100%; height: auto; object-fit: cover; border-radius: 8px;"
                         onerror="this.onerror=null; this.src='{{ asset('img/balnkLogo.png') }}'">
                  </div>

                  <div class="mb-3 mt-5 d-grid">
                     <label class="btn btn-outline-primary mb-0" for="logo">{{ __('Upload Logo') }}</label>
                     <input type="file" id="logo" name="logo" form="clubForm" class="d-none" accept="image/jpeg,image/png,image/jpg,image/gif">
                  </div>
               </div>
               <h2 class="h4 fw-bold mb-1">{{ Auth::user()->nama ?? 'Anda' }}</h2>
               <p class="text-muted mb-3">Komunitas dan ruang bertumbuh bersama</p>
               <span class="badge text-bg-success px-3 py-2">{{ __('Active') }}</span>
               <hr class="my-4">
               <div class="row text-center">
                  <div class="col-4 border-end"><strong class="d-block h5 mb-1">0</strong><small class="text-muted">Anggota</small></div>
                  <div class="col-4 border-end"><strong class="d-block h5 mb-1">0</strong><small class="text-muted">Event</small></div>
                  <div class="col-4"><strong class="d-block h5 mb-1">0</strong><small class="text-muted">Postingan</small></div>
               </div>
            </div>
         </section>
      </div>

      <div class="col-12 col-xl-8">
         <section class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
               <div class="d-flex justify-content-between align-items-center mb-4">
                  <div>
                     <h2 class="h5 fw-bold mb-1">{{ __('Club Information') }}</h2>
                     <p class="text-muted small mb-0">{{ __('Basic information displayed on the club page.') }}</p>
                  </div>
                  <span class="text-muted small">Profil publik</span>
               </div>
               <form id="clubForm" class="row g-3" action="{{ $club ? route('dashboardClub.update', $club->id) : route('dashboardClub.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @if($club)
                     @method('PUT')
                  @endif
                  <div class="col-md-6">
                     <label class="form-label fw-semibold" for="clubName">{{ __('Club Name') }}</label>
                     <input type="text" class="form-control" id="clubName" name="nama" value="{{ old('nama', $club->nama ?? '') }}" placeholder="Masukkan nama club" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label fw-semibold" for="organization">{{ __('Parent Organization') }}</label>
                     <input type="text" class="form-control" id="organization" name="induk_organisasi" value="{{ old('induk_organisasi', $club->induk_organisasi ?? '') }}" placeholder="Masukkan organisasi induk" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label fw-semibold" for="province">{{ __('Province') }}</label>
                     <select class="form-select" id="province" aria-label="Pilih provinsi" name="id_provinsi" required>
                        <option value="">Pilih provinsi</option>
                        @foreach($provinsi as $prov)
                           <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label fw-semibold" for="city">{{ __('City') }}</label>
                     <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $club->city ?? '') }}" placeholder="Masukkan kota" required>
                  </div>
                  <div class="col-12">
                     <label class="form-label fw-semibold" for="description">{{ __('Club Description') }}</label>
                     <textarea class="form-control" id="description" name="deskripsi" rows="3" placeholder="Ceritakan tentang club Anda" required>{{ old('deskripsi', $club->deskripsi ?? '') }}</textarea>
                  </div>
                  <div class="col-12">
                     <label class="form-label fw-semibold" for="registrationLink">Link pendaftaran anggota</label>
                     <input type="url" class="form-control" id="registrationLink" name="gform_link" value="{{ old('gform_link', $club->gform_link ?? '') }}" placeholder="https://forms.gle/...">
                  </div>
                  <div class="d-flex justify-content-end mt-4">
                     <button type="submit" class="btn btn-primary">{{ $club ? 'Simpan perubahan' : 'Buat club' }}</button>
                  </div>
               </form>
               
            </div>
         </section>

         <section class="card border-0 shadow-sm">
            <div class="card-body p-4">
               <div class="d-flex justify-content-between align-items-center mb-3">
                  <div>
                     <h2 class="h5 fw-bold mb-1">{{ __('Club Activity') }}</h2>
                     <p class="text-muted small mb-0">{{ __('Monitor the latest club agenda and activities.') }}</p>
                  </div>
                  <a href="{{ route('event') }}" class="btn btn-outline-primary btn-sm">{{ __('View Event') }}</a>
               </div>
               <div class="text-center py-4 border rounded bg-light">
                  <i class="fas fa-calendar-check text-muted mb-2" style="font-size: 32px;"></i>
                  <p class="fw-semibold mb-1">Belum ada aktivitas</p>
                  <p class="text-muted small mb-0">Event dan aktivitas club akan tampil di sini.</p>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>
@endsection