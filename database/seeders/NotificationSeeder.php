<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use App\Models\Product;
use App\Models\event;
use App\Models\Club;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::query()->delete();

        $user = User::first();
        $userId = $user?->id;

        $firstEvent = event::first();
        $firstProduct = Product::first();
        $firstClub = Club::first();

        $notifications = [
            [
                'id_user' => $userId,
                'title' => 'Event baru telah dipublikasikan',
                'message' => 'Event ' . ($firstEvent?->nama ?? 'Turnamen Airsoft Nasional') . ' kini siap diikuti. Segera cek regulasi dan daftarkan tim Anda!',
                'type' => 'Event',
                'category' => 'buyer',
                'link' => $firstEvent ? route('isiEvent', $firstEvent->id) : url('/event'),
                'icon' => 'bi-calendar-check',
                'read_at' => null, // unread
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ],
            [
                'id_user' => $userId,
                'title' => 'Produk baru tersedia di Marketplace',
                'message' => 'Unit taktis baru ' . ($firstProduct?->nama ?? 'M4 Carbine Tactical Edition') . ' telah ditambahkan ke katalog marketplace.',
                'type' => 'Marketplace',
                'category' => 'buyer',
                'link' => $firstProduct ? route('isiMarketplace', $firstProduct->id) : url('/marketplace'),
                'icon' => 'bi-bag-plus',
                'read_at' => null, // unread
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],
            [
                'id_user' => $userId,
                'title' => 'Club airsoft baru telah bergabung',
                'message' => 'Selamat datang ' . ($firstClub?->nama ?? 'SLAM Commando') . ' dalam naungan komunitas Airunovin Indonesia.',
                'type' => 'Club',
                'category' => 'all',
                'link' => $firstClub ? route('isiClub', $firstClub->id) : url('/club'),
                'icon' => 'bi-shield-check',
                'read_at' => null, // unread
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ],
            [
                'id_user' => $userId,
                'title' => 'Pesanan baru masuk ke toko Anda',
                'message' => 'Seorang pembeli telah mengirimkan rincian pesanan checkout untuk produk Anda via WhatsApp.',
                'type' => 'Marketplace',
                'category' => 'seller',
                'link' => url('/marketplace'),
                'icon' => 'bi-cart-check',
                'read_at' => null, // unread
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'id_user' => $userId,
                'title' => 'Pendaftaran club berhasil diverifikasi',
                'message' => 'Data legalitas dan profil club airsoft Anda telah disetujui oleh admin platform.',
                'type' => 'Club',
                'category' => 'seller',
                'link' => url('/club'),
                'icon' => 'bi-patch-check',
                'read_at' => now()->subDay(), // read
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'id_user' => $userId,
                'title' => 'Profil komunitas berhasil diperbarui',
                'message' => 'Informasi profil akun dan nomor kontak WhatsApp Anda telah berhasil diperbarui.',
                'type' => 'Akun',
                'category' => 'all',
                'link' => url('/notifikasi'),
                'icon' => 'bi-person-check',
                'read_at' => now()->subDays(2), // read
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id_user' => $userId,
                'title' => 'Pengingat: Verifikasi unit & safety code',
                'message' => 'Pastikan unit airsoft Anda memiliki barrel plug/orange tip saat mobilisasi ke arena skirmish.',
                'type' => 'Sistem',
                'category' => 'buyer',
                'link' => url('/tentang'),
                'icon' => 'bi-exclamation-triangle',
                'read_at' => null, // unread
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ];

        foreach ($notifications as $notif) {
            Notification::create($notif);
        }
    }
}
