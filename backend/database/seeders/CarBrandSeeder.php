<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarBrand;

class CarBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CarBrand::create([
            'brand_name' => 'Toyota',
        ]);
        CarBrand::create([
            'brand_name' => 'Honda',
        ]);
        CarBrand::create([
            'brand_name' => 'Ford',
        ]);
        CarBrand::create([
            'brand_name' => 'Chevrolet',
        ]);
        CarBrand::create([
            'brand_name' => 'Nissan',
        ]);
        CarBrand::create([
            'brand_name' => 'Hyundai',
        ]);
        CarBrand::create([
            'brand_name' => 'Kia',
        ]);
        CarBrand::create([
            'brand_name' => 'Mazda',
        ]);
        CarBrand::create([
            'brand_name' => 'Subaru',
        ]);
        CarBrand::create([
            'brand_name' => 'Volkswagen',
        ]);
        CarBrand::create([
            'brand_name' => 'Mercedes-Benz',
        ]);
        CarBrand::create([
            'brand_name' => 'BMW',
        ]);
        CarBrand::create([
            'brand_name' => 'Audi',
        ]);
        CarBrand::create([
            'brand_name' => 'Lexus',
        ]);
        CarBrand::create([
            'brand_name' => 'Infiniti',
        ]);
    }
}
