<?php

namespace App\Actions\Trip;

use App\Enum\TripStatus;
use App\Models\Notification;
use App\Models\Trip;
use App\Models\User;
use InvalidArgumentException;

class RequestReturnTripAction
{
    public function execute(int $tripId, User $user): Trip
    {
        $trip = Trip::with('car')->find($tripId);
        if (!$trip) {
            throw new InvalidArgumentException('Không tìm thấy thông tin chuyến đi.');
        }

        if ($trip->user_id !== $user->id) {
            throw new InvalidArgumentException('Bạn không có quyền thực hiện hành động này.');
        }

        if ($trip->status !== TripStatus::Ongoing->value) {
            throw new InvalidArgumentException('Chuyến đi không ở trạng thái Đang diễn ra.');
        }

        $trip->update(['status' => TripStatus::WaitingReturn->value]);

        Notification::create([
            'user_id' => $trip->car->user_id,
            'message' => "Khách hàng {$user->name} đã yêu cầu trả xe sớm cho chuyến đi #{$trip->id} (xe {$trip->car->name}). Vui lòng xác nhận hoàn thành chuyến xe.",
            'is_read' => '0',
        ]);

        return $trip;
    }
}
