<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Car;
use App\Models\Review;
use App\Models\Trip;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class WalletController extends Controller
{
    /**
     * API Lấy chi tiết thông tin ví và sao kê giao dịch
     * GET /api/auth/wallet
     */
    public function getWalletDetails(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Đảm bảo user có ví
            if (!$user->wallet_id) {
                $wallet = Wallet::create(['amount' => 0]);
                $user->wallet_id = $wallet->id;
                $user->save();
            } else {
                $wallet = $user->wallet;
            }

            // Lấy danh sách giao dịch của user
            $transactionsQuery = Transaction::where('user_id', $user->id)
                ->with(['trip.car', 'trip.user'])
                ->orderBy('created_at', 'desc');

            $transactions = $transactionsQuery->get();

            // Tính toán tổng hợp các khoản
            $completedTripsChange = 0;
            $depositWithdrawalChange = 0;
            $cancelledTripsChange = 0;

            foreach ($transactions as $txn) {
                if ($txn->trip_id) {
                    $trip = $txn->trip;
                    // status: 0 - chưa bắt đầu, 1 - đang diễn ra, 2 - đã hoàn thành, 3 - đã hủy bởi người dùng, 4 - đã hủy bởi chủ xe
                    if ($trip && ($trip->status == 3 || $trip->status == 4)) {
                        $cancelledTripsChange += $txn->amount;
                    } else {
                        $completedTripsChange += $txn->amount;
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

            // Giả định Thuế kinh doanh = 10% doanh thu chuyến đi hoàn thành
            $taxDeducted = intval($completedTripsChange * 0.1);
            $ownerIncome = $completedTripsChange - $taxDeducted;

            // Tính toán rating của chủ xe (trung bình cộng rating các đánh giá loại 1 của các xe thuộc chủ xe)
            $carIds = Car::where('user_id', $user->id)->pluck('id');
            $rating = Review::whereIn('car_id', $carIds)
                ->where('review_type', 1)
                ->avg('rating');

            $rating = $rating ? round(floatval($rating), 1) : 5.0;

            // Tính số chuyến đi thành công (status = 4) của các xe thuộc chủ xe
            $completedTripsCount = Trip::whereIn('car_id', $carIds)
                ->where('status', TripStatus::Complete->value)
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'balance' => $wallet->amount,
                    'rating' => $rating,
                    'completed_trips_count' => $completedTripsCount,
                    'response_rate' => 100,
                    'response_time' => '5 phút',
                    'accept_rate' => 100,
                    'transactions' => $transactions->map(function ($txn) {
                        return [
                            'id' => $txn->id,
                            'transaction_code' => $txn->transaction_code,
                            'amount' => $txn->amount,
                            'prepay' => $txn->prepay,
                            'created_at' => $txn->created_at->format('d/m/Y H:i'),
                            'trip' => $txn->trip ? [
                                'id' => $txn->trip->id,
                                'start_at' => $txn->trip->start_at ? date('d/m/Y', strtotime($txn->trip->start_at)) : null,
                                'end_at' => $txn->trip->end_at ? date('d/m/Y', strtotime($txn->trip->end_at)) : null,
                                'created_at' => $txn->trip->created_at ? $txn->trip->created_at->format('d/m/Y') : null,
                                'cost' => $txn->trip->cost,
                                'discount_amount' => $txn->trip->discount_amount ?? 0,
                                'status' => $txn->trip->status,
                                'customer_name' => $txn->trip->user ? $txn->trip->user->name : 'N/A',
                                'service_fee' => intval($txn->trip->cost * 0.1),
                                'tax_deducted' => intval($txn->amount * 0.1),
                                'car' => $txn->trip->car ? [
                                    'id' => $txn->trip->car->id,
                                    'name' => $txn->trip->car->name,
                                    'license_plate' => $txn->trip->car->license_plate,
                                    'unit_price' => $txn->trip->car->unit_price,
                                ] : null
                            ] : null
                        ];
                    }),
                    'summary' => [
                        'completed_trips_change' => $completedTripsChange,
                        'deposit_withdrawal_change' => $depositWithdrawalChange,
                        'cancelled_trips_change' => $cancelledTripsChange,
                        'total_change' => $totalChange,
                        'start_balance' => $startBalance,
                        'end_balance' => $wallet->amount,
                        'tax_deducted' => $taxDeducted,
                        'owner_income' => $ownerIncome
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}
