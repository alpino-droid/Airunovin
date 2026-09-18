<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Provinsi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Club>
 */
class clubFactory extends Factory
{
    protected $model = Club::class;

    public static function indonesianClubs(): array
    {
        return [
            [
                'nama' => 'Ranger Squad Tactical Airsoft',
                'induk_organisasi' => 'PORGASI',
                'provinsi_nama' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'deskripsi' => 'Komunitas airsoft taktikal yang berfokus pada simulasi militer (Milsim), latihan CQB (Close Quarters Battle), serta menjunjung tinggi sportivitas dan kode etik safety airsoft gun. Berdiri sejak 2014 dan aktif mengadakan skirmish mingguan.',
                'gform_link' => 'https://forms.gle/RangerSquadJakarta',
                'logo' => 'default_club.png',
            ],
            [
                'nama' => 'Arjuna Tactical Airsoft Club',
                'induk_organisasi' => 'FAI',
                'provinsi_nama' => 'Jawa Barat',
                'city' => 'Bandung',
                'deskripsi' => 'Club airsoft berbasis di Bandung yang mewadahi penggiat olahraga airsoft gun di Jawa Barat. Aktif dalam latihan taktis, menembak reaksi cepat (AAIPSC), dan kejuaraan kompetisi antar-club tingkat daerah maupun nasional.',
                'gform_link' => 'https://forms.gle/ArjunaTacticalBandung',
                'logo' => 'default_club.png',
            ],
            [
                'nama' => 'SLAM Commando (Souting Legion Airsofter Malang)',
                'induk_organisasi' => 'INASOC',
                'provinsi_nama' => 'Jawa Timur',
                'city' => 'Malang',
                'deskripsi' => 'Komunitas airsoft legendaris di Malang Raya yang bersemboyan "We Born, We Fight, We Win". Aktif dalam simulasi tempur hutan (woodland), CQB, dan pembinaan atlet tembak reaksi airsoft di Jawa Timur.',
                'gform_link' => 'https://forms.gle/SlamCommandoMalang',
                'logo' => 'club_logos/slam_commando.jpg',
            ],
            [
                'nama' => 'Jatim Softer Community (JSC)',
                'induk_organisasi' => 'PORGASI',
                'provinsi_nama' => 'Jawa Timur',
                'city' => 'Surabaya',
                'deskripsi' => 'Salah satu perkumpulan airsofter terbesar di Jawa Timur yang berbasis di Surabaya. Rutin menyelenggarakan latihan bersama lintas club, turnamen speedsoft, serta kegiatan bakti sosial kemasyarakatan.',
                'gform_link' => 'https://forms.gle/JatimSofterSurabaya',
                'logo' => 'default_club.png',
            ],
            [
                'nama' => 'Jogja Airsoft Brotherhood (JAB)',
                'induk_organisasi' => 'FAI',
                'provinsi_nama' => 'DI Yogyakarta',
                'city' => 'Yogyakarta',
                'deskripsi' => 'Wadah silaturahmi dan persaudaraan bagi pegiat airsoft di wilayah Yogyakarta dan sekitarnya. Berorientasi pada edukasi regulasi hukum airsoft di Indonesia, keamanan bermain (eye & face protection), dan skirmish rekreatif.',
                'gform_link' => 'https://forms.gle/JogjaAirsoftBrotherhood',
                'logo' => 'default_club.png',
            ],
            [
                'nama' => 'Night Ops Team Semarang',
                'induk_organisasi' => 'PORGASI',
                'provinsi_nama' => 'Jawa Tengah',
                'city' => 'Semarang',
                'deskripsi' => 'Club airsoft spesialis operasi taktis malam hari dan skenario penyerbuan gedung tertutup. Memiliki anggota yang solid, terstruktur, serta menjunjung tinggi asas kejujuran (hit call) dalam setiap permainan.',
                'gform_link' => 'https://forms.gle/NightOpsTeamSemarang',
                'logo' => 'default_club.png',
            ],
            [
                'nama' => 'Borneo Airsoft Troopers (BAT)',
                'induk_organisasi' => 'ABSI',
                'provinsi_nama' => 'Kalimantan Tengah',
                'city' => 'Pangkalan Bun',
                'deskripsi' => 'Komunitas airsoft tangguh dari Kalimantan Tengah yang terbiasa bertempur dalam rimbunnya hutan tropis Kalimantan. Sering menggelar event skala pulau seperti Borneo Warrior dan aksi pelestarian alam.',
                'gform_link' => 'https://forms.gle/BorneoAirsoftTroopers',
                'logo' => 'default_club.png',
            ],
            [
                'nama' => 'Bali Tactical Airsoft Community (BATAC)',
                'induk_organisasi' => 'PERBAKIN',
                'provinsi_nama' => 'Bali',
                'city' => 'Denpasar',
                'deskripsi' => 'Komunitas airsoft di Pulau Bali yang menaungi divisi Milsim dan Tembak Reaksi Airsoft (AAIPSC). Menyediakan fasilitas latihan yang ramah pemula dan sering menjadi tuan rumah kejuaraan airsoft internasional.',
                'gform_link' => 'https://forms.gle/BatacBaliAirsoft',
                'logo' => 'default_club.png',
            ],
            [
                'nama' => 'Sriwijaya Airsoft Division (SAD)',
                'induk_organisasi' => 'PORGASI',
                'provinsi_nama' => 'Sumatera Selatan',
                'city' => 'Palembang',
                'deskripsi' => 'Club airsoft pelopor di Bumi Sriwijaya yang aktif mengkampanyekan olahraga airsoft yang aman, tertib, dan legal. Memiliki jadwal rutin latihan taktik regu dan simulasi penyelamatan sandera.',
                'gform_link' => 'https://forms.gle/SriwijayaAirsoftPalembang',
                'logo' => 'default_club.png',
            ],
            [
                'nama' => 'Banten Airsoft Tactical (BAT)',
                'induk_organisasi' => 'FOBI',
                'provinsi_nama' => 'Banten',
                'city' => 'Tangerang',
                'deskripsi' => 'Perkumpulan penggemar airsoft di wilayah Tangerang dan Banten. Fokus pada kompetisi speedball / speedsoft dan pengembangan generasi muda dalam olahraga menembak sasaran airsoft.',
                'gform_link' => 'https://forms.gle/BantenAirsoftTactical',
                'logo' => 'default_club.png',
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
        $club = fake()->randomElement(self::indonesianClubs());
        $provinsi = Provinsi::where('provinsi', 'like', '%' . $club['provinsi_nama'] . '%')->first();

        return [
            'id_user' => User::factory(),
            'nama' => $club['nama'],
            'induk_organisasi' => $club['induk_organisasi'],
            'id_provinsi' => $provinsi ? (string)$provinsi->id : '1',
            'city' => $club['city'],
            'deskripsi' => $club['deskripsi'],
            'gform_link' => $club['gform_link'],
            'logo' => $club['logo'],
        ];
    }
}
