<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use App\Models\User;
use Illuminate\Database\Seeder;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Provinsi::count() === 0) {
            $this->call(ProvinsiSeeder::class);
        }

        $provinsiIds = Provinsi::query()->pluck('id')->all();
        $defaultProvinsiId = $provinsiIds[0] ?? 1;

        User::factory(5)->create();
    }
}
