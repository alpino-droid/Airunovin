<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\event;
use Database\Factories\eventFactory;

class eventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        event::query()->delete();

        foreach (eventFactory::posterEvents() as $eventData) {
            event::factory()->create($eventData);
        }
    }
}
