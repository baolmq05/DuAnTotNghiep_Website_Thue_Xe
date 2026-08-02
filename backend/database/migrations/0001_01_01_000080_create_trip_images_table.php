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
        Schema::create('trip_images', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_thumbnail')->comment('0 - không phải ảnh đại diện, 1 - ảnh đại diện')->default(0);
            $table->text('image_url')->comment('đường dẫn hình ảnh');
            $table->tinyInteger('type')->comment('0 - trước chuyến đi, 1 - sau khi chuyến đi');
            $table->unsignedBigInteger('trip_id')->comment('mã chuyến đi');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_images');
    }
};
