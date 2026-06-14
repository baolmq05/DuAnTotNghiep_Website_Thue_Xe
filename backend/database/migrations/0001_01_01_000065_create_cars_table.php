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
        Schema::create('cars', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 255)->comment('tên xe');
            $table->string('license_plate', 12)->comment('biển số xe')->unique();
            $table->float('fuel_consumption', 10, 2)->comment('mức tiêu thụ nhiên liệu')->unsigned();
            $table->bigInteger('unit_price')->comment('đơn giá thuê xe')->unsigned();
            $table->bigInteger('discount_value')->comment('giá trị giảm giá')->unsigned()->default(0);
            $table->text('description')->comment('mô tả chi tiết về xe')->nullable();
            $table->text('rental_terms')->comment('điều khoản thuê xe')->nullable();
            $table->unsignedBigInteger('car_location_id')->comment('mã vị trí xe');
            $table->unsignedBigInteger('car_brand_id')->comment('mã thương hiệu xe');
            $table->unsignedBigInteger('car_type_id')->comment('mã loại xe');
            $table->decimal('seat_count', 2, 0)->comment('số chỗ ngồi')->unsigned();
            $table->date('manufacture_year')->comment('năm sản xuất');
            $table->string('fuel_type', 255)->comment('loại nhiên liệu');
            $table->string('transmission', 255)->comment('loại hộp số');
            $table->unsignedBigInteger('user_id')->comment('mã người dùng sở hữu xe');
            $table->unsignedBigInteger('delivery_option_id')->comment('mã tùy chọn giao xe');
            $table->unsignedBigInteger('usage_limit_id')->comment('mã giới hạn sử dụng');
            $table->tinyInteger('status')->comment('trạng thái xe, 0: dừng hoạt động, 1: đang hoạt động, 2:chờ duyệt, 3: bị từ chối')->default(2);
            $table->foreign('car_location_id')->references('id')->on('car_locations')->onDelete('cascade');
            $table->foreign('car_brand_id')->references('id')->on('car_brands')->onDelete('cascade');
            $table->foreign('car_type_id')->references('id')->on('car_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('delivery_option_id')->references('id')->on('car_delivery_options')->onDelete('cascade');
            $table->foreign('usage_limit_id')->references('id')->on('car_usage_limits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
