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

class AdminCancelTripAction
{
    /**
     * Cancel a trip by Admin intervention (Free cancellation / Dispute settlement).
     *
     * @param int $tripId
     * @param User $adminUser
     * @param string $faultSide 'owner' | 'renter'
     * @param string $reason
     * @return array
     */
    public function execute(int $tripId, User $adminUser, string $faultSide = 'owner', string $reason = ''): array
    {
        $trip = Trip::with(['car.owner', 'user'])->find($tripId);
        if (!$trip) {
            throw new InvalidArgumentException('Không tìm thấy thông tin chuyến đi.');
        }

        $totalPaid = (float) $trip->transactions()->sum('amount');
        $tripCode = $trip->trip_code ?? ('#' . $trip->id);

        try {
            DB::beginTransaction();

            if ($faultSide == 'owner') {
                // 1. Owner is at fault -> 100% Refund to Renter, No cancellation fee
                if ($totalPaid > 0) {
                    $renterWallet = Wallet::firstOrCreate(
                        ['user_id' => $trip->user_id],
                        ['amount' => 0, 'hold_balance' => 0]
                    );
                    $renterWallet->increment('amount', $totalPaid);
                }

                // Cancel any pending balances
                $trip->pendingBalances()->update(['status' => '0']);

                // Set status to OwnerCancel
                $trip->update([
                    'status' => TripStatus::OwnerCancel->value
                ]);

                // Notifications
                Notification::create([
                    'user_id' => $trip->user_id,
                    'message' => "Khiếu nại về chuyến đi {$tripCode} đã được giải quyết. Quản trị viên đã hủy chuyến và hoàn trả 100% số tiền " . number_format($totalPaid, 0, ',', '.') . " đ vào ví của bạn.",
                    'is_read' => '0',
                ]);

                if ($trip->car && $trip->car->user_id) {
                    Notification::create([
                        'user_id' => $trip->car->user_id,
                        'message' => "Chuyến đi {$tripCode} đã bị hủy bởi Quản trị viên do vi phạm từ phía chủ xe. Lý do: {$reason}.",
                        'is_read' => '0',
                    ]);
                }

            } else {
                // 2. Renter is at fault -> Apply Cancellation Policy:
                // Deduct cancellation fee according to policy (compensated to Owner), refund the remainder to Renter
                $tripValue = $trip->owner_gross_revenue > 0 ? $trip->owner_gross_revenue : ($trip->cost - $trip->discount_amount);
                $bookingTime = $trip->created_at ? $trip->created_at->timezone('Asia/Ho_Chi_Minh') : now()->timezone('Asia/Ho_Chi_Minh');
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

                // Refund the remainder to Renter
                if ($refundAmount > 0) {
                    $renterWallet = Wallet::firstOrCreate(
                        ['user_id' => $trip->user_id],
                        ['amount' => 0, 'hold_balance' => 0]
                    );
                    $renterWallet->increment('amount', $refundAmount);
                }

                // Transfer compensation fee to Owner
                if ($compensationFee > 0 && $trip->car && $trip->car->owner) {
                    $ownerWallet = Wallet::firstOrCreate(
                        ['user_id' => $trip->car->user_id],
                        ['amount' => 0, 'hold_balance' => 0]
                    );
                    $ownerWallet->increment('amount', $compensationFee);
                }

                // Cancel pending balances
                $trip->pendingBalances()->update(['status' => '0']);

                // Set status to UserCancel
                $trip->update([
                    'status' => TripStatus::UserCancel->value
                ]);

                // Notifications
                $renterMsg = "Chuyến đi {$tripCode} đã bị hủy bởi Quản trị viên (Xác định lỗi từ phía khách thuê). ";
                if ($cancellationFeePercent > 0) {
                    $renterMsg .= "Phí hủy chuyến áp dụng: " . $cancellationFeePercent . "% (" . number_format($compensationFee, 0, ',', '.') . " đ). Số tiền còn lại " . number_format($refundAmount, 0, ',', '.') . " đ đã được hoàn vào ví của bạn. Lý do: {$reason}.";
                } else {
                    $renterMsg .= "Bạn được miễn phí hủy chuyến, số tiền " . number_format($refundAmount, 0, ',', '.') . " đ đã được hoàn vào ví. Lý do: {$reason}.";
                }

                Notification::create([
                    'user_id' => $trip->user_id,
                    'message' => $renterMsg,
                    'is_read' => '0',
                ]);

                if ($trip->car && $trip->car->user_id) {
                    $ownerMsg = "Khiếu nại về chuyến đi {$tripCode} đã được giải quyết (Lỗi phía khách thuê). ";
                    if ($compensationFee > 0) {
                        $ownerMsg .= "Bạn đã nhận được tiền đền bù phí hủy " . number_format($compensationFee, 0, ',', '.') . " đ vào ví.";
                    } else {
                        $ownerMsg .= "Chuyến đi đã được hủy.";
                    }

                    Notification::create([
                        'user_id' => $trip->car->user_id,
                        'message' => $ownerMsg,
                        'is_read' => '0',
                    ]);
                }
            }

            DB::commit();

            return [
                'trip' => $trip,
                'summary' => [
                    'fault_side' => $faultSide,
                    'total_paid' => $totalPaid,
                    'reason' => $reason,
                ]
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
