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
        Schema::create('transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('ID của người dùng');
            $table->string('transaction_code')->unique()->comment('Mã giao dịch');
            $table->decimal('amount', 15, 2)->comment('Số tiền giao dịch');
            $table->decimal('prepay', 15, 2)->default(0)->comment('Số tiền đặt cọc trước');
            $table->unsignedBigInteger('trip_id')->nullable()->comment('ID của chuyến đi nếu có');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
