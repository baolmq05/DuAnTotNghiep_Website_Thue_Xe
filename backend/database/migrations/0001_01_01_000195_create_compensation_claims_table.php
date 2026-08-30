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
        Schema::create('compensation_claims', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('renter_id');
            $table->string('title', 255);
            $table->text('description');
            $table->decimal('requested_amount', 15, 2)->comment('Số tiền chủ xe yêu cầu');
            $table->decimal('counter_amount', 15, 2)->nullable()->comment('Người thuê đề xuất lại');
            $table->decimal('final_amount', 15, 2)->nullable()->comment('Số tiền cuối cùng sau thương lượng/Admin');
            $table->unsignedTinyInteger('status')->default(0)->comment('0: Chờ người thuê phản hồi, 1: Đang thương lượng, 2: Người thuê đồng ý, 3: Người thuê từ chối, 4: Chờ Admin xử lý, 5: Đã giải quyết');
            $table->text('owner_note')->nullable();
            $table->text('renter_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('renter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compensation_claims');
    }
};
