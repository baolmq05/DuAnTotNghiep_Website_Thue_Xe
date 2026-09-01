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
        // 1. trips table (chứa cost, discount_amount, car_discount_amount, promo_discount_amount)
        if (Schema::hasTable('trips')) {
            Schema::table('trips', function (Blueprint $table) {
                if (Schema::hasColumn('trips', 'cost')) {
                    $table->decimal('cost', 15, 2)->change();
                }
                if (Schema::hasColumn('trips', 'discount_amount')) {
                    $table->decimal('discount_amount', 15, 2)->default(0)->change();
                }
                if (Schema::hasColumn('trips', 'car_discount_amount')) {
                    $table->decimal('car_discount_amount', 15, 2)->default(0)->change();
                }
                if (Schema::hasColumn('trips', 'promo_discount_amount')) {
                    $table->decimal('promo_discount_amount', 15, 2)->default(0)->change();
                }
            });
        }

        // 2. transactions table (chứa amount, prepay)
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                if (Schema::hasColumn('transactions', 'amount')) {
                    $table->decimal('amount', 15, 2)->change();
                }
                if (Schema::hasColumn('transactions', 'prepay')) {
                    $table->decimal('prepay', 15, 2)->default(0)->change();
                }
            });
        }

        // 3. pending_balances table (chứa amount)
        if (Schema::hasTable('pending_balances')) {
            Schema::table('pending_balances', function (Blueprint $table) {
                if (Schema::hasColumn('pending_balances', 'amount')) {
                    $table->decimal('amount', 15, 2)->change();
                }
            });
        }

        // 4. wallets table (chứa amount, hold_balance)
        if (Schema::hasTable('wallets')) {
            Schema::table('wallets', function (Blueprint $table) {
                if (Schema::hasColumn('wallets', 'amount')) {
                    $table->decimal('amount', 15, 2)->default(0)->change();
                }
                if (Schema::hasColumn('wallets', 'hold_balance')) {
                    $table->decimal('hold_balance', 15, 2)->default(0)->change();
                }
            });
        }

        // 5. trip_extensions table (chứa extension_amount)
        if (Schema::hasTable('trip_extensions')) {
            Schema::table('trip_extensions', function (Blueprint $table) {
                if (Schema::hasColumn('trip_extensions', 'extension_amount')) {
                    $table->decimal('extension_amount', 15, 2)->default(0)->change();
                }
            });
        }

        // 6. promotions table (chứa discount_value)
        if (Schema::hasTable('promotions')) {
            Schema::table('promotions', function (Blueprint $table) {
                if (Schema::hasColumn('promotions', 'discount_value')) {
                    $table->decimal('discount_value', 15, 2)->change();
                }
            });
        }

        // 7. promotion_usages table (chứa discount_amount)
        if (Schema::hasTable('promotion_usages')) {
            Schema::table('promotion_usages', function (Blueprint $table) {
                if (Schema::hasColumn('promotion_usages', 'discount_amount')) {
                    $table->decimal('discount_amount', 15, 2)->change();
                }
            });
        }

        // 8. refunds table (chứa amount)
        if (Schema::hasTable('refunds')) {
            Schema::table('refunds', function (Blueprint $table) {
                if (Schema::hasColumn('refunds', 'amount')) {
                    $table->decimal('amount', 15, 2)->change();
                }
            });
        }

        // 9. compensation_claims table (chứa requested_amount, counter_amount, final_amount)
        if (Schema::hasTable('compensation_claims')) {
            Schema::table('compensation_claims', function (Blueprint $table) {
                if (Schema::hasColumn('compensation_claims', 'requested_amount')) {
                    $table->decimal('requested_amount', 15, 2)->change();
                }
                if (Schema::hasColumn('compensation_claims', 'counter_amount')) {
                    $table->decimal('counter_amount', 15, 2)->nullable()->change();
                }
                if (Schema::hasColumn('compensation_claims', 'final_amount')) {
                    $table->decimal('final_amount', 15, 2)->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
