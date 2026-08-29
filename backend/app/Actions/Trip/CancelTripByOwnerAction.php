<?php

namespace App\Actions\Trip;

use App\Enum\TripStatus;
use App\Models\Notification;
use App\Models\Trip;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Exception;

class CancelTripByOwnerAction
{
    public function execute(int $tripId, User $user, ?string $reason = null): array
    {
        $trip = Trip::with(['car.owner', 'user'])->find($tripId);
        if (!$trip) {
            throw new InvalidArgumentException('Không tìm thấy thông tin chuyến đi.');
        }

        if ($trip->car->user_id != $user->id) {
            throw new InvalidArgumentException('Bạn không có quyền thực hiện hành động này.');
        }

        if ($trip->status == TripStatus::Disputed->value) {
            throw new InvalidArgumentException('Chuyến đi đang trong quá trình xử lý khiếu nại (Tranh chấp). Vui lòng chờ phản hồi từ bộ phận CSKH trước khi thao tác.');
        }

        if (!in_array($trip->status, [
            TripStatus::Pending->value,
            TripStatus::WaitingPayment->value,
            TripStatus::Confirmed->value
        ])) {
            throw new InvalidArgumentException('Chuyến đi không ở trạng thái hợp lệ để hủy.');
        }

        $cancelReason = $reason ?? 'Chủ xe hủy chuyến đi vì lý do cá nhân';
        $totalPaid = (float)$trip->transactions()->sum('amount');
        $refundAmount = $totalPaid;

        try {
            DB::beginTransaction();

            if ($refundAmount > 0) {
                $renter = $trip->user;
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $renter->id],
                    ['amount' => 0, 'hold_balance' => 0]
                );
                $wallet->increment('amount', $refundAmount);
            }

            $trip->pendingBalances()->update(['status' => '0']);

            $trip->update([
                'status' => TripStatus::OwnerCancel->value
            ]);

            Notification::create([
                'user_id' => $trip->user_id,
                'message' => "Chủ xe đã hủy chuyến đi #{$trip->id}. Số tiền " . number_format($refundAmount, 0, ',', '.') . " đ đã được hoàn lại vào ví của bạn. Lý do: {$cancelReason}.",
                'is_read' => '0',
            ]);

            DB::commit();

            return [
                'trip' => $trip,
                'summary' => [
                    'total_paid' => $totalPaid,
                    'refund_amount' => $refundAmount,
                    'reason' => $cancelReason,
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
