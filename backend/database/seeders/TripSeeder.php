<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trip;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Trip::create([
            'cost' => 500000,
            'discount_amount' => 50000,
            'status' => 2,
            'trip_type' => 0,
            'start_at' => '2024-01-01 08:00:00',
            'end_at' => '2024-01-03 18:00:00',
            'car_id' => 1,
            'user_id' => 2,
            'delivery_address'=> '123 Main St',
            'delivery_location'=> '10.958293,106.593036',
        ]);
        Trip::create([
            'cost' => 300000,
            'discount_amount' => 30000,
            'status' => 1,
            'trip_type' => 1,
            'start_at' => '2024-02-01 09:00:00',
            'end_at' => '2024-02-01 17:00:00',
            'car_id' => 2,
            'user_id' => 3,
            'delivery_address'=> '123 Main St',
            'delivery_location'=> '10.958293,106.593036',
        ]);
        Trip::create([
            'cost' => 200000,
            'discount_amount' => 20000,
            'status' => 0,
            'trip_type' => 0,
            'start_at' => '2024-03-01 10:00:00',
            'end_at' => '2024-03-02 16:00:00',
            'car_id' => 3,
            'user_id' => 4,
            'delivery_address'=> '123 Main St',
            'delivery_location'=> '10.958293,106.593036',
        ]);
        Trip::create([
            'cost' => 1000000,
            'discount_amount' => 100000,
            'status' => 1, // Busy trip for Camry
            'trip_type' => 0,
            'start_at' => '2026-06-26 08:00:00',
            'end_at' => '2026-06-28 20:00:00',
            'car_id' => 1,
            'user_id' => 2,
            'delivery_address'=> '123 Main St',
            'delivery_location'=> '10.958293,106.593036',
        ]);
        Trip::create([
            'cost' => 900000,
            'discount_amount' => 50000,
            'status' => 0, // Busy trip for Civic
            'trip_type' => 0,
            'start_at' => '2026-06-29 08:00:00',
            'end_at' => '2026-07-02 20:00:00',
            'car_id' => 3,
            'user_id' => 2,
            'delivery_address'=> '123 Main St',
            'delivery_location'=> '10.958293,106.593036',
        ]);
    }
}
