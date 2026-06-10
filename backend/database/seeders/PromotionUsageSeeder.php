<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PromotionUsage;

class PromotionUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PromotionUsage::create([
            'user_id' => 2,
            'promotion_id' => 1,
            'discount_amount' => 100000,
            'used_at' => now(),
            'trip_id' => 1
        ]);
        PromotionUsage::create([
            'user_id' => 3,
            'promotion_id' => 2,
            'discount_amount' => 50000,
            'used_at' => now(),
            'trip_id' => 2
        ]);
        PromotionUsage::create([
            'user_id' => 4,
            'promotion_id' => 3,
            'discount_amount' => 200000,
            'used_at' => now(),
            'trip_id' => 3
        ]);
    }
}
