<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PostCategory;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        PostCategory::create([
            'name' => 'Tin tức',
            'status' => 1,
        ]);
        PostCategory::create([
            'name' => 'Khuyến mãi',
            'status' => 1,
        ]);
        PostCategory::create([
            'name' => 'Hướng dẫn',
            'status' => 1,
        ]);
        PostCategory::create([
            'name' => 'Sự kiện',
            'status' => 1,
        ]);
        PostCategory::create([
            'name' => 'Khác',
            'status' => 1,
        ]);
    }
}
