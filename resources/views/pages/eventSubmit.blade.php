@extends('layout/dashboard')

@section('title', 'Home - Laravel Blade')

@section('content')
@php
    $maxPoster = \App\Models\event::MAX_POSTERS;
   $isEdit = isset($event);
   $formAction = $isEdit ? route('event.update', $event) : route('event.store');
   $existingPosters = $isEdit ? $event->poster : [];
@endphp

<div class="container text-start pt-3">
   <div class="mt-5 bg-light row p-3">
      <div class="col-md-12 mt-3" style="overflow: visible;">
         <form class="row g-3" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" style="overflow: visible;">
            @csrf
            @if($isEdit)
               @method('PUT')
            @endif

            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
               <h4 class="mb-0">{{ $isEdit ? 'Edit Event' : 'Poster Event' }}</h4>
               <div class="text-end">
                  <span class="badge bg-light text-dark border">Maksimal {{ $maxPoster }} poster</span>
                  <div class="form-text">Saran ukuran poster: 800 x 1200 px (rasio 2:3). Format JPG, PNG, WEBP, atau GIF, maksimal 2 MB per file.</div>
               </div>
            </div>

            <div id="posterCardContainer" class="col-12 d-flex flex-wrap">
               @forelse($existingPosters as $index => $poster)
               <div class="col-md-3 mt-2 mb-3 card-item" data-card-id="{{ $index + 1 }}">
                  <div class="card" style="width: 18rem;">
                     <div class="poster-upload-frame">
                        <img src="{{ asset('storage/' . $poster) }}" alt="Poster event">
                     </div>
                     <div class="card-body">
                        <button type="button" class="btn btn-outline-primary btn-upload" data-id="{{ $index + 1 }}">Ganti File</button>
                        <input type="file" id="fileInputButton{{ $index + 1 }}" name="poster[]" class="d-none poster-input" accept="image/*">
                        <button type="button" class="btn btn-outline-danger btn-sm mt-2 btn-remove" data-id="{{ $index + 1 }}">Hapus</button>
                     </div>
                  </div>
               </div>
               @empty
               <div class="col-md-3 mt-2 mb-3 card-item" data-card-id="1">
                  <div class="card" style="width: 18rem;">
                     <div class="poster-upload-frame">
                        <img src="{{ asset('img/balnkLogo.png') }}" alt="Gambar 1">
                     </div>
                     <div class="card-body">
                        <button type="button" class="btn btn-outline-primary btn-upload" data-id="1">Upload File</button>
                        <input type="file" id="fileInputButton1" name="poster[]" class="d-none poster-input" accept="image/*">
                        <button type="button" class="btn btn-outline-danger btn-sm mt-2 btn-remove" data-id="1">Hapus</button>
                     </div>
                  </div>
               </div>
               @endforelse

               <div class="col-md-3 mt-2 mb-3">
                  <button type="button" id="addPosterBtn" class="btn-outline-primary d-flex flex-column align-items-center justify-content-center poster-upload-frame" style="border: 2px dashed #0d6efd; border-radius: 8px; background: transparent;">
                     <img src="{{ asset('icon/basil--add-outline.png') }}" alt="Tambah" style="width: 50px; height: 50px;">
                  </button>
               </div>
            </div>

            <input type="hidden" name="card_data" id="cardData">

            <div class="col-md-6">
               <label for="nama" class="form-label">Nama Event</label>
               <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $event->nama ?? '') }}" placeholder="Masukkan nama event" required>
            </div>

            <div class="col-md-6">
               <label for="tanggal" class="form-label">Tanggal</label>
               <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', $event->tanggal ?? '') }}" required>
            </div>

            <div class="col-md-6">
               <label for="penyelenggara" class="form-label">Penyelenggara</label>
               <input type="text" class="form-control" id="penyelenggara" name="penyelenggara" value="{{ old('penyelenggara', $event->penyelenggara ?? '') }}" placeholder="Masukkan nama penyelenggara" required>
            </div>

            <div class="col-md-6">
               <label for="lokasi" class="form-label">Lokasi</label>
               <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ old('lokasi', $event->lokasi ?? '') }}" placeholder="Masukkan lokasi event">
            </div>

            <div class="col-md-6" style="overflow: visible;">
               <label for="id_provinsi" class="form-label">Provinsi</label>
               <select id="id_provinsi" class="form-select" name="id_provinsi" required style="position: relative; z-index: 1050;">
                  <option value="">Pilih Provinsi...</option>
                  @foreach($provinsi as $prov)
                     <option value="{{ $prov->id }}" @selected(old('id_provinsi', $event->id_provinsi ?? '') == $prov->id)>{{ $prov->provinsi }}</option>
                  @endforeach
               </select>
            </div>

            <div class="col-md-6">
               <label for="kota" class="form-label">Kota</label>
               <input type="text" id="kota" class="form-control" name="kota" value="{{ old('kota', $event->kota ?? '') }}" placeholder="Masukkan kota event" required>
            </div>

            <div class="col-md-6">
               <label for="sumber" class="form-label">Sumber Informasi</label>
               <input type="text" class="form-control" id="sumber" name="sumber" value="{{ old('sumber', $event->sumber ?? '') }}" placeholder="Sumber informasi event">
            </div>

            <div class="col-md-6">
               <label for="htm" class="form-label">Harga Tiket Masuk</label>
               <input type="number" class="form-control" id="htm" name="htm" value="{{ old('htm', $event->htm ?? '') }}" placeholder="Masukkan harga tiket" min="0">
            </div>

            <div class="col-md-6">
               <label for="kelasPertandingan" class="form-label">Kelas Pertandingan</label>
               <input type="text" class="form-control" id="kelasPertandingan" name="kelasPertandingan" value="{{ old('kelasPertandingan', $event->kelasPertandingan ?? '') }}" placeholder="Contoh: 3 on 3 Rifle, Duelling Plat">
            </div>

            <div class="col-12">
               <label for="deskripsi" class="form-label">Detail / Deskripsi Event</label>
               <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6" maxlength="1000" placeholder="Jelaskan konsep event, rangkaian kegiatan, ketentuan peserta, dan informasi penting lainnya...">{{ old('deskripsi', $event->deskripsi ?? '') }}</textarea>
               <div class="form-text text-end"><span id="deskripsiCounter">0</span>/1000 karakter</div>
            </div>

            <div class="col-12" style="margin-bottom: 300px;">
               <button type="submit" class="btn btn-primary" onclick="prepareSubmit()">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}</button>
               <button type="button" class="btn btn-secondary" onclick="resetAllCards()">Reset Card</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection

@push('scripts')
<script>
   const MAX_POSTERS = {{ $maxPoster }};
   const addPosterBtn = document.getElementById('addPosterBtn');
   const posterContainer = document.getElementById('posterCardContainer');

   function getCardItems() {
      return document.querySelectorAll('#posterCardContainer .card-item');
   }

   function refreshAddButton() {
      if (!addPosterBtn || !addPosterBtn.parentElement) return;
      const count = getCardItems().length;
      addPosterBtn.parentElement.style.display = count >= MAX_POSTERS ? 'none' : '';
   }

   function prepareSubmit() {
      const cardData = Array.from(getCardItems()).map(card => ({
         id: card.dataset.cardId,
         hasFile: (card.querySelector('.poster-input')?.files?.length || 0) > 0
      }));
      const cardDataInput = document.getElementById('cardData');
      if (cardDataInput) {
         cardDataInput.value = JSON.stringify(cardData);
      }
   }

   function resetAllCards() {
      const cards = getCardItems();
      cards.forEach((card, index) => {
         if (index === 0) {
            const input = card.querySelector('.poster-input');
            if (input) input.value = '';
            const img = card.querySelector('img');
            if (img) img.src = '{{ asset('img/balnkLogo.png') }}';
         } else {
            card.remove();
         }
      });
      refreshAddButton();
   }

   window.prepareSubmit = prepareSubmit;
   window.resetAllCards = resetAllCards;

   document.addEventListener('click', function (event) {
      const uploadButton = event.target.closest('.btn-upload');
      if (uploadButton) {
         const inputId = 'fileInputButton' + uploadButton.dataset.id;
         const targetInput = document.getElementById(inputId);
         if (targetInput) targetInput.click();
         return;
      }

      const removeButton = event.target.closest('.btn-remove');
      if (removeButton) {
         const card = removeButton.closest('.card-item');
         if (card && getCardItems().length > 1) {
            card.remove();
            refreshAddButton();
         }
         return;
      }
   });

   document.addEventListener('change', function (event) {
      const input = event.target.closest('.poster-input');
      if (!input || !input.files || !input.files[0]) return;

      const reader = new FileReader();
      reader.onload = function (e) {
         const card = input.closest('.card-item');
         const img = card ? card.querySelector('img') : null;
         if (img) img.src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
   });

   let nextCardId = Math.max(0, ...Array.from(getCardItems()).map(c => parseInt(c.dataset.cardId, 10) || 0)) + 1;

   if (addPosterBtn) {
      addPosterBtn.addEventListener('click', function (e) {
         e.preventDefault();
         const currentCards = getCardItems();
         if (currentCards.length >= MAX_POSTERS) {
            return;
         }

         const template = currentCards[0] || document.querySelector('.card-item');
         if (!template) return;

         const clone = template.cloneNode(true);
         const newId = nextCardId++;
         clone.dataset.cardId = newId;

         const uploadBtn = clone.querySelector('.btn-upload');
         if (uploadBtn) {
            uploadBtn.dataset.id = newId;
            uploadBtn.textContent = 'Upload File';
         }

         const removeBtn = clone.querySelector('.btn-remove');
         if (removeBtn) {
            removeBtn.dataset.id = newId;
         }

         const input = clone.querySelector('.poster-input');
         if (input) {
            input.id = 'fileInputButton' + newId;
            input.name = 'poster[]';
            input.value = '';
         }

         const img = clone.querySelector('img');
         if (img) {
            img.src = '{{ asset('img/balnkLogo.png') }}';
            img.alt = 'Poster ' + newId;
         }

         addPosterBtn.parentElement.before(clone);
         refreshAddButton();
      });
   }

   const deskripsiEvent = document.getElementById('deskripsi');
   const deskripsiCounter = document.getElementById('deskripsiCounter');

   if (deskripsiEvent && deskripsiCounter) {
      deskripsiCounter.textContent = deskripsiEvent.value.length;
      deskripsiEvent.addEventListener('input', function () {
         deskripsiCounter.textContent = this.value.length;
      });
   }

   refreshAddButton();
</script>
@endpush