<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $nama = [
            'admin',
            'User 1',
            'User 2',
            'User 3',
            'User 4',
            'User 5'
        ];
        static $phone = [
            '081234567890',
            '081234567891',
            '081234567892',
            '081234567893',
            '081234567894'
        ];
        static $city = [
            'Bandung',
            'Semarang',
            'Surabaya',
            'Denpasar',
            'Medan'
        ];
        return [
            'username' => fake()->unique()->username(),
            'nama' => fake()->randomElement($nama),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => fake()->randomElement($phone),
            'id_provinsi' => fake()->randomElement($provinsiIds ??= \App\Models\Provinsi::query()->pluck('id')->all()),
            'city' => fake()->randomElement($city),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
