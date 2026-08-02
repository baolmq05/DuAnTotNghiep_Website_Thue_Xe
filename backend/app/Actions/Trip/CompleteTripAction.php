<?php

namespace App\Actions\Trip;

use App\Enum\TripStatus;
use App\Models\Notification;
use App\Models\Trip;
use App\Models\TripImage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Exception;

class CompleteTripAction
{
    public function execute(int $tripId, User $user, array $imageUrls): Trip
    {
        $trip = Trip::with('car')->find($tripId);
        if (!$trip) {
            throw new InvalidArgumentException('Không tìm thấy thông tin chuyến đi.');
        }

        if ($trip->car->user_id !== $user->id) {
            throw new InvalidArgumentException('Bạn không có quyền thực hiện hành động này.');
        }

        if ($trip->status !== TripStatus::WaitingReturn->value) {
            throw new InvalidArgumentException('Chuyến đi không ở trạng thái Chờ trả xe để hoàn thành.');
        }

        try {
            DB::beginTransaction();

            foreach ($imageUrls as $url) {
                TripImage::create([
                    'trip_id' => $trip->id,
                    'image_url' => $url,
                    'type' => 1, // Sau chuyến đi
                    'is_thumbnail' => 0,
                ]);
            }

            $trip->update(['status' => TripStatus::Complete->value]);

            // Giải ngân tiền từ pending_balances sang ví chủ xe (giữ lại 2% phí phạt nguội vào hold_balance)
            $trip->releasePendingBalances();

            Notification::create([
                'user_id' => $trip->user_id,
                'message' => "Chuyến đi #{$trip->id} (xe {$trip->car->name}) của bạn đã được chủ xe xác nhận hoàn thành.",
                'is_read' => '0',
            ]);

            DB::commit();

            return $trip->load(['car', 'images']);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
