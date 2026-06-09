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
        Schema::create('car_locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('province_id')->comment('mã tỉnh/thành phố')->unsigned();
            $table->bigInteger('ward_code')->comment('mã phường/xã')->unsigned();
            $table->text('street_name')->comment('tên đường')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_locations');
    }
};
