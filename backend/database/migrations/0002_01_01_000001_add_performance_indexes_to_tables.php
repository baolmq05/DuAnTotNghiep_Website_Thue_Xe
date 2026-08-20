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
        Schema::table('users', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->index('status');
            $table->index('unit_price');
            $table->index('seat_count');
            $table->index('created_at');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
            $table->index(['start_at', 'end_at']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->index('status');
            $table->index('published_at');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('rating');
            $table->index('review_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['unit_price']);
            $table->dropIndex(['seat_count']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['start_at', 'end_at']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['published_at']);
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['start_date', 'end_date']);
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['rating']);
            $table->dropIndex(['review_type']);
        });
    }
};
