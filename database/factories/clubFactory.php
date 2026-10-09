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
                'nama' => 'SLAM Commando (Souting Legion Airsofter Malang)',
                'induk_organisasi' => 'INASOC',
                'provinsi_nama' => 'Jawa Timur',
                'city' => 'Malang',
                'deskripsi' => 'Komunitas airsoft legendaris di Malang Raya yang bersemboyan "We Born, We Fight, We Win". Aktif dalam simulasi tempur hutan (woodland), CQB, dan pembinaan atlet tembak reaksi airsoft di Jawa Timur.',
                'gform_link' => 'https://forms.gle/SlamCommandoMalang',
                'logo' => 'club_logos/slam_commando.jpg',
            ],
            [
                'nama' => 'OWL Airsoft Club',
                'induk_organisasi' => 'PORGASI',
                'provinsi_nama' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'deskripsi' => 'Komunitas airsoft night ops dan tactical unit yang mengusung lambang burung hantu (OWL). Menekankan taktik regu senyap, pengintaian, kedisiplinan, serta keselamatan bermain airsoft.',
                'gform_link' => 'https://forms.gle/OwlAirsoftClub',
                'logo' => 'club_logos/owl_airsoft_club.jpg',
            ],
            [
                'nama' => 'Airsofter Spring Semarang (A.S.S)',
                'induk_organisasi' => 'PORGASI',
                'provinsi_nama' => 'Jawa Tengah',
                'city' => 'Semarang',
                'deskripsi' => 'Komunitas penggiat unit airsoft spring di wilayah Semarang dan Jawa Tengah. Menjunjung tinggi sportivitas, kekeluargaan, kejujuran (hit call), serta kegiatan skirmish ramah kantong bagi pemula.',
                'gform_link' => 'https://forms.gle/AirsofterSpringSemarang',
                'logo' => 'club_logos/airsofter_spring_semarang.jpg',
            ],
            [
                'nama' => 'Pagoda Rangers Tactical Squad',
                'induk_organisasi' => 'FAI',
                'provinsi_nama' => 'Jawa Tengah',
                'city' => 'Semarang',
                'deskripsi' => 'Didirikan sejak 2018 (Est. 2018), Pagoda Rangers Tactical Squad adalah skuad taktis airsoft yang aktif dalam latihan simulasi pertempuran, skenario penyelamatan sandera, dan patroli taktis.',
                'gform_link' => 'https://forms.gle/PagodaRangersTactical',
                'logo' => 'club_logos/pagoda_rangers.jpg',
            ],
            [
                'nama' => 'Dewata Tactical Airsoft (DTA)',
                'induk_organisasi' => 'PERBAKIN',
                'provinsi_nama' => 'Bali',
                'city' => 'Denpasar',
                'deskripsi' => 'Club airsoft modern berbasis di Pulau Dewata Bali. Berfokus pada kompetisi speedsoft, CQB dinamis, dan pembinaan atlet tembak reaksi airsoft (AAIPSC) di wilayah Bali dan Nusa Tenggara.',
                'gform_link' => 'https://forms.gle/DewataTacticalAirsoft',
                'logo' => 'club_logos/dewata_tactical_airsoft.jpg',
            ],
            [
                'nama' => 'Sriwijaya Tactical Airsoft Club',
                'induk_organisasi' => 'INASOC',
                'provinsi_nama' => 'Sumatera Selatan',
                'city' => 'Palembang',
                'deskripsi' => 'Club airsoft pelopor di Bumi Sriwijaya yang aktif mengkampanyekan olahraga airsoft yang aman, tertib, dan legal. Memiliki jadwal rutin latihan taktik regu dan simulasi penyelamatan sandera dengan semboyan "Una In Diversitate".',
                'gform_link' => 'https://forms.gle/SriwijayaTacticalAirsoft',
                'logo' => 'club_logos/sriwijaya_tactical_airsoft.jpg',
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
            'status' => 'diterima',
        ];
    }
}
