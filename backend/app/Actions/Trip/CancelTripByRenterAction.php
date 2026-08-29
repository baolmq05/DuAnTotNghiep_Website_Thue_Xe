<?php

namespace App\Actions\Trip;

use App\Enum\TripStatus;
use App\Models\Notification;
use App\Models\Trip;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Exception;

class CancelTripByRenterAction
{
    public function execute(int $tripId, User $user): array
    {
        $trip = Trip::with(['car.owner', 'user'])->find($tripId);
        if (!$trip) {
            throw new InvalidArgumentException('Không tìm thấy thông tin chuyến đi.');
        }

        if ($trip->user_id != $user->id) {
            throw new InvalidArgumentException('Bạn không có quyền hủy chuyến đi này.');
        }

        if ($trip->status == TripStatus::Disputed->value) {
            throw new InvalidArgumentException('Chuyến đi đang trong quá trình xử lý khiếu nại (Tranh chấp). Vui lòng chờ phản hồi từ bộ phận CSKH trước khi thao tác.');
        }

        // Chỉ cho phép hủy khi trạng thái ở 0 (Pending), 1 (WaitingPayment), 2 (Confirmed)
        if (!in_array($trip->status, [
            TripStatus::Pending->value,
            TripStatus::WaitingPayment->value,
            TripStatus::Confirmed->value
        ])) {
            throw new InvalidArgumentException('Chuyến đi không ở trạng thái hợp lệ để hủy.');
        }

        $totalPaid = (float)$trip->transactions()->sum('amount');
        $tripValue = $trip->owner_gross_revenue > 0 ? $trip->owner_gross_revenue : ($trip->cost - $trip->discount_amount);
        $bookingTime = $trip->created_at->timezone('Asia/Ho_Chi_Minh');
        $startTime = Carbon::parse($trip->start_at, 'Asia/Ho_Chi_Minh');
        $now = now()->timezone('Asia/Ho_Chi_Minh');

        $cancellationFeePercent = 0;
        if ($bookingTime->diffInMinutes($now) > 60) {
            $daysToStart = $now->diffInDays($startTime, false);
            if ($daysToStart >= 7) {
                $cancellationFeePercent = 10;
            } else {
                $cancellationFeePercent = 40;
            }
        }

        $cancellationFee = ($cancellationFeePercent / 100) * $tripValue;
        $refundAmount = max(0, $totalPaid - $cancellationFee);
        $compensationFee = min($totalPaid, $cancellationFee);

        try {
            DB::beginTransaction();

            // Hoàn tiền cho Khách thuê
            if ($refundAmount > 0) {
                $renter = $trip->user;
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $renter->id],
                    ['amount' => 0, 'hold_balance' => 0]
                );
                $wallet->increment('amount', $refundAmount);
            }

            // Đền bù tiền phạt cho Chủ xe
            if ($compensationFee > 0) {
                $owner = $trip->car->owner;
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $owner->id],
                    ['amount' => 0, 'hold_balance' => 0]
                );
                $wallet->increment('amount', $compensationFee);
            }

            // Hủy các bản ghi tiền treo
            $trip->pendingBalances()->update(['status' => '0']);

            $trip->update([
                'status' => TripStatus::UserCancel->value
            ]);

            Notification::create([
                'user_id' => $trip->car->user_id,
                'message' => "Khách hàng đã hủy chuyến đi #{$trip->id}. Phí đền bù nhận được: " . number_format($compensationFee, 0, ',', '.') . " đ.",
                'is_read' => '0',
            ]);

            DB::commit();

            return [
                'trip' => $trip,
                'summary' => [
                    'total_paid' => $totalPaid,
                    'cancellation_fee_percent' => $cancellationFeePercent,
                    'cancellation_fee' => $cancellationFee,
                    'refund_amount' => $refundAmount,
                    'compensation_fee' => $compensationFee,
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
