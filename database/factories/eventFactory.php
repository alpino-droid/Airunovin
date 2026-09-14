<?php

namespace Database\Factories;

use App\Models\event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<event>
 */
class eventFactory extends Factory
{
    public static function posterEvents(): array
    {
        return [
            [
                'nama' => 'PIALA WALIKOTA REKTORAT CUP AIRSOFTGUN SURABAYA',
                'tanggal' => '2026-05-30',
                'penyelenggara' => 'KORMI Kota Surabaya',
                'lokasi' => 'Universitas Wijaya Kusuma Surabaya',
                'id_provinsi' => 11,
                'kota' => 'Surabaya',
                'sumber' => null,
                'htm' => null,
                'deskripsi' => 'Terbuka untuk pelajar kelas spring dan umum kelas AEG. Diselenggarakan pada 30-31 Mei 2026 dengan format CTS, 3 on 3, dan sniper alley.',
                'poster' => ['PosterEvent/P.E.jpg'],
            ],
            [
                'nama' => 'BORNEO WARRIOR',
                'tanggal' => '2018-03-10',
                'penyelenggara' => 'Airsoft Pangkalan Bun',
                'lokasi' => 'Pangkalan Bun, Kalimantan Tengah',
                'id_provinsi' => 14,
                'kota' => 'Pangkalan Bun',
                'sumber' => null,
                'htm' => 0,
                'deskripsi' => 'Battle of Tanjung Keluang: Saving Tom the Ultimate Borneo for NKRI. CQB (Close Quarter Battle) Dandim Cup pada Sabtu, 10 Maret 2018 dan airsoft milsim pada Minggu, 11 Maret 2018. Pendaftaran gratis.',
                'poster' => ['PosterEvent/P.E1.jpg'],
            ],
            [
                'nama' => 'TACSHOT CHALLENGE',
                'tanggal' => '2026-05-10',
                'penyelenggara' => 'Dream Field Tactical',
                'lokasi' => 'Surabaya, Jawa Timur',
                'id_provinsi' => 11,
                'kota' => 'Surabaya',
                'sumber' => null,
                'htm' => 35000,
                'deskripsi' => '3rd Anniversary Dream Field Tactical. Tacshot Challenge spring dan all-unit pada Minggu, 10 Mei 2026. Kelas spring khusus pelajar Rp35.000 dan kelas all-unit umum Rp50.000. Peserta mendapatkan sertifikat, piala, dan uang pembinaan.',
                'poster' => ['PosterEvent/P.E2.jpeg'],
            ],
            [
                'nama' => 'PAYUDAN BANASPAT',
                'tanggal' => '2025-11-22',
                'penyelenggara' => 'Jatim Softer',
                'lokasi' => 'Taman Buah Jeru, Tumpang, Malang, Jawa Timur',
                'id_provinsi' => 11,
                'kota' => 'Malang',
                'sumber' => null,
                'htm' => 15000,
                'deskripsi' => 'Airsoft night skirmish dengan format spring, AEG, GBB, dan HPA. Sabtu, 22 November 2025, mulai pukul 19.00 sampai selesai. Attitude: no full auto. Proper gear, proper safety, proper clothes. HTM Rp15.000.',
                'poster' => ['PosterEvent/P.E3.jpeg'],
            ],
            [
                'nama' => 'PAYUDAN TANDEY EPISODE 8',
                'tanggal' => '2026-05-03',
                'penyelenggara' => 'Jatim Softer',
                'lokasi' => 'Puslat Rindam V/Brawijaya, Malang, Jawa Timur',
                'id_provinsi' => 11,
                'kota' => 'Malang',
                'sumber' => null,
                'htm' => 30000,
                'deskripsi' => 'Airsoft skirmish all open dengan format spring, AEG, GBB, dan HPA. Minggu, 3 Mei 2026, mulai pukul 12.00 sampai selesai. Attitude: no full auto. Proper gear, proper safety, proper clothes. HTM Rp30.000.',
                'poster' => ['PosterEvent/P.E4.jpeg'],
            ],
            [
                'nama' => 'MERAH PUTIH AIRSOFT CHALLENGE',
                'tanggal' => '2026-08-17',
                'penyelenggara' => 'Airsoft Indonesia',
                'lokasi' => 'Jawa Timur',
                'id_provinsi' => 11,
                'kota' => 'Surabaya',
                'sumber' => null,
                'htm' => null,
                'deskripsi' => 'Merah Putih Airsoft Challenge dengan total hadiah Rp50 juta. Tantangan airsoft untuk peserta yang siap bertanding dan bermain fair play.',
                'poster' => ['PosterEvent/UE2Rg1iIJlYNg6unPwSv9trcI5IavtSOcsnT9O54.jpg'],
            ],
        ];
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $event = $this->faker->randomElement(static::posterEvents());

        return array_merge($event, [
            'id_user' => $this->faker->randomElement(User::query()->pluck('id')->all()),
        ]);
    }
}
