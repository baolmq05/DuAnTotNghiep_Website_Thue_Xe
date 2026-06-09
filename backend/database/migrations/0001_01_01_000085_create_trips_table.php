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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->decimal('cost', 10, 2)->comment('chi phí chuyến đi')->unsigned();
            $table->decimal('discount_amount', 10, 2)->comment('số tiền giảm giá')->unsigned()->default(0);
            $table->tinyInteger('status')->comment('trạng thái chuyến đi: 0 - chưa bắt đầu, 1 - đang diễn ra, 2 - đã hoàn thành, 3 - đã hủy bởi người dùng, 4 - đã hủy bởi chủ xe')->default(0);
            $table->tinyInteger('trip_type')->comment('loại chuyến đi: 0 - thuê theo ngày, 1 - thuê theo km')->default(0);
            $table->dateTime('start_at')->comment('thời gian bắt đầu chuyến đi');
            $table->dateTime('end_at')->comment('thời gian kết thúc chuyến đi');
            $table->unsignedBigInteger('car_id')->comment('mã xe');
            $table->unsignedBigInteger('user_id')->comment('mã người dùng thuê xe');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
