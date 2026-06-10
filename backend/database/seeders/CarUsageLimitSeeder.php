<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarUsageLimit;

class CarUsageLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CarUsageLimit::create([
            'max_daily_distance' => 100,
            'extra_distance_fee' => 50000,
            'status' => 1
        ]);
        CarUsageLimit::create([
            'max_daily_distance' => 200,
            'extra_distance_fee' => 100000,
            'status' => 1
        ]);
        CarUsageLimit::create([
            'max_daily_distance' => 300,
            'extra_distance_fee' => 150000,
            'status' => 1
        ]);
        CarUsageLimit::create([
            'max_daily_distance' => 400,
            'extra_distance_fee' => 200000,
            'status' => 1
        ]);
    }
}
