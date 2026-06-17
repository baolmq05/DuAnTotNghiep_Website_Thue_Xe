<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Post::create([
            'title' => 'Bài viết mẫu',
            'slug' => 'bai-viet-mau',
            'excerpt' => 'Đây là mô tả ngắn của bài viết mẫu.',
            'content' => 'Đây là nội dung chi tiết của bài viết mẫu. Bạn có thể thêm nhiều thông tin hơn ở đây.',
            'thumbnail' => null,
            'user_id' => 1, // Giả sử user_id 1 tồn tại
            'post_category_id' => 1, // Giả sử post_category_id 1 tồn tại
            'status' => 1,
            'type' => 'post',
            'published_at' => now(),
        ]);
        Post::create([
            'title' => 'Bài viết khuyến mãi',
            'slug' => 'bai-viet-khuyen-mai',
            'excerpt' => 'Đây là mô tả ngắn của bài viết khuyến mãi.',
            'content' => 'Đây là nội dung chi tiết của bài viết khuyến mãi. Bạn có thể thêm nhiều thông tin hơn ở đây.',
            'thumbnail' => null,
            'user_id' => 1, // Giả sử user_id 1 tồn tại
            'post_category_id' => 2, // Giả sử post_category_id 2 tồn tại
            'status' => 1,
            'type' => 'post',
            'published_at' => now(),
        ]);
         Post::create([
            'title' => 'Bài viết hướng dẫn',
            'slug' => 'bai-viet-huong-dan',
            'excerpt' => 'Đây là mô tả ngắn của bài viết hướng dẫn.',
            'content' => 'Đây là nội dung chi tiết của bài viết hướng dẫn.  Bạn có thể thêm nhiều thông tin hơn ở đây.',
            'thumbnail' => null,
            'user_id' => 1, // Giả sử user_id 1 tồn tại
            'post_category_id' => 3, // Giả sử post_category_id 3 tồn tại
            'status' => 1,
            'type' => 'post',
            'published_at' => now(),
        ]);
        Post::create([
            'title' => 'Bài viết hướng dẫn',
            'slug' => 'bai-viet-huong-dan',
            'excerpt' => 'Đây là mô tả ngắn của bài viết hướng dẫn.',
            'content' => 'Đây là nội dung chi tiết của bài viết hướng dẫn.  Bạn có thể thêm nhiều thông tin hơn ở đây.',
            'thumbnail' => null,
            'user_id' => 1, // Giả sử user_id 1 tồn tại
            'post_category_id' => 3, // Giả sử post_category_id 3 tồn tại
            'status' => 1,
            'type' => 'post',
            'published_at' => now(),
        ]);

    }
}
