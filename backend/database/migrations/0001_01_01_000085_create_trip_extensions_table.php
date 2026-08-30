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
        Schema::create('trip_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->decimal('extension_amount', 15, 2)->default(0)->comment('Phí gia hạn thêm');
            $table->tinyInteger('status')->default(0)->comment('0: Chưa gia hạn, 1: Đã gửi yêu cầu, 2: Chờ thanh toán, 3: Đã gia hạn, 4: Bị từ chối');
            $table->dateTime('start_date')->nullable()->comment('Thời gian kết thúc cũ trước khi gia hạn');
            $table->dateTime('end_date')->nullable()->comment('Thời gian kết thúc mới đề xuất');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_extensions');
    }
};
