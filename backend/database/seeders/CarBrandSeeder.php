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
$brands = [
    'Acura', 'Audi', 'Baic', 'Bentley', 'BMW',
    'Brilliance', 'BYD', 'Changan', 'Chevrolet', 'Chrysler',
    'Daewoo', 'Daihatsu', 'Dongfeng', 'Fairy', 'Fiat',
    'Ford', 'GAC', 'Geely', 'Haima', 'Haval',
    'Honda', 'Hyundai', 'Isuzu', 'JaeCoo', 'Jaguar',
    'Kenbo', 'Kia', 'Land Rover', 'Lexus', 'Luxgen',
    'Lynk & Co', 'Mazda', 'Mercedes', 'Mitsubishi', 'Morris Garages',
    'Nissan', 'Omoda', 'Peugeot', 'Porsche', 'Renault',
    'Riich', 'Samsung', 'Skoda', 'SsangYong', 'Subaru',
    'Suzuki', 'Tobe', 'Toyota', 'UAZ', 'Vinfast',
    'Volkswagen', 'Volvo', 'Wuling', 'Zotye'
];

foreach ($brands as $brand) {
    CarBrand::create([
        'brand_name' => $brand,
    ]);
}; 
    }
}
