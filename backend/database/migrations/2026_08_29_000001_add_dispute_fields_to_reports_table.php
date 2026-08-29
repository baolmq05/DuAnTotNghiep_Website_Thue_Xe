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
        Schema::table('reports', function (Blueprint $table) {
            $table->tinyInteger('previous_trip_status')->nullable()->after('status')->comment('Trạng thái chuyến đi trước khi xảy ra tranh chấp');
            $table->timestamp('deadline_at')->nullable()->after('admin_note')->comment('Hạn xử lý khiếu nại trước khi tự động hết hạn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['previous_trip_status', 'deadline_at']);
        });
    }
};
