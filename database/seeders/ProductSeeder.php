<?php

namespace Database\Seeders;

use App\Models\Marketplace;
use App\Models\Product;
use App\Models\User;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Salin berkas gambar dari public/img/imgStatik/GambarProduk ke storage publik
        $sourceDir = public_path('img/imgStatik/GambarProduk');
        $targetDirProduct = storage_path('app/public/Product');
        $targetDirStatik = storage_path('app/public/imgStatik/GambarProduk');

        if (File::isDirectory($sourceDir)) {
            File::ensureDirectoryExists($targetDirProduct);
            File::ensureDirectoryExists($targetDirStatik);

            foreach (File::files($sourceDir) as $file) {
                File::copy($file->getPathname(), $targetDirProduct . DIRECTORY_SEPARATOR . $file->getFilename());
                File::copy($file->getPathname(), $targetDirStatik . DIRECTORY_SEPARATOR . $file->getFilename());
            }
        }

        // 2. Bersihkan produk lama sebelum seeding
        Product::query()->delete();

        // 3. Pastikan minimal ada 1 Marketplace & User untuk mengaitkan produk
        $marketplaces = Marketplace::with('user')->get();

        if ($marketplaces->isEmpty()) {
            $user = User::first() ?? User::create([
                'nama' => 'Admin Marketplace',
                'username' => 'airsoft_admin',
                'email' => 'admin.market@airunovin.com',
                'password' => bcrypt('password'),
                'phone' => '081234567890',
                'province' => 'Jawa Barat',
            ]);

            $marketplace = Marketplace::create([
                'id_user' => $user->id,
                'nama' => 'Airunovin Tactical Hub',
                'deskripsi' => 'Pusat jual beli unit airsoft, sparepart upgrade, dan perlengkapan taktis.',
                'status' => 'active',
            ]);

            $marketplaces = collect([$marketplace]);
        }

        $marketplaceCount = $marketplaces->count();

        // 4. Buat produk untuk setiap gambar di katalog lengkap dengan deskripsi yang sesuai
        foreach (ProductFactory::$catalog as $index => $item) {
            $marketplace = $marketplaces[$index % $marketplaceCount];

            Product::create([
                'id_user' => $marketplace->id_user,
                'id_marketplace' => $marketplace->id,
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
}
