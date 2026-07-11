<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ZaloPayService;
use App\Models\Trip;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class ZaloPayController extends Controller
{
    protected $zalopayService;

    public function __construct(ZaloPayService $zalopayService)
    {
        $this->zalopayService = $zalopayService;
    }

    /**
     * POST /api/auth/zalopay/create-payment
     */
    public function createPayment(Request $request)
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng.'
                ], 404);
            }

            $request->validate([
                'payment_type' => 'required|string|in:rental,deposit,penalty,extension',
                'amount' => 'required|numeric|min:1000',
                'trip_id' => 'required_if:payment_type,rental,penalty,extension'
            ]);

            $paymentType = $request->payment_type;
            $amount = floatval($request->amount);

            switch ($paymentType) {

                case 'rental':

                    $trip = Trip::with('car')->find($request->trip_id);

                    if (!$trip) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không tìm thấy chuyến đi.'
                        ], 404);
                    }

                    $ownerId = $trip->car->user_id;
                    $appTransId =
                        date('ymd') .
                        "_rental_" .
                        $trip->id .
                        "_" .
                        $ownerId .
                        "_" .
                        time();
                    $description = "Thanh toán thuê xe #{$trip->id}";

                    break;

                case 'deposit':

                    $appTransId =
                        date('ymd') .
                        "_deposit_" .
                        $user->id .
                        "_" .
                        time();

                    $description = "Nạp ví tài khoản #{$user->id}";

                    break;

                case 'penalty':
                    $trip = Trip::with('car')->find($request->trip_id);

                    if (!$trip) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không tìm thấy chuyến đi.'
                        ], 404);
                    }
                    $appTransId =
                        date('ymd') .
                        "_penalty_" .
                        $trip->id .
                        "_" .
                        $user->id .
                        "_" .
                        time();
                    $description = "Thanh toán tiền phạt #{$request->trip_id}";

                    break;

                case 'extension':
                    $trip = Trip::with('car')->find($request->trip_id);

                    if (!$trip) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không tìm thấy chuyến đi.'
                        ], 404);
                    }
                    $extension = $trip->extensions()->where('status', 2)->latest()->first();
                    if (!$extension) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không tìm thấy yêu cầu gia hạn đang chờ thanh toán.'
                        ], 404);
                    }
                    $ownerId = $trip->car->user_id ?? 0;
                    $appTransId =
                        date('ymd') .
                        "_ext_" .
                        $trip->id .
                        "_" .
                        $extension->id .
                        "_" .
                        $ownerId .
                        "_" .
                        time();
                    $description = "Thanh toán phí gia hạn chuyến đi #{$trip->id}";

                    break;

                default:

                    return response()->json([
                        'success' => false,
                        'message' => 'Loại thanh toán không hợp lệ.'
                    ], 400);

            }

            $result = $this->zalopayService->createPaymentUrl(
                $appTransId,
                $amount,
                $user->id,
                $description
            );

            return response()->json([
                'success' => true,
                'payment_url' => $result['order_url'] ?? null,
                'zalopay' => $result
            ]);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            Log::warning("ZaloPay createPayment - Token Expired: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Token has expired'
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            Log::warning("ZaloPay createPayment - Token Invalid: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid'
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            Log::warning("ZaloPay createPayment - JWT Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Token is absent or invalid'
            ], 401);
        } catch (\Exception $e) {

            Log::error("ZaloPay createPayment : " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }

    }

    /**
     * Callback từ ZaloPay
     */

    public function callback(Request $request)
    {
        try {

            $result = $this->zalopayService->verifyCallback($request);

            return response()->json($result);

        } catch (\Exception $e) {

            Log::error("ZaloPay Callback : " . $e->getMessage());

            return response()->json([
                'return_code' => 0,
                'return_message' => $e->getMessage()
            ]);

        }

    }

    /**
     * Frontend Verify
     */

    public function verify(Request $request)
    {
        try {
            $appTransId = $request->app_trans_id;
            if (!$appTransId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu mã giao dịch (app_trans_id).'
                ], 400);
            }

            $result = $this->zalopayService->queryTransaction($appTransId);

            // ZaloPay return_code: 1 - Thành công, 2 - Thất bại, 3 - Đang xử lý
            $returnCode = $result['return_code'] ?? -1;

            if ($returnCode == 1) {
                $amount = floatval($result['amount'] ?? 0);
                $zpTransId = $result['zp_trans_id'] ?? '';
                $meta = $this->zalopayService->parseAppTransId($appTransId);
                $paymentType = $meta['type'] ?? 'unknown';

                // Gọi processPayment dự phòng nếu callback bị chậm trễ
                $processRes = $this->zalopayService->processPayment(
                    $appTransId,
                    $amount,
                    $zpTransId,
                    $paymentType
                );

                if ($processRes['success'] || $processRes['message'] === 'Giao dịch đã xử lý.') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Thanh toán thành công.',
                        'data' => [
                            'transaction_no' => $zpTransId,
                            'amount' => $amount,
                            'payment_type' => $paymentType,
                            'provider' => 'zalopay',
                            'meta' => $meta
                        ]
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => $processRes['message'] ?? 'Xử lý giao dịch thất bại.'
                ], 400);
            } elseif ($returnCode == 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giao dịch đang được xử lý.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['return_message'] ?? 'Thanh toán thất bại hoặc đã bị hủy.'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('ZaloPay Verify error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi xác thực thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/zalopay/banks
     */
    public function getBanks(Request $request)
    {
        try {
            $result = $this->zalopayService->getBanks();
            $returnCode = $result['returncode'] ?? $result['return_code'] ?? -1;

            if ($returnCode == 1) {
                return response()->json([
                    'success' => true,
                    'banks' => $result['banks'] ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['returnmessage'] ?? $result['return_message'] ?? 'Không thể lấy danh sách ngân hàng.'
            ], 400);

        } catch (\Exception $e) {
            Log::error("ZaloPay getBanks error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}