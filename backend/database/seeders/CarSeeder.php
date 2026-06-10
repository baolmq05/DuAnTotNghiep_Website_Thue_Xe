<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Car::create([
            'name' => 'Toyota Camry',
            'license_plate' => '30A-12345',
            'fuel_consumption' => 8,
            'unit_price' => 1000000,
            'discount_value' => 100000,
            'description' => 'Xe sang trọng, tiện nghi, phù hợp cho các chuyến đi dài ngày.',
            'rental_terms' => 'Không hút thuốc, không chở vật nuôi, trả xe đúng giờ.',
            'car_location_id' => 1,
            'car_brand_id' => 1,
            'car_type_id' => 1,
            'seat_count' => 5,
            'manufacture_year' => '2020-01-01',
            'fuel_type' => 'Xăng',
            'transmission' => 'Tự động',
            'user_id' => 1,
            'delivery_option_id' => 1,
            'usage_limit_id' => 1
        ]);
        Car::create([
            'name' => 'Honda Civic',
            'license_plate' => '30B-54321',
            'fuel_consumption' => 7,
            'unit_price' => 900000,
            'discount_value' => 50000,
            'description' => 'Xe thể thao, năng động, phù hợp cho các chuyến đi trong thành phố.',
            'rental_terms' => 'Không hút thuốc, không chở vật nuôi, trả xe đúng giờ.',
            'car_location_id' => 1,
            'car_brand_id' => 2,
            'car_type_id' => 1,
            'seat_count' => 5,
            'manufacture_year' => '2019-01-01',
            'fuel_type' => 'Xăng',
            'transmission' => 'Tự động',
            'user_id' => 1,
            'delivery_option_id' => 1,
            'usage_limit_id' => 1
        ]);
        Car::create([
            'name' => 'Toyota Wigo',
            'license_plate' => '30C-67890',
            'fuel_consumption' => 6,
            'unit_price' => 700000,
            'discount_value' => 30000,
            'description' => 'Xe nhỏ gọn, tiết kiệm nhiên liệu, phù hợp cho các chuyến đi trong thành phố.',
            'rental_terms' => 'Không hút thuốc, không chở vật nuôi, trả xe đúng giờ.',
            'car_location_id' => 1,
            'car_brand_id' => 1,
            'car_type_id' => 2,
            'seat_count' => 5,
            'manufacture_year' => '2021-01-01',
            'fuel_type' => 'Xăng',
            'transmission' => 'Tự động',
            'user_id' => 1,
            'delivery_option_id' => 1,
            'usage_limit_id' => 1
        ]);
        Car::create([
            'name' => 'Toyota Corolla Cross',
            'license_plate' => '30D-98765',
            'fuel_consumption' => 7,
            'unit_price' => 800000,
            'discount_value' => 40000,
            'description' => 'Xe SUV, rộng rãi, phù hợp cho các chuyến đi gia đình hoặc nhóm bạn.',
            'rental_terms' => 'Không hút thuốc, không chở vật nuôi, trả xe đúng giờ.',
            'car_location_id' => 1,
            'car_brand_id' => 1,
            'car_type_id' => 3,
            'seat_count' => 5,
            'manufacture_year' => '2022-01-01',
            'fuel_type' => 'Xăng',
            'transmission' => 'Tự động',
            'user_id' => 1,
            'delivery_option_id' => 1,
            'usage_limit_id' => 1
        ]);
    }
}
