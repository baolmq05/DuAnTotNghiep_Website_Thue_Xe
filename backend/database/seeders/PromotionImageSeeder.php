<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PromotionImage;

class PromotionImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PromotionImage::create([
            'promotion_id' => 1,
            'image_url' => 'https://res.cloudinary.com/djbobb5oe/image/upload/v1782706265/e066d0fb-d713-4aac-a05d-c0c022530795_vk9zcj.jpg'
        ]);
        PromotionImage::create([
            'promotion_id' => 2,
            'image_url' => 'https://res.cloudinary.com/djbobb5oe/image/upload/v1782706265/e066d0fb-d713-4aac-a05d-c0c022530795_vk9zcj.jpg'
        ]);
        PromotionImage::create([
            'promotion_id' => 3,
            'image_url' => 'https://res.cloudinary.com/djbobb5oe/image/upload/v1782706265/e066d0fb-d713-4aac-a05d-c0c022530795_vk9zcj.jpg'
        ]);
        PromotionImage::create([
            'promotion_id' => 4,
            'image_url' => 'https://res.cloudinary.com/djbobb5oe/image/upload/v1782706265/e066d0fb-d713-4aac-a05d-c0c022530795_vk9zcj.jpg'
        ]);
    }
}
