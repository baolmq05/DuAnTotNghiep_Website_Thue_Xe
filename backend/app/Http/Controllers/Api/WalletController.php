<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Enum\RefundStatus;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Car;
use App\Models\Review;
use App\Models\Trip;
use App\Models\Refund;
use App\Models\PendingBalance;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Wallet\WithdrawRequest;
use App\Http\Requests\Wallet\WithdrawHoldRequest;
use Exception;

class WalletController extends Controller
{
    /**
     * Get wallet details and transaction history.
     * GET /api/auth/wallet
     */
    public function getWalletDetails(Request $request)
    {
        try {
            $user = auth('api')->user();

            $month = intval($request->query('month', now()->month));
            $year  = intval($request->query('year', now()->year));

            // Ensure the user has a wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['amount' => 0, 'hold_balance' => 0]
            );

            // Fetch transaction history
            $transactions = $this->getTransactionHistory($user->id);

            // Calculate summaries for requested month and year
            $summary = $this->calculateSummary($transactions, $wallet, $month, $year);

            // Fetch owner profile stats
            $rating = $this->getOwnerRating($user->id);
            $completedTripsCount = $this->getCompletedTripsCount($user->id);
            $pendingBalance = $this->getPendingBalance($user->id);

            // Fetch refunds from Refunds table for this wallet filtered by month & year
            $refunds = Refund::where('wallet_id', $wallet->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->orderBy('created_at', 'desc')
                ->get();

            // Filter transactions by requested month and year for transactions list
            $monthlyTransactions = $transactions->filter(function ($txn) use ($month, $year) {
                if ($txn->trip_id && $txn->trip) {
                    $trip = $txn->trip;
                    $dateStr = $trip->end_at ?: ($trip->updated_at ?: $trip->start_at);
                    if ($dateStr) {
                        $date = Carbon::parse($dateStr);
                        return $date->month == $month && $date->year == $year;
                    }
                }
                if ($txn->created_at) {
                    $date = Carbon::parse($txn->created_at);
                    return $date->month == $month && $date->year == $year;
                }
                return false;
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'balance'               => floatval($wallet->amount ?? 0),
                    'hold_balance'          => floatval($wallet->hold_balance ?? 0),
                    'pending_balance'       => floatval($pendingBalance),
                    'rating'                => $rating,
                    'completed_trips_count' => $completedTripsCount,
                    'response_rate'         => 100,
                    'response_time'         => '5 phút',
                    'accept_rate'           => 100,
                    'transactions'          => $this->formatTransactions($monthlyTransactions),
                    'refunds'               => $refunds->map(function ($ref) {
                        return [
                            'id'               => $ref->id,
                            'transaction_code' => 'RF' . sprintf('%06d', $ref->id),
                            'amount'           => -$ref->amount,
                            'status'           => $ref->status->value,
                            'description'      => $ref->description ?: ('Yêu cầu rút tiền #' . $ref->id),
                            'created_at'       => $ref->created_at ? (is_string($ref->created_at) ? date('d/m/Y H:i', strtotime($ref->created_at)) : $ref->created_at->format('d/m/Y H:i')) : null,
                        ];
                    }),
                    'summary'               => $summary
                ]
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request withdrawal from user wallet.
     * POST /api/auth/wallet/withdraw
     */
    public function withdraw(WithdrawRequest $request)
    {
        try {
            $user = auth('api')->user();

            // Check if user linked bank account details
            if (empty($user->bank_name) || empty($user->bank_account_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng liên kết tài khoản ngân hàng trong phần Thông tin tài khoản trước khi thực hiện rút tiền.'
                ], 400);
            }

            // Ensure user has a wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['amount' => 0, 'hold_balance' => 0]
            );

            $amount = intval($request->input('amount'));

            // Check wallet balance
            if ($wallet->amount < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số dư trong ví không đủ để thực hiện giao dịch này.'
                ], 400);
            }

            // Create Refund request directly without deducting amount or logging transaction yet
            Refund::create([
                'wallet_id'   => $wallet->id,
                'amount'      => $amount,
                'status'      => RefundStatus::Pending,
                'description' => $request->input('description') ?: ('Rút tiền về ngân hàng ' . $user->bank_name . ' (' . $user->bank_account_number . ')'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gửi yêu cầu rút tiền thành công. Yêu cầu đang được chờ phê duyệt.',
                'balance' => $wallet->fresh()->amount
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Withdraw from hold balance to available wallet balance.
     * POST /api/auth/wallet/withdraw-hold
     */
    public function withdrawHold(WithdrawHoldRequest $request)
    {
        try {
            $user = auth('api')->user();

            // Ensure user has a wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['amount' => 0, 'hold_balance' => 0]
            );

            $amount = intval($request->input('amount'));

            // Check hold balance
            if ($wallet->hold_balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số dư tiền tạm giữ không đủ để thực hiện giao dịch này.'
                ], 400);
            }

            // Perform DB transaction to transfer balance and record history
            DB::transaction(function () use ($user, $wallet, $amount) {
                // 1. Deduct hold_balance, increment amount
                $wallet->decrement('hold_balance', $amount);
                $wallet->increment('amount', $amount);

                // 2. Create Transaction log
                Transaction::create([
                    'user_id'          => $user->id,
                    'transaction_code' => 'WH' . strtoupper(uniqid()),
                    'amount'           => $amount,
                    'prepay'           => 0,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Rút tiền dự trù về ví thành công.',
                'balance' => floatval($wallet->fresh()->amount),
                'hold_balance' => floatval($wallet->fresh()->hold_balance)
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction history for current renter and owner trips (Helper).
     */
    private function getTransactionHistory(int $userId)
    {
        $carIds = Car::where('user_id', $userId)->pluck('id');
        $tripIds = Trip::whereIn('car_id', $carIds)->pluck('id');

        return Transaction::where(function ($query) use ($userId, $tripIds) {
            $query->where('user_id', $userId)
                  ->orWhereIn('trip_id', $tripIds);
        })
        ->with(['trip.car.owner', 'trip.user'])
        ->orderBy('created_at', 'desc')
        ->get();
    }

    /**
     * Calculate financial summary data (Helper).
     */
    private function calculateSummary($transactions, Wallet $wallet, ?int $currentMonth = null, ?int $currentYear = null): array
    {
        $completedTripsChange = 0;
        $depositWithdrawalChange = 0;
        $cancelledTripsChange = 0;

        $currentMonth = $currentMonth ?: now()->month;
        $currentYear  = $currentYear ?: now()->year;

        foreach ($transactions as $txn) {
            $isCurrentMonth = false;

            if ($txn->trip_id && $txn->trip) {
                $trip = $txn->trip;
                // Kiểm tra ngày kết thúc chuyến đi (end_at) có thuộc tháng & năm hiện tại không
                if (!empty($trip->end_at)) {
                    $endDate = Carbon::parse($trip->end_at);
                    if ($endDate->month == $currentMonth && $endDate->year == $currentYear) {
                        $isCurrentMonth = true;
                    }
                } elseif (!empty($txn->created_at)) {
                    $txnDate = Carbon::parse($txn->created_at);
                    if ($txnDate->month == $currentMonth && $txnDate->year == $currentYear) {
                        $isCurrentMonth = true;
                    }
                }
            } else {
                // Giao dịch nạp/rút tiền (không có trip) kiểm tra ngày tạo giao dịch
                if (!empty($txn->created_at)) {
                    $txnDate = Carbon::parse($txn->created_at);
                    if ($txnDate->month == $currentMonth && $txnDate->year == $currentYear) {
                        $isCurrentMonth = true;
                    }
                } else {
                    $isCurrentMonth = true;
                }
            }

            if ($isCurrentMonth) {
                if ($txn->trip_id && $txn->trip) {
                    $trip = $txn->trip;
                    if ((int)$trip->status === TripStatus::Complete->value) {
                        $completedTripsChange += $txn->amount;
                    } elseif (in_array((int)$trip->status, [TripStatus::UserCancel->value, TripStatus::OwnerCancel->value])) {
                        $cancelledTripsChange += $txn->amount;
                    }
                } else {
                    $depositWithdrawalChange += $txn->amount;
                }
            }
        }

        $totalChange = $completedTripsChange + $depositWithdrawalChange + $cancelledTripsChange;
        
        $startBalance = $wallet->amount - $totalChange;
        if ($startBalance < 0) {
            $startBalance = 0;
        }

        // Đọc tỷ lệ cài đặt từ DB system_settings (hoặc mặc định: hoa hồng 18%, VAT 7%, phạt nguội 2%)
        $commissionRate = floatval(SystemSetting::get('commission_rate', 18));
        $vatRate        = floatval(SystemSetting::get('vat_rate', 7));
        $taxRate        = $commissionRate + $vatRate; // 18% + 7% = 25%
        $penaltyRate    = floatval(SystemSetting::get('fee_2_percent', 2));

        // Tính toán các khoản tiền khấu trừ dựa trên tổng amount chuyến đi hoàn thành trong tháng
        $taxDeducted     = intval($completedTripsChange * ($taxRate / 100));
        $penaltyDeducted = intval($completedTripsChange * ($penaltyRate / 100));

        $ownerIncome = $completedTripsChange - $taxDeducted - $penaltyDeducted;

        return [
            'completed_trips_change'    => $completedTripsChange,
            'deposit_withdrawal_change' => $depositWithdrawalChange,
            'cancelled_trips_change'    => $cancelledTripsChange,
            'total_change'              => $totalChange,
            'start_balance'             => $startBalance,
            'end_balance'               => $wallet->amount,
            'tax_rate'                  => $taxRate,
            'penalty_rate'              => $penaltyRate,
            'tax_deducted'              => $taxDeducted,
            'penalty_deducted'          => $penaltyDeducted,
            'owner_income'              => $ownerIncome
        ];
    }

    /**
     * Calculate owner's average rating (Helper).
     */
    private function getOwnerRating(int $userId): float
    {
        $carIds = Car::where('user_id', $userId)->pluck('id');
        $rating = Review::whereIn('car_id', $carIds)
            ->where('review_type', 1)
            ->avg('rating');

        return $rating ? round(floatval($rating), 1) : 5.0;
    }

    /**
     * Count completed trips as an owner (Helper).
     */
    private function getCompletedTripsCount(int $userId): int
    {
        $carIds = Car::where('user_id', $userId)->pluck('id');

        return Trip::whereIn('car_id', $carIds)
            ->where('status', TripStatus::Complete->value)
            ->count();
    }

    /**
     * Sum the owner's pending hold balance (Helper).
     */
    private function getPendingBalance(int $userId): float
    {
        return floatval(
            PendingBalance::where('receiver_id', $userId)
                ->where('status', '1')
                ->sum('amount')
        );
    }

    /**
     * Format transaction items for JSON response (Helper).
     */
    private function formatTransactions($transactions)
    {
        $commissionRate = floatval(SystemSetting::get('commission_rate', 18));
        $vatRate        = floatval(SystemSetting::get('vat_rate', 7));
        $taxRate        = $commissionRate + $vatRate; // 25%
        $penaltyRate    = floatval(SystemSetting::get('fee_2_percent', 2)); // 2%

        return $transactions->map(function ($txn) use ($taxRate, $penaltyRate) {
            return [
                'id'               => $txn->id,
                'transaction_code' => $txn->transaction_code,
                'amount'           => $txn->amount,
                'prepay'           => $txn->prepay,
                'description'      => str_starts_with($txn->transaction_code, 'WH') ? 'Rút tiền dự trù (phạt nguội) về ví' : (str_starts_with($txn->transaction_code, 'WD') ? 'Rút tiền về tài khoản ngân hàng' : null),
                'created_at'       => $txn->created_at ? (is_string($txn->created_at) ? date('d/m/Y H:i', strtotime($txn->created_at)) : $txn->created_at->format('d/m/Y H:i')) : null,
                'trip'             => $txn->trip ? [
                    'id'              => $txn->trip->id,
                    'start_at'        => $txn->trip->start_at ? date('d/m/Y', strtotime($txn->trip->start_at)) : null,
                    'end_at'          => $txn->trip->end_at ? date('d/m/Y', strtotime($txn->trip->end_at)) : null,
                    'created_at'      => $txn->trip->created_at ? (is_string($txn->trip->created_at) ? date('d/m/Y', strtotime($txn->trip->created_at)) : $txn->trip->created_at->format('d/m/Y')) : null,
                    'updated_at'      => $txn->trip->updated_at ? (is_string($txn->trip->updated_at) ? date('d/m/Y H:i', strtotime($txn->trip->updated_at)) : $txn->trip->updated_at->format('d/m/Y H:i')) : null,
                    'cost'            => $txn->trip->cost,
                    'discount_amount' => $txn->trip->cost_discount ?? $txn->trip->discount_amount ?? 0,
                    'status'          => $txn->trip->status,
                    'cancel_by_name'  => (int)$txn->trip->status === TripStatus::UserCancel->value ? 'Người thuê hủy' : ((int)$txn->trip->status === TripStatus::OwnerCancel->value ? 'Chủ xe hủy' : 'Hủy chuyến'),
                    'customer_name'   => $txn->trip->user ? $txn->trip->user->name : 'N/A',
                    'owner_name'      => ($txn->trip->car && $txn->trip->car->owner) ? $txn->trip->car->owner->name : 'N/A',
                    'penalty_deducted'=> intval($txn->amount * ($penaltyRate / 100)),
                    'tax_deducted'    => intval($txn->amount * ($taxRate / 100)),
                    'car'             => $txn->trip->car ? [
                        'id'            => $txn->trip->car->id,
                        'name'          => $txn->trip->car->name,
                        'license_plate' => $txn->trip->car->license_plate,
                        'unit_price'    => $txn->trip->car->unit_price,
                    ] : null
                ] : null
            ];
        });
    }
}
