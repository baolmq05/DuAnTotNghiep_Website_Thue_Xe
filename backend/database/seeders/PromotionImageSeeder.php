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
            'image_url' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1783153605/z8006884307236_135cff544e4f013d18015dc69071ba52_bzswc3.jpg'
        ]);
        PromotionImage::create([
            'promotion_id' => 2,
            'image_url' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1783153605/z8006884269175_9b27d8cc121d2d60a2208ef64f41ec93_twggmx.jpg'
        ]);
        PromotionImage::create([
            'promotion_id' => 3,
            'image_url' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1783153605/z8006884925560_9d06b15e0252024ce72013e5f13adf06_zpxfge.jpg'
        ]);
        PromotionImage::create([
            'promotion_id' => 4,
            'image_url' => 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1783153605/z8006887853933_6d3eed6000f370adcab1c18c0863d4fe_xfpd43.jpg'
        ]);
    }
}
