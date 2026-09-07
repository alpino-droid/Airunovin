@extends('layout/dashboard')

@section('title', 'Home - Laravel Blade')

@section('content')
@php
    $maxPoster = \App\Models\event::MAX_POSTERS;
@endphp

<div class="container text-start pt-3">
   <div class="mt-5 bg-light row p-3">
      <div class="col-md-12 mt-3" style="overflow: visible;">
         <form class="row g-3" action="{{ route('event.store') }}" method="POST" enctype="multipart/form-data" style="overflow: visible;">
            @csrf

            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
               <h4 class="mb-0">Poster Event</h4>
               <span class="badge bg-light text-dark border">Maksimal {{ $maxPoster }} poster</span>
            </div>

            <div id="cardContainer" class="col-12 d-flex flex-wrap">
               <div class="col-md-3 mt-2 mb-3 card-item" data-card-id="1">
                  <div class="card" style="width: 18rem;">
                     <img src="{{ asset('img/balnkLogo.png') }}" class="card-img-top" alt="Gambar 1">
                     <div class="card-body">
                        <button type="button" class="btn btn-outline-primary btn-upload" data-id="1">Upload File</button>
                        <input type="file" id="fileInputButton1" name="poster[]" class="d-none poster-input" accept="image/*">
                        <button type="button" class="btn btn-outline-danger btn-sm mt-2 btn-remove" data-id="1">Hapus</button>
                     </div>
                  </div>
               </div>

               <div class="col-md-3 mt-2 mb-3">
                  <button type="button" id="addCardBtn" class="btn-outline-primary d-flex flex-column align-items-center justify-content-center" style="width: 18rem; height: 18rem; border: 2px dashed #0d6efd; border-radius: 8px; background: transparent;">
                     <img src="{{ asset('icon/basil--add-outline.png') }}" alt="Tambah" style="width: 50px; height: 50px;">
                  </button>
               </div>
            </div>

            <input type="hidden" name="card_data" id="cardData">

            <div class="col-md-6">
               <label for="nama" class="form-label">Nama Event</label>
               <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama event" required>
            </div>

            <div class="col-md-6">
               <label for="tanggal" class="form-label">Tanggal</label>
               <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>

            <div class="col-md-6">
               <label for="penyelenggara" class="form-label">Penyelenggara</label>
               <input type="text" class="form-control" id="penyelenggara" name="penyelenggara" placeholder="Masukkan nama penyelenggara" required>
            </div>

            <div class="col-md-6">
               <label for="lokasi" class="form-label">Lokasi</label>
               <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Masukkan lokasi event">
            </div>

            <div class="col-md-6" style="overflow: visible;">
               <label for="id_provinsi" class="form-label">Provinsi</label>
               <select id="id_provinsi" class="form-select" name="id_provinsi" required style="position: relative; z-index: 1050;">
                  <option value="">Pilih Provinsi...</option>
                  @foreach($provinsi as $prov)
                     <option value="{{ $prov->id }}">{{ $prov->provinsi }}</option>
                  @endforeach
               </select>
            </div>

            <div class="col-md-6" style="overflow: visible;">
               <label for="kota" class="form-label">Kota</label>
               <select id="kota" class="form-select" name="kota" required style="position: relative; z-index: 1050;">
                  <option value="">Pilih Kota...</option>
                  <option value="Jakarta">Jakarta</option>
                  <option value="Bandung">Bandung</option>
                  <option value="Surabaya">Surabaya</option>
                  <option value="Yogyakarta">Yogyakarta</option>
                  <option value="Denpasar">Denpasar</option>
                  <option value="Lainnya">Lainnya</option>
               </select>
            </div>

            <div class="col-md-6">
               <label for="sumber" class="form-label">Sumber Informasi</label>
               <input type="text" class="form-control" id="sumber" name="sumber" placeholder="Sumber informasi event">
            </div>

            <div class="col-md-6">
               <label for="htm" class="form-label">Harga Tiket Masuk</label>
               <input type="number" class="form-control" id="htm" name="htm" placeholder="Masukkan harga tiket" min="0">
            </div>

            <div class="col-12">
               <label for="deskripsi" class="form-label">Detail / Deskripsi Event</label>
               <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6" maxlength="1000" placeholder="Jelaskan konsep event, rangkaian kegiatan, ketentuan peserta, dan informasi penting lainnya..."></textarea>
               <div class="form-text text-end"><span id="deskripsiCounter">0</span>/1000 karakter</div>
            </div>

            <div class="col-12" style="margin-bottom: 300px;">
               <button type="submit" class="btn btn-primary" onclick="prepareSubmit()">Simpan</button>
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
   let currentCardCount = document.querySelectorAll('.card-item').length;
   const addCardBtn = document.getElementById('addCardBtn');

   function refreshAddButton() {
      addCardBtn.style.display = currentCardCount >= MAX_POSTERS ? 'none' : 'flex';
   }

   function prepareSubmit() {
      const cardData = Array.from(document.querySelectorAll('.card-item')).map(card => ({
         id: card.dataset.cardId,
         hasFile: card.querySelector('.poster-input')?.files?.length > 0
      }));
      document.getElementById('cardData').value = JSON.stringify(cardData);
   }

   function resetAllCards() {
      const cards = document.querySelectorAll('.card-item');
      cards.forEach((card, index) => {
         if (index === 0) {
            const input = card.querySelector('.poster-input');
            input.value = '';
            card.querySelector('img').src = '{{ asset('img/balnkLogo.png') }}';
         } else {
            card.remove();
         }
      });
      currentCardCount = 1;
      refreshAddButton();
   }

   document.addEventListener('click', function (event) {
      const uploadButton = event.target.closest('.btn-upload');
      if (uploadButton) {
         const inputId = 'fileInputButton' + uploadButton.dataset.id;
         document.getElementById(inputId).click();
      }

      const removeButton = event.target.closest('.btn-remove');
      if (removeButton) {
         const card = removeButton.closest('.card-item');
         if (document.querySelectorAll('.card-item').length > 1) {
            card.remove();
            currentCardCount = document.querySelectorAll('.card-item').length;
            refreshAddButton();
         }
      }
   });

   document.addEventListener('change', function (event) {
      const input = event.target.closest('.poster-input');
      if (!input || !input.files || !input.files[0]) return;

      const reader = new FileReader();
      reader.onload = function (e) {
         const card = input.closest('.card-item');
         const img = card.querySelector('img');
         img.src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
   });

   addCardBtn.addEventListener('click', function () {
      if (currentCardCount >= MAX_POSTERS) {
         return;
      }

      const template = document.querySelector('.card-item');
      const clone = template.cloneNode(true);
      const newId = currentCardCount + 1;
      clone.dataset.cardId = newId;
      clone.querySelector('.btn-upload').dataset.id = newId;
      clone.querySelector('.btn-remove').dataset.id = newId;

      const input = clone.querySelector('.poster-input');
      input.id = 'fileInputButton' + newId;
      input.name = 'poster[]';
      input.value = '';
      clone.querySelector('img').src = '{{ asset('img/balnkLogo.png') }}';

      addCardBtn.parentElement.before(clone);
      currentCardCount++;
      refreshAddButton();
   });

   const deskripsiEvent = document.getElementById('deskripsi');
   const deskripsiCounter = document.getElementById('deskripsiCounter');

   deskripsiEvent.addEventListener('input', function () {
      deskripsiCounter.textContent = this.value.length;
   });
</script>
@endpush