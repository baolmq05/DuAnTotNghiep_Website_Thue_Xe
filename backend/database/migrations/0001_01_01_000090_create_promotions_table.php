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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Mã khuyến mãi');
            $table->string('name')->comment('Tên khuyến mãi');
            $table->text('description')->comment('Mô tả khuyến mãi');
            $table->enum('discount_type', [0, 1])->comment('Loại giảm giá 0: phần trăm, 1: số tiền');
            $table->integer('discount_value')->comment('Giá trị giảm giá');
            $table->date('start_date')->comment('Ngày bắt đầu');
            $table->date('end_date')->comment('Ngày kết thúc');
            $table->integer('usage_limit')->nullable()->comment('Giới hạn số lần sử dụng');
            $table->integer('per_user_limit')->nullable()->comment('Giới hạn số lần sử dụng cho mỗi người dùng');
            $table->enum('status', [0, 1])->default(1)->comment('Trạng thái khuyến mãi 0: không hoạt động, 1: hoạt động');
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID của người dùng tạo khuyến mãi');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
