<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HalamanController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NotificationController;

// ============ ROUTE HALAMAN PUBLIK ============
Route::get('/', [EventController::class, 'home'])->name('home');
Route::get('/event', [EventController::class, 'event'])->name('event');
Route::middleware('auth')->get('/event/create', [EventController::class, 'eventCreate'])->name('event.create');
Route::get('/event/{event}', [EventController::class, 'isiEvent'])->whereNumber('event')->name('isiEvent');
Route::get('/marketplace', [HalamanController::class, 'marketplace'])->name('marketplace');
Route::get('/marketplace/nama', [MarketplaceController::class, 'market'])->name('market');
Route::get('/marketplace/{product}', [ProductController::class, 'isiMarketplace'])->whereNumber('product')->name('isiMarketplace');
Route::get('/club', [ClubController::class, 'home'])->name('club');
Route::get('/club/{club}', [ClubController::class, 'isiClub'])->name('isiClub');
Route::view('/tentang', 'pages.about')->name('about');
Route::view('/kebijakan-privasi', 'pages.privacy')->name('privacy');

// ============ ROUTE ADMIN ============
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/club', [App\Http\Controllers\AdminController::class, 'club'])->name('admin.club');
Route::get('/admin/event', [App\Http\Controllers\AdminController::class, 'event'])->name('admin.event');
Route::get('/admin/marketplace', [App\Http\Controllers\AdminController::class, 'marketplace'])->name('admin.marketplace');
Route::get('/admin/registrasi', [App\Http\Controllers\AdminController::class, 'registrasi'])->name('admin.registrasi');
Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');

// Admin Event CRUD
Route::post('/admin/event', [App\Http\Controllers\AdminController::class, 'eventStore'])->name('admin.event.store');
Route::put('/admin/event/{id}', [App\Http\Controllers\AdminController::class, 'eventUpdate'])->name('admin.event.update');
Route::delete('/admin/event/{id}', [App\Http\Controllers\AdminController::class, 'eventDestroy'])->name('admin.event.destroy');

// Admin Club CRUD
Route::post('/admin/club', [App\Http\Controllers\AdminController::class, 'clubStore'])->name('admin.club.store');
Route::put('/admin/club/{id}', [App\Http\Controllers\AdminController::class, 'clubUpdate'])->name('admin.club.update');
Route::delete('/admin/club/{id}', [App\Http\Controllers\AdminController::class, 'clubDestroy'])->name('admin.club.destroy');

// Admin Marketplace / Product CRUD
Route::post('/admin/marketplace', [App\Http\Controllers\AdminController::class, 'productStore'])->name('admin.marketplace.store');
Route::put('/admin/marketplace/{id}', [App\Http\Controllers\AdminController::class, 'productUpdate'])->name('admin.marketplace.update');
Route::delete('/admin/marketplace/{id}', [App\Http\Controllers\AdminController::class, 'productDestroy'])->name('admin.marketplace.destroy');

Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['id', 'en'], true), 404);

    session()->put('locale', $locale);
    app()->setLocale($locale);

    return redirect()->to(url()->previous() ?: route('home'));
})->name('language.switch');

// ============ ROUTE NOTIFIKASI ============
Route::get('/notifikasi', [NotificationController::class, 'index'])->defaults('mode', 'all')->name('notifications');
Route::get('/notifikasi/penjual', [NotificationController::class, 'index'])->defaults('mode', 'seller')->name('notifications.seller');
Route::get('/notifikasi/pembeli', [NotificationController::class, 'index'])->defaults('mode', 'buyer')->name('notifications.buyer');
Route::post('/notifikasi/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
Route::post('/notifikasi/{id}/mark-read', [NotificationController::class, 'markAsRead'])->whereNumber('id')->name('notifications.markRead');
Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy'])->whereNumber('id')->name('notifications.destroy');
Route::get('/notifikasi/{id}', [NotificationController::class, 'show'])->whereNumber('id')->name('notifications.detail');

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
    Route::get('/dashboardMarketplace/create', [MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/dashboardMarketplace', [MarketplaceController::class, 'store'])->name('marketplace.store');
    Route::get('/dashboardMarketplace/{marketplace}/edit', [MarketplaceController::class, 'edit'])->name('marketplace.edit');
    Route::put('/dashboardMarketplace/{marketplace}', [MarketplaceController::class, 'update'])->name('marketplace.update');
    Route::delete('/dashboardMarketplace/{marketplace}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');
    Route::get('/dashboardMarketplace/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/dashboardMarketplace/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/dashboardMarketplace/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/dashboardMarketplace/product/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/dashboardMarketplace/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    
    // Route Club
    Route::get('/dashboardClub', [ClubController::class, 'clubCreate'])->name('dashboardClub');
    Route::post('/dashboardClub', [ClubController::class, 'clubStore'])->name('dashboardClub.store');
    Route::get('/dashboardClub/{id}', [ClubController::class, 'clubShow'])->name('dashboardClub.show');
    Route::get('/dashboardClub/{id}/edit', [ClubController::class, 'clubEdit'])->name('dashboardClub.edit');
    Route::put('/dashboardClub/{id}', [ClubController::class, 'clubUpdate'])->name('dashboardClub.update');
    Route::delete('/dashboardClub/{id}', [ClubController::class, 'clubDestroy'])->name('dashboardClub.destroy');
    
    // Event & Profil
    Route::get('/event/{event}/edit', [EventController::class, 'eventEdit'])->whereNumber('event')->name('event.edit');
    Route::post('/event/store', [EventController::class, 'eventStore'])->name('event.store');
    Route::put('/event/{event}', [EventController::class, 'eventUpdate'])->whereNumber('event')->name('event.update');
    Route::delete('/event/{event}', [EventController::class, 'eventDestroy'])->whereNumber('event')->name('event.destroy');
    Route::get('/profil', [UserController::class, 'profil'])->name('profil');
    Route::put('/profil/update', [UserController::class, 'updateProfil'])->name('profil.update');
});