<?php

namespace Database\Factories;

use App\Models\Marketplace;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Daftar katalog produk statis yang disesuaikan dengan gambar di public/img/imgStatik/GambarProduk
     */
    public static array $catalog = [
        [
            'gambar' => 'Product/G.S.P1.jpg',
            'nama' => 'Tactical Vest Plate Carrier MOLLE System',
            'merk' => 'Condor Tactical',
            'jenis' => 'Perlengkapan',
            'kondisi' => 'Baru',
            'harga' => 450000,
            'stok' => 15,
            'lokasi' => 'Jakarta',
            'deskripsi' => 'Rompi taktis plate carrier dengan sistem webbing MOLLE standar militer. Terbuat dari material Cordura 1000D yang tebal, tahan gesekan, dan tahan air. Dilengkapi dengan 3 pouch magazine rifle di bagian depan, 1 utility pouch, dan 1 admin pouch di dada. Ukuran dapat disesuaikan (all-size adjustable) pada bagian bahu dan pinggang. Sangat nyaman dan kokoh untuk skirmish airsoft maupun kegiatan outdoor taktis.',
            'payment_methods' => ['Transfer Bank', 'QRIS', 'COD'],
        ],
        [
            'gambar' => 'Product/G.S.P2.jpg',
            'nama' => 'M4 Carbine AEG M-LOK Tactical Edition',
            'merk' => 'Specna Arms',
            'jenis' => 'Unit',
            'kondisi' => 'Baru',
            'harga' => 2850000,
            'stok' => 5,
            'lokasi' => 'Bandung',
            'deskripsi' => 'Unit airsoft rifle M4 Carbine bertenaga listrik (AEG) dengan handguard aluminium M-LOK modern. Receiver full metal yang kokoh dengan finishing matte black. Dilengkapi gearbox V2 reinforced, sistem quick spring change (Enter & Convert), dan flip-up iron sight. Popor crane stock dapat diatur 6 posisi dan memuat baterai LiPo nunchuck. FPS stabil di kisaran 380-400 FPS (0.20g BB), sangat akurat untuk skirmish jarak menengah hingga jauh.',
            'payment_methods' => ['Transfer Bank', 'QRIS'],
        ],
        [
            'gambar' => 'Product/G.S.P3.jpg',
            'nama' => 'SLR Rifleworks M4 Custom Build + ACOG Scope',
            'merk' => 'Custom Build',
            'jenis' => 'Unit',
            'kondisi' => 'Sangat baik',
            'harga' => 3450000,
            'stok' => 2,
            'lokasi' => 'Surabaya',
            'deskripsi' => 'Unit rakitan custom basis SLR Rifleworks M4 dengan performa tinggi. Sudah terpasang scope ACOG 4x32 pembesaran jernih, mock suppressor / tracer unit silencer di ujung laras, dan popor CTR minimalist. Upgrade internal mencakup hop-up chamber CNC, laras presisi tightbore 6.02mm, serta mosfet internal untuk respon trigger instan. Tembakan sangat rapat dan konsisten di 410 FPS. Siap pakai untuk kompetisi speedsoft maupun milsim.',
            'payment_methods' => ['Transfer Bank', 'QRIS'],
        ],
        [
            'gambar' => 'Product/G.S.P4.jpg',
            'nama' => 'SIG 556 Tactical Two-Tone Custom Airsoft',
            'merk' => "D'Cobra Custom",
            'jenis' => 'Unit',
            'kondisi' => 'Baik',
            'harga' => 850000,
            'stok' => 4,
            'lokasi' => 'Yogyakarta',
            'deskripsi' => 'Unit airsoft model SIG 556 dengan kustomisasi cat two-tone elegan (kombinasi bodi abu-abu metalik dan aksen hitam matte). Dilengkapi dengan red dot optic sight pada rail atas untuk bidikan cepat, serta handguard ber-rail untuk memasang aksesoris grip atau senter. Sistem kokang sudah diperkuat (upgrade spring & kompresi) sehingga memiliki jangkauan tembak yang lebih jauh dan bertenaga. Cocok untuk koleksi dan latihan target.',
            'payment_methods' => ['Transfer Bank', 'COD'],
        ],
        [
            'gambar' => 'Product/G.S.P5.jpg',
            'nama' => 'Handle Kokang Metal Hybrid-X Dcobra MAK47L',
            'merk' => 'RCW Engineering',
            'jenis' => 'Sparepart',
            'kondisi' => 'Baru',
            'harga' => 125000,
            'stok' => 25,
            'lokasi' => 'Malang',
            'deskripsi' => "Upgrade handle kokang bahan metal CNC tipe Hybrid-X khusus untuk unit spring MAK47L / AK47 D'Cobra. Dibuat dari material aluminium dural tebal yang presisi dan anti patah, menggantikan kokangan plastik bawaan pabrik yang rentan rusak. Pemasangan plug-and-play (PnP) tanpa perlu modifikasi bodi yang rumit. Menjadikan proses kokang jauh lebih mantap, kuat saat menggunakan per upgrade berdaya tinggi.",
            'payment_methods' => ['Transfer Bank', 'QRIS'],
        ],
        [
            'gambar' => 'Product/G.S.P6.jpg',
            'nama' => 'Maple Leaf Hop Up Chamber Set for 1911 GBB',
            'merk' => 'Maple Leaf',
            'jenis' => 'Sparepart',
            'kondisi' => 'Baru',
            'harga' => 320000,
            'stok' => 10,
            'lokasi' => 'Depok',
            'deskripsi' => 'Chamber Hop-Up presisi tinggi buatan Maple Leaf khusus untuk unit pistol Gas Blowback (GBB) seri 1911 (kompatibel dengan Tokyo Marui, KJW, dan WE). Terbuat dari konstruksi logam die-cast berkualitas dengan roda penyetelan hop-up mikro yang halus. Meningkatkan akurasi, efisiensi kompresi gas, dan kestabilan putaran BB secara signifikan. Dilengkapi dengan I-Key hop up arm untuk penekanan karet hop-up yang lebih merata.',
            'payment_methods' => ['Transfer Bank', 'QRIS'],
        ],
    ];

    public function definition(): array
    {
        $item = fake()->randomElement(self::$catalog);

        return [
            'id_user' => User::factory(),
            'id_marketplace' => Marketplace::factory(),
            'nama' => $item['nama'],
            'harga' => $item['harga'],
            'merk' => $item['merk'],
            'jenis' => $item['jenis'],
            'kondisi' => $item['kondisi'],
            'stok' => $item['stok'],
            'lokasi' => $item['lokasi'],
            'deskripsi' => $item['deskripsi'],
            'gambar' => $item['gambar'],
            'payment_methods' => $item['payment_methods'],
        ];
    }

    /**
     * State untuk memilih produk tertentu berdasarkan indeks katalog (0 - 5)
     */
    public function item(int $index): static
    {
        $item = self::$catalog[$index % count(self::$catalog)];

        return $this->state(fn () => [
            'nama' => $item['nama'],
            'harga' => $item['harga'],
            'merk' => $item['merk'],
            'jenis' => $item['jenis'],
            'kondisi' => $item['kondisi'],
            'stok' => $item['stok'],
            'lokasi' => $item['lokasi'],
            'deskripsi' => $item['deskripsi'],
            'gambar' => $item['gambar'],
            'payment_methods' => $item['payment_methods'],
        ]);
    }
}
