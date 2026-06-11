<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('title')->comment('Tiêu đề bài viết');
            $table->text('slug')->comment('Slug bài viết');
            $table->text('excerpt')->comment('Mô tả ngắn bài viết');
            $table->longText('content')->comment('Nội dung bài viết');
            $table->string('thumbnail')->nullable()->comment('Ảnh đại diện bài viết');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_category_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('status')->default(1)->comment('Trạng thái bài viết');
            $table->string('type')->default('post')->comment('Loại bài viết');
            $table->timestamp('published_at')->nullable()->comment('Thời gian xuất bản');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
