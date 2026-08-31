<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\FcmService;
use Illuminate\Support\Facades\Log;
use Exception;

class Notification extends Model
{
    protected $fillable = ['user_id', 'message', 'is_read'];

    protected static function booted()
    {
        // Tự động bắn Push Notification qua Firebase bất cứ khi nào có bản ghi thông báo mới được tạo
        static::created(function ($notification) {
            try {
                $user = User::find($notification->user_id);
                if ($user && !empty($user->fcm_token)) {
                    FcmService::sendPushNotification(
                        $user->fcm_token,
                        'Thông báo Drivio',
                        $notification->message,
                        [
                            'type' => 'notification',
                            'id' => (string) $notification->id,
                            'message' => $notification->message,
                        ]
                    );
                }
            } catch (Exception $e) {
                Log::error('Lỗi gửi FCM từ Notification model: ' . $e->getMessage());
            }
        });
    }
}
