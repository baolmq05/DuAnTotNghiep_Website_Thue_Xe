<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarLocation;

class CarLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CarLocation::create([
            'location' => '10.958293,106.593036',
            'address' => 'Tân Thạnh Đông, Củ Chi, Hồ Chí Minh',
        ]);
        CarLocation::create([
            'location' => '20.958293,106.593036',
            'address' => 'Thanh Trì, Hà Nội',
        ]);
        CarLocation::create([
            'location' => '16.058293,108.234543',
            'address' => 'Lê Duẩn, Hải Châu, Đà Nẵng',
        ]);
    }
}
