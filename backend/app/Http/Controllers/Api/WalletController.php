<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Car;
use App\Models\Review;
use App\Models\Trip;
use App\Models\Refund;
use App\Models\PendingBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Wallet\WithdrawRequest;
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

            // Ensure the user has a wallet
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['amount' => 0, 'hold_balance' => 0]
            );

            // Fetch transaction history
            $transactions = $this->getTransactionHistory($user->id);

            // Calculate summaries
            $summary = $this->calculateSummary($transactions, $wallet);

            // Fetch owner profile stats
            $rating = $this->getOwnerRating($user->id);
            $completedTripsCount = $this->getCompletedTripsCount($user->id);
            $pendingBalance = $this->getPendingBalance($user->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'balance'               => $wallet->amount,
                    'pending_balance'       => floatval($pendingBalance),
                    'rating'                => $rating,
                    'completed_trips_count' => $completedTripsCount,
                    'response_rate'         => 100,
                    'response_time'         => '5 phút',
                    'accept_rate'           => 100,
                    'transactions'          => $this->formatTransactions($transactions),
                    'summary'               => $summary
                ]
            ]);

        } catch (Exception $e) {
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

            // Perform DB transaction to deduct balance and record history
            DB::transaction(function () use ($user, $wallet, $amount, $request) {
                // 1. Deduct wallet amount
                $wallet->decrement('amount', $amount);

                // 2. Create Refund request
                Refund::create([
                    'wallet_id'   => $wallet->id,
                    'amount'      => $amount,
                    'status'      => 'pending',
                    'description' => $request->input('description') ?: ('Rút tiền về ngân hàng ' . $user->bank_name . ' (' . $user->bank_account_number . ')'),
                ]);

                // 3. Create Transaction log
                Transaction::create([
                    'user_id'          => $user->id,
                    'transaction_code' => 'WD' . strtoupper(uniqid()),
                    'amount'           => -$amount,
                    'prepay'           => 0,
                ]);
            });

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
    private function calculateSummary($transactions, Wallet $wallet): array
    {
        $completedTripsChange = 0;
        $depositWithdrawalChange = 0;
        $cancelledTripsChange = 0;

        foreach ($transactions as $txn) {
            if ($txn->trip_id) {
                $trip = $txn->trip;
                if ($trip && (int)$trip->status === TripStatus::Complete->value) {
                    $completedTripsChange += $txn->amount;
                } elseif ($trip && in_array((int)$trip->status, [TripStatus::UserCancel->value, TripStatus::OwnerCancel->value])) {
                    $cancelledTripsChange += $txn->amount;
                }
            } else {
                $depositWithdrawalChange += $txn->amount;
            }
        }

        $totalChange = $completedTripsChange + $depositWithdrawalChange + $cancelledTripsChange;
        
        $startBalance = $wallet->amount - $totalChange;
        if ($startBalance < 0) {
            $startBalance = 0;
        }

        // Deduct 10% tax for completed trips
        $taxDeducted = intval($completedTripsChange * 0.1);
        $ownerIncome = $completedTripsChange - $taxDeducted;

        return [
            'completed_trips_change'    => $completedTripsChange,
            'deposit_withdrawal_change' => $depositWithdrawalChange,
            'cancelled_trips_change'    => $cancelledTripsChange,
            'total_change'              => $totalChange,
            'start_balance'             => $startBalance,
            'end_balance'               => $wallet->amount,
            'tax_deducted'              => $taxDeducted,
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
        return $transactions->map(function ($txn) {
            return [
                'id'               => $txn->id,
                'transaction_code' => $txn->transaction_code,
                'amount'           => $txn->amount,
                'prepay'           => $txn->prepay,
                'created_at'       => $txn->created_at->format('d/m/Y H:i'),
                'trip'             => $txn->trip ? [
                    'id'              => $txn->trip->id,
                    'start_at'        => $txn->trip->start_at ? date('d/m/Y', strtotime($txn->trip->start_at)) : null,
                    'end_at'          => $txn->trip->end_at ? date('d/m/Y', strtotime($txn->trip->end_at)) : null,
                    'created_at'      => $txn->trip->created_at ? $txn->trip->created_at->format('d/m/Y') : null,
                    'cost'            => $txn->trip->cost,
                    'discount_amount' => $txn->trip->cost_discount ?? $txn->trip->discount_amount ?? 0,
                    'status'          => $txn->trip->status,
                    'customer_name'   => $txn->trip->user ? $txn->trip->user->name : 'N/A',
                    'owner_name'      => ($txn->trip->car && $txn->trip->car->owner) ? $txn->trip->car->owner->name : 'N/A',
                    'service_fee'     => intval($txn->trip->cost * 0.1),
                    'tax_deducted'    => intval($txn->amount * 0.1),
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
