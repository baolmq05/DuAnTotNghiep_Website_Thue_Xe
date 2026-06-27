<?php

use App\Models\ChatConversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    // 1. Tìm thông tin cuộc hội thoại kèm thông tin chuyến đi (trip) và xe (car)
    $conversation = ChatConversation::with('trip.car')->find($conversationId);

    if (!$conversation) {
        return false;
    }
    // 2. Lấy ID người thuê xe (renter) và chủ xe (owner)
    $renterId = $conversation->trip->user_id;
    $ownerId = $conversation->trip->car->user_id;
    // 3. Cho phép kết nối nếu ID của User hiện tại khớp với Renter hoặc Owner
    return (int) $user->id === (int) $renterId || (int) $user->id === (int) $ownerId;
});