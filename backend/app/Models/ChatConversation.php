<?php

namespace App\Models;

use App\Enum\TripStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    /** @use HasFactory<\Database\Factories\ChatConversationFactory> */
    use HasFactory;

    protected $fillable = [
        'status',
        'trip_id'
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    /**
     * Tự động tạo cuộc trò chuyện cho chuyến đi chỉ khi chuyến đi ĐÃ ĐƯỢC XÁC NHẬN trở lên
     */
    public static function createForTrip($trip): ?self
    {
        if (!$trip) {
            return null;
        }

        // Không tạo chat nếu chuyến đi chưa được xác nhận (Pending = 0) hoặc đã bị hủy (UserCancel = 5, OwnerCancel = 6)
        if (in_array((int)$trip->status, [
            TripStatus::Pending->value,
            TripStatus::UserCancel->value,
            TripStatus::OwnerCancel->value
        ])) {
            return null;
        }

        $conversation = static::firstOrCreate(
            ['trip_id' => $trip->id],
            ['status' => 1]
        );

        if ($conversation->messages()->count() === 0 && $trip->car) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $trip->car->user_id,
                'text' => "Xin chào! Chuyến đi #{$trip->id} (xe {$trip->car->name}) đã được xác nhận thành công. Bạn có thể trao đổi thông tin chi tiết với tôi tại đây.",
                'type' => 'text',
                'is_read' => false
            ]);
        }

        return $conversation;
    }
}
