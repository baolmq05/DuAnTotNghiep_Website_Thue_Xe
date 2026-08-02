<?php

namespace App\Actions\Trip;

use App\Enum\TripStatus;
use App\Models\Notification;
use App\Models\Trip;
use App\Models\User;
use InvalidArgumentException;

class RejectTripAction
{
    public function execute(int $tripId, User $user, ?string $reason = null): Trip
    {
        $trip = Trip::with('car')->find($tripId);
        if (!$trip) {
            throw new InvalidArgumentException('Không tìm thấy thông tin chuyến đi.');
        }

        if ($trip->car->user_id !== $user->id) {
            throw new InvalidArgumentException('Bạn không có quyền thực hiện hành động này.');
        }

        if ($trip->status !== TripStatus::Pending->value) {
            throw new InvalidArgumentException('Chuyến đi không ở trạng thái Chờ xác nhận.');
        }

        $rejectReason = $reason ?? 'Chủ xe từ chối yêu cầu thuê';
        $trip->update(['status' => TripStatus::OwnerCancel->value]);

        Notification::create([
            'user_id' => $trip->user_id,
            'message' => "Yêu cầu thuê xe '{$trip->car->name}' của bạn đã bị từ chối. Lý do: {$rejectReason}.",
            'is_read' => '0',
        ]);

        return $trip;
    }
}
