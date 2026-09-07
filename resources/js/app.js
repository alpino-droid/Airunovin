// public/js/app.js

function pilihProvinsi(element) {
    // Ambil teks dari item yang diklik
    const teks = element.textContent;
    
    // Cari span untuk mengubah teks
    const span = document.getElementById('selectedText');
    
    // Ubah teks span dengan teks yang dipilih
    span.textContent = teks;
    
    // Hapus class 'active' dari semua item
    const semuaItem = document.querySelectorAll('.dropdown-item');
    semuaItem.forEach(item => {
        item.classList.remove('active');
    });
    
    // Tambahkan class 'active' ke item yang diklik
    element.classList.add('active');
    
    // Tutup dropdown otomatis
    const tombol = document.getElementById('provinsiButton');
    const dropdown = bootstrap.Dropdown.getInstance(tombol);
    if (dropdown) {
        dropdown.hide();
    }
}

// Saat halaman dimuat, tampilkan teks dari item yang aktif
document.addEventListener('DOMContentLoaded', function() {
    // Cari item yang memiliki class 'active'
    const activeItem = document.querySelector('.dropdown-item.active');
    const span = document.getElementById('selectedText');
    
    if (activeItem && span) {
        // Tampilkan teks dari item aktif di tombol
        span.textContent = activeItem.textContent;
    }
});

$(document).ready(function() {
    $('#inputState').select2({
        placeholder: "Cari provinsi...",
        allowClear: true,
        width: '100%',
        language: {
            searching: function() { return 'Mencari...'; },
            noResults: function() { return 'Provinsi tidak ditemukan'; }
        }
    });
});
