<?php

namespace Database\Factories;

use App\Models\Marketplace;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Marketplace>
 */
class MarketplaceFactory extends Factory
{
    protected $model = Marketplace::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'nama' => fake()->company() . ' Marketplace',
            'deskripsi' => fake()->sentence(12),
            'logo' => null,
            'status' => 'active',
        ];
    }
}
