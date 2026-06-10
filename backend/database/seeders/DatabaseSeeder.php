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
            CarLocationSeeder::class,
            CarBrandSeeder::class,
            CarTypeSeeder::class,
            CarDeliveryOptionSeeder::class,
            CarUsageLimitSeeder::class,
            CarSeeder::class,
        ]);
    }
}
