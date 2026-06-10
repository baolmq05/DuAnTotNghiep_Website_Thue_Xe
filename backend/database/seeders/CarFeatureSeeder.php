<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarFeature;

class CarFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 1
        ]);
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 2
        ]);
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 3
        ]);
        CarFeature::create([    
            'car_id' => 1,
            'feature_id' => 4
        ]);
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 5
        ]);
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 6
        ]);
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 7
        ]);
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 8
        ]);
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 9
        ]);
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 10
        ]);
        CarFeature::create([
            'car_id' => 1,
            'feature_id' => 11
        ]);
        CarFeature::create([
            'car_id' => 2,
            'feature_id' => 1
        ]);
        CarFeature::create([
            'car_id' => 2,
            'feature_id' => 2
        ]);
        CarFeature::create([
            'car_id' => 2,
            'feature_id' => 3
        ]);
        CarFeature::create([    
            'car_id' => 2,
            'feature_id' => 4
        ]);
        CarFeature::create([
            'car_id' => 2,
            'feature_id' => 5
        ]);
        CarFeature::create([
            'car_id' => 3,
            'feature_id' => 1
        ]);
        CarFeature::create([
            'car_id' => 3,
            'feature_id' => 2
        ]);
        CarFeature::create([
            'car_id' => 3,
            'feature_id' => 3
        ]);
        CarFeature::create([    
            'car_id' => 3,
            'feature_id' => 4
        ]);
        CarFeature::create([
            'car_id' => 3,
            'feature_id' => 5
        ]);
        CarFeature::create([
            'car_id' => 4,
            'feature_id' => 1
        ]);
        CarFeature::create([
            'car_id' => 4,
            'feature_id' => 2
        ]);
        CarFeature::create([
            'car_id' => 4,
            'feature_id' => 3
        ]);
    }
}
