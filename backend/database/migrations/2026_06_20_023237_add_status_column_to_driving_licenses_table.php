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
        Schema::table('driving_licenses', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('Trạng thái bằng lái: 0 - chờ duyệt, 1 - đã duyệt, 2 - bị từ chối')->after('DOB');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driving_licenses', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
