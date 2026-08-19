<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->tinyInteger('report_type')->unsigned()->comment('Loại báo cáo: 0 - Giao sai xe, 1 - Không đến giao/nhận xe, 2 - Gian lận, 3 - Khác');
            $table->string('title', 255)->comment('tiêu đề báo cáo');
            $table->text('description')->comment('chi tiết báo cáo');
            $table->tinyInteger('status')->unsigned()->comment('trạng thái: 0 - Chờ xử lý, 1 - Đang xử lý, 2 - Đã giải quyết, 3 - Từ chối')->default(0);
            $table->text('admin_note')->nullable()->comment('ghi chú của admin');
            $table->timestamp('resolved_at')->nullable()->comment('thời gian xử lý');
            
            $table->unsignedBigInteger('trip_id')->comment('mã chuyến đi');
            $table->unsignedBigInteger('reporter_id')->comment('mã người báo cáo');
            $table->unsignedBigInteger('resolved_by')->nullable()->comment('admin xử lý');

            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
