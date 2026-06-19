<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::all() as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => 'Chào mừng ' . $user->name . ' đến với Drivio! Bạn đã đăng ký tài khoản thành công.',
                'is_read' => '0'
            ]);
            Notification::create([
                'user_id' => $user->id,
                'message' => 'Thanh toán chuyến đi của bạn đã được xác nhận thành công.',
                'is_read' => '0'
            ]);
        }
    }
}
