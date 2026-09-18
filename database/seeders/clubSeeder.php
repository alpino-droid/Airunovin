<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Provinsi;
use App\Models\User;
use Database\Factories\clubFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class clubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan data master provinsi dan user tersedia
        if (Provinsi::count() === 0) {
            $this->call(ProvinsiSeeder::class);
        }

        if (User::count() === 0) {
            $this->call(userSeeder::class);
        }

        // 2. Siapkan direktori storage logo club dan logo default
        $clubLogoDir = storage_path('app/public/club_logos');
        File::ensureDirectoryExists($clubLogoDir);

        $defaultLogoSrc = public_path('img/balnkLogo.png');
        if (File::exists($defaultLogoSrc)) {
            File::copy($defaultLogoSrc, storage_path('app/public/default_club.png'));
            File::copy($defaultLogoSrc, $clubLogoDir . DIRECTORY_SEPARATOR . 'default_club.png');
        }

        // Salin logo SLAM Commando jika ada berkas aslinya
        $slamOriginal = $clubLogoDir . DIRECTORY_SEPARATOR . 'g8NhOP7QCxApcUtI5wOwt4YIaakqHW1tCoGDV0QR.jpg';
        $slamTarget = $clubLogoDir . DIRECTORY_SEPARATOR . 'slam_commando.jpg';
        if (File::exists($slamOriginal) && !File::exists($slamTarget)) {
            File::copy($slamOriginal, $slamTarget);
        }

        // 3. Bersihkan data club lama sebelum seeding
        Club::query()->delete();

        // 4. Ambil user untuk dijadikan pemilik club
        $users = User::all();
        $userCount = $users->count();

        // 5. Seed daftar club airsoft ternama di Indonesia
        foreach (clubFactory::indonesianClubs() as $index => $clubData) {
            $provinsi = Provinsi::where('provinsi', 'like', '%' . $clubData['provinsi_nama'] . '%')->first();
            $assignedUser = $users[$index % $userCount];

            Club::create([
                'id_user' => $assignedUser->id,
                'nama' => $clubData['nama'],
                'induk_organisasi' => $clubData['induk_organisasi'],
                'id_provinsi' => $provinsi ? (string)$provinsi->id : '1',
                'city' => $clubData['city'],
                'deskripsi' => $clubData['deskripsi'],
                'gform_link' => $clubData['gform_link'],
                'logo' => $clubData['logo'],
            ]);
        }
    }
}
