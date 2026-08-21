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
        Schema::create('owner_penalties', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('Chủ xe bị phạt');
            $table->unsignedBigInteger('trip_id')->nullable()->comment('Chuyến đi liên quan');
            $table->unsignedBigInteger('report_id')->nullable()->comment('Báo cáo liên quan');
            $table->tinyInteger('penalty_type')->comment('Loại hình phạt: 0 - Cảnh cáo lần 1 (Warning 1), 1 - Cảnh báo lần 2 (Warning 2), 2 - Khóa tài khoản (Account Suspension)');
            $table->timestamp('start_at')->nullable()->comment('Thời gian bắt đầu phạt');
            $table->timestamp('end_at')->nullable()->comment('Thời gian kết thúc phạt');
            $table->text('reason')->comment('Lý do xử phạt');
            $table->unsignedBigInteger('resolved_by')->comment('Admin xử lý');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('set null');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('set null');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_penalties');
    }
};
