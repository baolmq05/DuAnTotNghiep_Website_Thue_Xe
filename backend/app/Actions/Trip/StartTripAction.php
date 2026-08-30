<?php

namespace App\Actions\Trip;

use App\Enum\TripStatus;
use App\Models\Trip;
use App\Models\TripImage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Exception;

class StartTripAction
{
    public function execute(int $tripId, User $user, array $imageUrls): Trip
    {
        $trip = Trip::with('car')->find($tripId);
        if (!$trip) {
            throw new InvalidArgumentException('Không tìm thấy thông tin chuyến đi.');
        }

        if ($trip->user_id !== $user->id && $trip->car->user_id !== $user->id) {
            throw new InvalidArgumentException('Bạn không có quyền thực hiện hành động này.');
        }

        $statusVal = $trip->status instanceof TripStatus ? $trip->status->value : (int) $trip->status;
        if ($statusVal !== TripStatus::Confirmed->value) {
            throw new InvalidArgumentException('Chuyến đi không ở trạng thái Đang xác nhận để bắt đầu.');
        }

        try {
            DB::beginTransaction();

            foreach ($imageUrls as $index => $url) {
                TripImage::create([
                    'trip_id' => $trip->id,
                    'image_url' => $url,
                    'type' => 0, // Trước chuyến đi
                    'is_thumbnail' => $index === 0 ? 1 : 0,
                ]);
            }

            $trip->update(['status' => TripStatus::Ongoing->value]);

            DB::commit();

            return $trip->load(['car', 'images']);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
