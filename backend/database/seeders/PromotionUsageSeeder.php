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
        $trips = \App\Models\Trip::all();
        $promotions = \App\Models\Promotion::all();
        $users = \App\Models\User::all();

        if ($trips->count() >= 3 && $promotions->count() >= 3) {
            PromotionUsage::create([
                'user_id' => $users->skip(1)->first()?->id ?? 2,
                'promotion_id' => $promotions->first()?->id ?? 1,
                'discount_amount' => 100000,
                'used_at' => now(),
                'trip_id' => $trips->first()?->id
            ]);
            PromotionUsage::create([
                'user_id' => $users->skip(2)->first()?->id ?? 3,
                'promotion_id' => $promotions->skip(1)->first()?->id ?? 2,
                'discount_amount' => 50000,
                'used_at' => now(),
                'trip_id' => $trips->skip(1)->first()?->id
            ]);
            PromotionUsage::create([
                'user_id' => $users->skip(3)->first()?->id ?? 4,
                'promotion_id' => $promotions->skip(2)->first()?->id ?? 3,
                'discount_amount' => 200000,
                'used_at' => now(),
                'trip_id' => $trips->skip(2)->first()?->id
            ]);
        }
    }
}
