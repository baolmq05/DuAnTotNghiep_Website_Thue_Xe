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
        Schema::table('trips', function (Blueprint $table) {
            if (!Schema::hasColumn('trips', 'car_discount_amount')) {
                $table->decimal('car_discount_amount', 15, 2)
                    ->default(0)
                    ->after('cost')
                    ->comment('Số tiền giảm giá của chủ xe (car.discount_value * days)');
            }
            if (!Schema::hasColumn('trips', 'promo_discount_amount')) {
                $table->decimal('promo_discount_amount', 15, 2)
                    ->default(0)
                    ->after('car_discount_amount')
                    ->comment('Số tiền mã giảm giá khuyến mãi của sàn/admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            if (Schema::hasColumn('trips', 'car_discount_amount')) {
                $table->dropColumn('car_discount_amount');
            }
            if (Schema::hasColumn('trips', 'promo_discount_amount')) {
                $table->dropColumn('promo_discount_amount');
            }
        });
    }
};
