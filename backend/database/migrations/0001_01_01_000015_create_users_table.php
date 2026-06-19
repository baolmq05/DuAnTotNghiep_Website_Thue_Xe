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
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->text('name')->comment('Tên người dùng');
            $table->string('email')->unique()->comment('Địa chỉ email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->comment('Mật khẩu');
            $table->string('phone')->unique()->comment('Số điện thoại')->nullable();
            $table->text('avatar')->nullable();
            $table->TinyInteger('gender')->nullable()->comment('Giới tính 0: nữ, 1: nam, 2: khác');
            $table->date('DOB')->nullable()->comment('Ngày sinh');
            $table->string('national_number')->unique()->nullable()->comment('Số căn cước công dân');
            $table->TinyInteger('status')->default(1)->comment('Trạng thái tài khoản 0: bị khóa, 1: hoạt động');
            $table->unsignedBigInteger('role_id')->comment('Vai trò của người dùng');
            $table->unsignedBigInteger('wallet_id')->nullable()->comment('ID của ví');
            $table->unsignedBigInteger('driving_license_id')->nullable()->comment('ID của bằng lái xe');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('wallet_id')->references('id')->on('wallets');
            $table->foreign('driving_license_id')->references('id')->on('driving_licenses')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
