<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarType;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //brand id 1: Toyota - brand id 2: Honda - brand id 3: Ford
        CarType::create([
            'type_name' => 'TOYOTA VENZA',
            'car_brand_id' => 1,
        ]);
        CarType::create([
            'type_name' => 'TOYOTA RAV4',
            'car_brand_id' => 1,
        ]);
        CarType::create([
            'type_name' => 'HONDA CR-V',
            'car_brand_id' => 2,
        ]);
        CarType::create([
            'type_name' => 'HONDA CIVIC',
            'car_brand_id' => 2,
        ]);
        CarType::create([
            'type_name' => 'FORD ESCAPE',
            'car_brand_id' => 3,
        ]);
        CarType::create([
            'type_name' => 'FORD F-150',
            'car_brand_id' => 3,
        ]);
    }
}
