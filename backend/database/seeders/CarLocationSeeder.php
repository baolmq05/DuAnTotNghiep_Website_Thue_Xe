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
            'province_id' => 1,
            'ward_code' => 1,
            'street_name' => 'Đường Lê Duẩn',
        ]);
        CarLocation::create([
            'province_id' => 2,
            'ward_code' => 2,
            'street_name' => 'Đường Nguyễn Huệ',
        ]);
        CarLocation::create([
            'province_id' => 3,
            'ward_code' => 3,
            'street_name' => 'Đường Trần Hưng Đạo',
        ]);
    }
}
