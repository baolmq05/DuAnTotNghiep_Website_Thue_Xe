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
        Schema::create('car_images', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->tinyInteger('is_thumbnail')->comment('0 - không phải ảnh đại diện, 1 - ảnh đại diện')->default(0);
            $table->text('image_url')->comment('đường dẫn hình ảnh');
            $table->unsignedBigInteger('car_id')->comment('mã xe');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_images');
    }
};
