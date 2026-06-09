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
        Schema::create('driving_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->comment('Tên đầy đủ');
            $table->text('image')->comment('Ảnh bằng lái xe');
            $table->string('driving_license_number')->unique()->comment('Số bằng lái xe');
            $table->date('DOB')->comment('Ngày sinh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driving_licenses');
    }
};
