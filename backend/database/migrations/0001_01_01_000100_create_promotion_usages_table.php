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
        Schema::create('promotion_usages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('ID của người dùng');
            $table->unsignedBigInteger('promotion_id')->comment('ID của khuyến mãi');
            $table->decimal('discount_amount', 15, 2)->comment('Số tiền được giảm');
            $table->dateTime('used_at')->comment('Thời gian sử dụng khuyến mãi');
            $table->unsignedBigInteger('trip_id')->nullable()->comment('ID của chuyến đi nếu có');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');
    }
};
