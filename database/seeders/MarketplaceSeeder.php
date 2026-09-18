<?php

namespace Database\Seeders;

use App\Models\Marketplace;
use App\Models\User;
use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->take(5)->get();

        foreach ($users as $user) {
            Marketplace::updateOrCreate(
                ['id_user' => $user->id],
                [
                    'nama' => 'Marketplace ' . ($user->nama ?: $user->username),
                    'deskripsi' => 'Toko perlengkapan airsoft milik ' . ($user->nama ?: $user->username) . '.',
                    'status' => 'active',
                ]
            );
        }
    }
}
