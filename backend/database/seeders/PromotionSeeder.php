<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //             $table->id();
            // $table->string('code')->unique()->comment('Mã khuyến mãi');
            // $table->string('name')->comment('Tên khuyến mãi');
            // $table->text('description')->comment('Mô tả khuyến mãi');
            // $table->enum('discount_type', [0, 1])->comment('Loại giảm giá 0: phần trăm, 1: số tiền');
            // $table->integer('discount_value')->comment('Giá trị giảm giá');
            // $table->date('start_date')->comment('Ngày bắt đầu');
            // $table->date('end_date')->comment('Ngày kết thúc');
            // $table->integer('usage_limit')->nullable()->comment('Giới hạn số lần sử dụng');
            // $table->integer('per_user_limit')->nullable()->comment('Giới hạn số lần sử dụng cho mỗi người dùng');
            // $table->enum('status', [0, 1])->default(1)->comment('Trạng thái khuyến mãi 0: không hoạt động, 1: hoạt động');
            // $table->unsignedBigInteger('user_id')->nullable()->comment('ID của người dùng tạo khuyến mãi');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // $table->timestamps();
        Promotion::create([
            'code' => 'PROMO10',
            'name' => 'Giảm giá 10%',
            'description' => 'Giảm giá 10% cho tất cả các đơn hàng',
            'discount_type' => '0',
            'discount_value' => 10,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'usage_limit' => 100,
            'per_user_limit' => 1,
            'status' => 1,
            'user_id' => 1
        ]);
        Promotion::create([
            'code' => 'PROMO50K',
            'name' => 'Giảm giá 50.000đ',
            'description' => 'Giảm giá 50.000đ cho đơn hàng từ 500.000đ trở lên',
            'discount_type' => '1',
            'discount_value' => 50000,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'usage_limit' => 50,
            'per_user_limit' => 2,
            'status' => 1,
            'user_id' => 1
        ]);
        Promotion::create([
            'code' => 'PROMO20',
            'name' => 'Giảm giá 20%',
            'description' => 'Giảm giá 20% cho đơn hàng từ 1.000.000đ trở lên',
            'discount_type' => '0',
            'discount_value' => 20,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'usage_limit' => 30,
            'per_user_limit' => 1,
            'status' => 1,
            'user_id' => 1
        ]);
    }
}
