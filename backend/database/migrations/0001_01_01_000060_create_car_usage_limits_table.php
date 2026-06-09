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
        Schema::create('car_usage_limits', function (Blueprint $table) {
            $table->id();
            $table->float('max_daily_distance', 10, 2)->comment('khoảng cách tối đa')->unsigned();
            $table->decimal('extra_distance_fee', 10, 2)->comment('phí cho mỗi km vượt quá khoảng cách tối đa')->unsigned();
            $table->tinyInteger('status')->comment('trạng thái giới hạn sử dụng: 0 - không áp dụng, 1 - áp dụng')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_usage_limits');
    }
};
