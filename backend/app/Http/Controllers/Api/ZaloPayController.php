<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ZaloPayService;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Requests\ZaloPay\CreatePaymentRequest;
use Illuminate\Support\Facades\Log;
use Exception;

class ZaloPayController extends Controller
{
    protected $zalopayService;

    public function __construct(ZaloPayService $zalopayService)
    {
        $this->zalopayService = $zalopayService;
    }

    /**
     * Create payment URL for ZaloPay order
     * POST /api/auth/zalopay/create-payment
     */
    public function createPayment(CreatePaymentRequest $request)
    {
        try {
            $user = auth('api')->user();

            $paymentType = $request->input('payment_type');
            $amount = floatval($request->input('amount'));
            $tripId = $request->input('trip_id');

            // Generate metadata using helper
            $metadata = $this->getPaymentMetadata($paymentType, $tripId, $user);
            if (!$metadata) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy chuyến đi hoặc yêu cầu thanh toán không hợp lệ.'
                ], 404);
            }

            $result = $this->zalopayService->createPaymentUrl(
                $metadata['appTransId'],
                $amount,
                $user->id,
                $metadata['description']
            );

            return response()->json([
                'success' => true,
                'payment_url' => $result['order_url'] ?? null,
                'zalopay' => $result
            ]);

        } catch (Exception $e) {
            Log::error("ZaloPay createPayment: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ZaloPay Webhook Callback
     * POST /api/zalopay/callback
     */
    public function callback(Request $request)
    {
        try {
            $result = $this->zalopayService->verifyCallback($request);
            return response()->json($result);
        } catch (Exception $e) {
            Log::error("ZaloPay Callback: " . $e->getMessage());
            return response()->json([
                'return_code' => 0,
                'return_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Frontend redirect verification page check
     * GET /api/zalopay/verify
     */
    public function verify(Request $request)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để xem kết quả thanh toán.'
                ], 401);
            }

            $appTransId = $request->input('app_trans_id');
            if (!$appTransId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu mã giao dịch (app_trans_id).'
                ], 400);
            }

            $result = $this->zalopayService->queryTransaction($appTransId);

            // ZaloPay return_code: 1 - Success, 2 - Failed, 3 - Processing
            $returnCode = $result['return_code'] ?? -1;

            if ($returnCode == 1) {
                $amount = floatval($result['amount'] ?? 0);
                $zpTransId = $result['zp_trans_id'] ?? '';
                $meta = $this->zalopayService->parseAppTransId($appTransId);
                $paymentType = $meta['type'] ?? 'unknown';

                if (!$this->isTransactionOwnedByUser($meta, $paymentType, $user->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn không có quyền xem giao dịch này.'
                    ], 403);
                }

                // Backup processing if callback delay occurs
                $processRes = $this->zalopayService->processPayment(
                    $appTransId,
                    $amount,
                    $zpTransId,
                    $paymentType
                );

                if ($processRes['success'] || $processRes['message'] == 'Giao dịch đã xử lý.') {
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

        } catch (Exception $e) {
            Log::error('ZaloPay Verify error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi xác thực thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }

    private function isTransactionOwnedByUser(array $meta, string $paymentType, int $userId): bool
    {
        switch ($paymentType) {
            case 'rental':
            case 'extension':
                $tripId = $meta['trip_id'] ?? null;
                if (!$tripId) {
                    return false;
                }

                $trip = Trip::find($tripId);
                return $trip && (int) $trip->user_id === $userId;

            case 'deposit':
            case 'penalty':
                return isset($meta['user_id']) && (int) $meta['user_id'] === $userId;

            default:
                return false;
        }
    }

    /**
     * Get list of supported banks from ZaloPay
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

        } catch (Exception $e) {
            Log::error("ZaloPay getBanks error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate ZaloPay app transaction ID and description metadata (Helper).
     */
    private function getPaymentMetadata(string $paymentType, $tripId, $user): ?array
    {
        $timestamp = time();
        $datePrefix = date('ymd');

        switch ($paymentType) {
            case 'rental':
                $trip = Trip::with('car')->find($tripId);
                if (!$trip) {
                    return null;
                }
                $ownerId = $trip->car->user_id ?? 0;
                return [
                    'appTransId'  => "{$datePrefix}_rental_{$trip->id}_{$ownerId}_{$timestamp}",
                    'description' => "Thanh toán thuê xe " . ($trip->trip_code ?? "#{$trip->id}")
                ];

            case 'deposit':
                return [
                    'appTransId'  => "{$datePrefix}_deposit_{$user->id}_{$timestamp}",
                    'description' => "Nạp ví tài khoản #{$user->id}"
                ];

            case 'penalty':
                $trip = Trip::with('car')->find($tripId);
                if (!$trip) {
                    return null;
                }
                return [
                    'appTransId'  => "{$datePrefix}_penalty_{$trip->id}_{$user->id}_{$timestamp}",
                    'description' => "Thanh toán tiền phạt " . ($trip->trip_code ?? "#{$tripId}")
                ];

            case 'extension':
                $trip = Trip::with('car')->find($tripId);
                if (!$trip) {
                    return null;
                }
                $extension = $trip->extensions()->where('status', 2)->latest()->first();
                if (!$extension) {
                    return null;
                }
                $ownerId = $trip->car->user_id ?? 0;
                return [
                    'appTransId'  => "{$datePrefix}_ext_{$trip->id}_{$extension->id}_{$ownerId}_{$timestamp}",
                    'description' => "Thanh toán phí gia hạn chuyến đi " . ($trip->trip_code ?? "#{$trip->id}")
                ];
        }

        return null;
    }
}