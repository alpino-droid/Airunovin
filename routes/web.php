<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HalamanController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;

// ============ ROUTE HALAMAN PUBLIK ============
Route::get('/', [EventController::class, 'home'])->name('home');
Route::get('/event', [EventController::class, 'event'])->name('event');
Route::get('/event/nama', [EventController::class, 'isiEvent'])->name('isiEvent');
Route::get('/marketplace', [HalamanController::class, 'marketplace'])->name('marketplace');
Route::get('/marketplace/jenis_barang/nama_barang', [HalamanController::class, 'isiMarketplace'])->name('isiMarketplace');
Route::get('/club', [ClubController::class, 'home'])->name('club');
Route::get('/club/detail', [ClubController::class, 'isiClub'])->name('isiClub');

// ============ ROUTE ADMIN (FRONT-END) ============
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/club', [App\Http\Controllers\AdminController::class, 'club'])->name('admin.club');
Route::get('/admin/event', [App\Http\Controllers\AdminController::class, 'event'])->name('admin.event');
Route::get('/admin/marketplace', [App\Http\Controllers\AdminController::class, 'marketplace'])->name('admin.marketplace');
Route::get('/admin/registrasi', [App\Http\Controllers\AdminController::class, 'registrasi'])->name('admin.registrasi');
Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');

Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['id', 'en'], true), 404);

    session()->put('locale', $locale);
    app()->setLocale($locale);

    return redirect()->to(url()->previous() ?: route('home'));
})->name('language.switch');

Route::get('/notifikasi', function () {
    return view('pages.notifications', ['mode' => 'all']);
})->name('notifications');

Route::get('/notifikasi/penjual', function () {
    return view('pages.notifications', ['mode' => 'seller']);
})->name('notifications.seller');

Route::get('/notifikasi/pembeli', function () {
    return view('pages.notifications', ['mode' => 'buyer']);
})->name('notifications.buyer');

Route::get('/notifikasi/detail/{mode}/{index}', function (string $mode, int $index) {
    $sellerNotifications = [
        ['title' => 'Pembelian baru', 'type' => 'Penjualan', 'message' => 'Produk "Rantai Matic" berhasil dibeli oleh pelanggan.', 'time' => '2 menit lalu'],
        ['title' => 'Pesanan dikirim', 'type' => 'Pengiriman', 'message' => 'Status pengiriman untuk order #MK-2048 sudah diperbarui.', 'time' => '1 jam lalu'],
        ['title' => 'Stok menipis', 'type' => 'Inventaris', 'message' => 'Stok produk "Ban Dalam 14 inch" tersisa 3 unit.', 'time' => '3 jam lalu'],
        ['title' => 'Review pelanggan', 'type' => 'Feedback', 'message' => 'Ada review baru dengan rating 5 dari pembeli.', 'time' => '1 hari lalu'],
    ];
    $buyerNotifications = [
        ['title' => 'Pembayaran berhasil', 'type' => 'Transaksi', 'message' => 'Pembayaran untuk produk "Helm Fullface" berhasil diproses.', 'time' => '10 menit lalu'],
        ['title' => 'Barang dikirim', 'type' => 'Pengiriman', 'message' => 'Pesanan Anda sedang dikirim dengan nomor resi JNE-2048.', 'time' => '2 jam lalu'],
        ['title' => 'Promo terbaru', 'type' => 'Promo', 'message' => 'Diskon 20% untuk sparepart motor hari ini.', 'time' => '5 jam lalu'],
        ['title' => 'Pesanan diterima', 'type' => 'Status', 'message' => 'Barang telah diterima dengan kondisi baik.', 'time' => '2 hari lalu'],
    ];

    $items = $mode === 'penjual' ? $sellerNotifications : ($mode === 'pembeli' ? $buyerNotifications : array_merge($sellerNotifications, $buyerNotifications));
    abort_unless(isset($items[$index]), 404);

    return view('pages.notificationDetail', ['item' => $items[$index], 'mode' => $mode]);
})->name('notifications.detail');

// ============ ROUTE AUTH (TAMPILAN) ============
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::get('/register', [UserController::class, 'register'])->name('register');
});

// ============ ROUTE AUTH (PROSES) ============
Route::post('/login', [UserController::class, 'prosesLogin'])->name('login.proses');
Route::post('/register', [UserController::class, 'prosesRegister'])->name('register.proses');
Route::post('/logout', [UserController::class, 'prosesLogout'])->name('logout');

// ============ ROUTE YANG BUTUH LOGIN ============
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboardMarketplace', [HalamanController::class, 'DM'])->name('dashboardMarketplace');
    Route::get('/dashboardMarketplace/product/create', [HalamanController::class, 'productCreate'])->name('product.create');
    Route::post('/dashboardMarketplace/product', [HalamanController::class, 'productStore'])->name('product.store');
    
    // Route Club
    Route::get('/dashboardClub', [ClubController::class, 'clubCreate'])->name('dashboardClub');
    Route::post('/dashboardClub', [ClubController::class, 'clubStore'])->name('dashboardClub.store');
    Route::get('/dashboardClub/{id}', [ClubController::class, 'clubShow'])->name('dashboardClub.show');
    Route::get('/dashboardClub/{id}/edit', [ClubController::class, 'clubEdit'])->name('dashboardClub.edit');
    Route::put('/dashboardClub/{id}', [ClubController::class, 'clubUpdate'])->name('dashboardClub.update');
    Route::delete('/dashboardClub/{id}', [ClubController::class, 'clubDestroy'])->name('dashboardClub.destroy');
    
    // Event & Profil
    Route::get('/event/create', [EventController::class, 'eventCreate'])->name('event.create');
    Route::post('/event/store', [EventController::class, 'eventStore'])->name('event.store');
    Route::get('/profil', [UserController::class, 'profil'])->name('profil');
    Route::put('/profil/update', [UserController::class, 'updateProfil'])->name('profil.update');
});