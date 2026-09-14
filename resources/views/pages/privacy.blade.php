@extends('layout/app')

@section('title', 'Kebijakan Privasi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <p class="text-uppercase text-primary fw-semibold small mb-2">Airunovin</p>
            <h1 class="display-5 fw-bold mb-2">Kebijakan Privasi</h1>
            <p class="text-muted mb-5">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>

            <section class="mb-4">
                <h2 class="h4 fw-bold">Data yang kami kumpulkan</h2>
                <p class="text-muted">Kami dapat menyimpan data akun seperti nama, email, nomor telepon, lokasi, serta konten yang Anda kirimkan, termasuk event, club, dan produk marketplace.</p>
            </section>
            <section class="mb-4">
                <h2 class="h4 fw-bold">Penggunaan data</h2>
                <p class="text-muted">Data digunakan untuk menyediakan fitur akun, menampilkan konten komunitas, memproses unggahan, meningkatkan layanan, dan menjaga keamanan platform.</p>
            </section>
            <section class="mb-4">
                <h2 class="h4 fw-bold">Penyimpanan dan keamanan</h2>
                <p class="text-muted">Kami berupaya menjaga data melalui pengaturan akses dan praktik keamanan yang wajar. Jangan membagikan kata sandi kepada siapa pun.</p>
            </section>
            <section class="mb-4">
                <h2 class="h4 fw-bold">Hak pengguna</h2>
                <p class="text-muted">Anda dapat memperbarui informasi profil dan meminta bantuan terkait data akun melalui pengelola platform.</p>
            </section>
            <section>
                <h2 class="h4 fw-bold">Perubahan kebijakan</h2>
                <p class="text-muted mb-0">Kebijakan ini dapat diperbarui untuk menyesuaikan perubahan fitur atau peraturan yang berlaku. Perubahan akan ditampilkan pada halaman ini.</p>
            </section>
        </div>
    </div>
</div>
@endsection
