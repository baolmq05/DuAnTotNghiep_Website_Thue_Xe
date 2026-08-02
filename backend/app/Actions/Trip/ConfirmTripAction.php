<?php

namespace App\Actions\Trip;

use App\Enum\TripStatus;
use App\Models\Notification;
use App\Models\Trip;
use App\Models\User;
use InvalidArgumentException;

class ConfirmTripAction
{
    public function execute(int $tripId, User $user): Trip
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

        $trip->update(['status' => TripStatus::WaitingPayment->value]);

        Notification::create([
            'user_id' => $trip->user_id,
            'message' => "Yêu cầu thuê xe '{$trip->car->name}' của bạn đã được chủ xe xác nhận. Vui lòng tiến hành thanh toán.",
            'is_read' => '0',
        ]);

        return $trip;
    }
}
