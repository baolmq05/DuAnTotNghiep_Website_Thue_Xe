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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('feature_name', 255)->comment('tên tính năng')->unique();
            $table->text('icon')->comment('biểu tượng');
            $table->text('description')->comment('mô tả tính năng');
            $table->tinyInteger('status')->comment('trạng thái tính năng: 0 - không hoạt động, 1 - hoạt động')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
