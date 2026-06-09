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
        Schema::create('car_delivery_options', function (Blueprint $table) {
            $table->id();
            $table->float('max_distance', 10, 2)->comment('khoảng cách tối đa')->unsigned();
            $table->float('fee_distance', 10, 2)->comment('phí cho mỗi km vượt quá khoảng cách tối đa')->unsigned();
            $table->tinyInteger('status')->comment('trạng thái giao xe: 0 - không áp dụng, 1 - áp dụng')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_delivery_options');
    }
};
