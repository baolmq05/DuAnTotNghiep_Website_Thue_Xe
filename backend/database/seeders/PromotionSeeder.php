<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::create([
            'code' => 'PROMO10',
            'name' => 'Giảm giá 10%',
            'description' => 'Giảm giá 10% cho tất cả các đơn hàng',
            'discount_type' => '0',
            'discount_value' => 10,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'usage_limit' => 100,
            'per_user_limit' => 1,
            'status' => '1',
            'user_id' => 1
        ]);
        Promotion::create([
            'code' => 'PROMO50K',
            'name' => 'Giảm giá 50.000đ',
            'description' => 'Giảm giá 50.000đ cho đơn hàng từ 500.000đ trở lên',
            'discount_type' => '1',
            'discount_value' => 50000,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'usage_limit' => 50,
            'per_user_limit' => 2,
            'status' => '1',
            'user_id' => 1
        ]);
        Promotion::create([
            'code' => 'PROMO20',
            'name' => 'Giảm giá 20%',
            'description' => 'Giảm giá 20% cho đơn hàng từ 1.000.000đ trở lên',
            'discount_type' => '0',
            'discount_value' => 20,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'usage_limit' => 30,
            'per_user_limit' => 1,
            'status' => '1',
            'user_id' => 1
        ]);
        Promotion::create([
            'code'=> 'PROVIP100',
            'name'=> 'Giảm giá 100.000đ',
            'description'=> 'Giảm giá 100.000đ cho đơn hàng từ 1.000.000đ trở lên',
            'discount_type' => '1',
            'discount_value' => 100000,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'usage_limit' => 20,
            'per_user_limit' => 1,
            'status' => '1',
            'user_id' => 1
        ]);
    }
}
