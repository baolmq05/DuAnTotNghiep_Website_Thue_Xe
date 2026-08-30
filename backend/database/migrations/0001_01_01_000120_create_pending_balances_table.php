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
        Schema::create('pending_balances', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('transaction_id')->nullable()->comment('tham chiếu transaction');
            $table->unsignedBigInteger('trip_id')->comment('đơn thuê xe');
            $table->unsignedBigInteger('payer_id')->comment('người thuê');
            $table->unsignedBigInteger('receiver_id')->comment('chủ xe');
            $table->decimal('amount', 15, 2)->comment('tiền đang giữ');
            $table->string('status')->default('1')->comment('1 - holding, 2 - released, 3 - cancelled');
            $table->timestamp('expired_at')->nullable()->comment('quá hạn');
            $table->timestamp('released_at')->nullable()->comment('thời điểm giải ngân');

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->foreign('payer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_balances');
    }
};
