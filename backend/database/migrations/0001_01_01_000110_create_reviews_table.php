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
        Schema::create('reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('trip_id')->comment('mã chuyến đi');
            $table->unsignedBigInteger('reviewer_id')->comment('mã người đánh giá');
            $table->unsignedBigInteger('target_id')->comment('mã người được đánh giá');
            $table->unsignedBigInteger('car_id')->comment('mã xe');
            $table->tinyInteger('rating')->comment('đánh giá sao từ 1 đến 5');
            $table->text('comment')->comment('bình luận đánh giá')->nullable();
            $table->tinyInteger('review_type')->comment('loại đánh giá: 0 - đánh giá người thuê, 1 - đánh giá người cho thuê');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
