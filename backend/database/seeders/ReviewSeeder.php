<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Review::create([
            'trip_id' => 1,
            'reviewer_id' => 2,
            'target_id' => 1,
            'car_id' => 1,
            'rating' => 5,
            'comment' => 'Xe rất tốt, dịch vụ tuyệt vời!',
            'review_type' => 0
        ]);
        Review::create([
            'trip_id' => 2,
            'reviewer_id' => 3,
            'target_id' => 2,
            'car_id' => 2,
            'rating' => 4,
            'comment' => 'Xe ổn, nhưng có thể cải thiện dịch vụ.',
            'review_type' => 0
        ]);
        Review::create([
            'trip_id' => 3,
            'reviewer_id' => 4,
            'target_id' => 3,
            'car_id' => 3,
            'rating' => 3,
            'comment' => 'Xe bình thường, không có gì đặc biệt.',
            'review_type' => 0
        ]);
        Review::create([
            'trip_id' => 1,
            'reviewer_id' => 1,
            'target_id' => 2,
            'car_id' => 1,
            'rating' => 5,
            'comment' => 'Khách hàng rất dễ thương, hy vọng được phục vụ lần sau!',
            'review_type' => 1
        ]);
        Review::create([
            'trip_id' => 2,
            'reviewer_id' => 2,
            'target_id' => 3,
            'car_id' => 2,
            'rating' => 4,
            'comment' => 'Khách hàng lịch sự, mong được hợp tác lần sau.',
            'review_type' => 1
        ]);
        Review::create([
            'trip_id' => 3,
            'reviewer_id' => 3,
            'target_id' => 4,
            'car_id' => 3,
            'rating' => 3,
            'comment' => 'Khách hàng bình thường, không có gì đặc biệt.',
            'review_type' => 1
        ]);
    }
}
