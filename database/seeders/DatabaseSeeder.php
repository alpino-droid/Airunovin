<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinsiSeeder::class,
        ]);

        User::factory()->create([
            'username' => 'testuser',
            'nama' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Buat beberapa user tambahan untuk testing
        User::factory(5)->create();
    }
}
