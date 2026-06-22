<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarDeliveryOption;

class CarDeliveryOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CarDeliveryOption::create([
            'max_distance' => 10,
            'fee_distance' => 50000,
            'free_distance' => 5,
            'status' => 1
        ]);
        CarDeliveryOption::create([
            'max_distance' => 20,
            'fee_distance' => 100000,
            'free_distance' => 10,
            'status' => 1
        ]);

        CarDeliveryOption::create([
            'max_distance' => 30,
            'fee_distance' => 150000,
            'free_distance' => 15,
            'status' => 1
        ]);
        CarDeliveryOption::create([
            'max_distance' => 40,
            'fee_distance' => 200000,
            'free_distance' => 20,
            'status' => 1
        ]);
        CarDeliveryOption::create([
            'max_distance' => 50,
            'fee_distance' => 250000,
            'free_distance' => 25,
            'status' => 1
        ]);
    }
}
