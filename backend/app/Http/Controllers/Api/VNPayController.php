<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VNPayService;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Requests\VNPay\CreatePaymentRequest;
use Illuminate\Support\Facades\Log;
use Exception;

class VNPayController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Create Payment URL
     * POST /api/vnpay/create-payment
     */
    public function createPayment(CreatePaymentRequest $request)
    {
        try {
            $user = auth('api')->user();

            $paymentType = $request->input('payment_type');
            $amount = floatval($request->input('amount'));
            $tripId = $request->input('trip_id');

            // Generate reference and metadata using helper
            $metadata = $this->getPaymentMetadata($paymentType, $tripId, $user);
            if (!$metadata) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin chuyến đi hoặc yêu cầu thanh toán không hợp lệ.'
                ], 404);
            }

            $paymentUrl = $this->vnpayService->createPaymentUrl(
                $metadata['txnRef'],
                $amount,
                $metadata['orderInfo']
            );

            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl
            ]);

        } catch (Exception $e) {
            Log::error('VNPay createPayment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi tạo liên kết thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * IPN URL (Instant Payment Notification) - called asynchronously by VNPay backend
     * GET /api/vnpay/ipn
     */
    public function ipn(Request $request)
    {
        try {
            $inputData = $request->all();
            Log::info('VNPay IPN Received:', $inputData);

            // 1. Verify checksum signature
            if (!$this->vnpayService->verifyCallback($inputData)) {
                Log::error('VNPay IPN signature verification failed.');
                return response()->json([
                    'RspCode' => '97',
                    'Message' => 'Signature invalid'
                ]);
            }

            $txnRef = $inputData['vnp_TxnRef'] ?? '';
            $amount = floatval($inputData['vnp_Amount'] ?? 0) / 100;
            $vnpTransactionNo = $inputData['vnp_TransactionNo'] ?? '';
            $responseCode = $inputData['vnp_ResponseCode'] ?? '';

            // Extract payment type from txnRef prefix
            $meta = $this->vnpayService->parseTxnRef($txnRef);
            $paymentType = $meta['type'] ?? 'unknown';

            // 2. Check if transaction was successful on VNPay side (ResponseCode == '00')
            if ($responseCode !== '00') {
                Log::warning("VNPay IPN indicated failure or pending state for txnRef: {$txnRef}. Code: {$responseCode}");
                return response()->json([
                    'RspCode' => '00',
                    'Message' => 'Confirm Success'
                ]);
            }

            // 3. Process the actual credit logic
            $result = $this->vnpayService->processPayment($txnRef, $amount, $vnpTransactionNo, $paymentType);

            if (!$result['success']) {
                return response()->json([
                    'RspCode' => $result['code'],
                    'Message' => $result['message']
                ]);
            }

            return response()->json([
                'RspCode' => '00',
                'Message' => 'Confirm Success'
            ]);

        } catch (Exception $e) {
            Log::error('VNPay IPN exception: ' . $e->getMessage());
            return response()->json([
                'RspCode' => '99',
                'Message' => 'System error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Verify callback from frontend redirection
     * GET /api/vnpay/verify
     */
    public function verify(Request $request)
    {
        try {
            $inputData = $request->all();
            Log::info('VNPay Verify Called:', $inputData);

            if (!$this->vnpayService->verifyCallback($inputData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chữ ký không hợp lệ, thông tin thanh toán có thể bị thay đổi.'
                ], 400);
            }

            $txnRef = $inputData['vnp_TxnRef'] ?? '';
            $amount = floatval($inputData['vnp_Amount'] ?? 0) / 100;
            $vnpTransactionNo = $inputData['vnp_TransactionNo'] ?? '';
            $responseCode = $inputData['vnp_ResponseCode'] ?? '';

            $meta = $this->vnpayService->parseTxnRef($txnRef);
            $paymentType = $meta['type'] ?? 'unknown';

            if ($responseCode === '00') {
                // Trigger processing as backup if IPN is pending or failed
                $result = $this->vnpayService->processPayment($txnRef, $amount, $vnpTransactionNo, $paymentType);
                
                // Allow successful returns even if duplicate transaction was already processed by IPN
                if ($result['success'] || $result['code'] === '02') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Thanh toán thành công.',
                        'data' => [
                            'transaction_no' => $vnpTransactionNo,
                            'amount' => $amount,
                            'payment_type' => $paymentType,
                            'txn_ref' => $txnRef,
                            'meta' => $meta
                        ]
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Thanh toán thất bại hoặc đã bị hủy từ phía khách hàng.',
                'code' => $responseCode
            ], 400);

        } catch (Exception $e) {
            Log::error('VNPay Verify exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi xác thực thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate transaction reference and order information based on payment type (Helper).
     */
    private function getPaymentMetadata(string $paymentType, $tripId, $user): ?array
    {
        $timestamp = time();

        switch ($paymentType) {
            case 'rental':
                $trip = Trip::with('car')->find($tripId);
                if (!$trip) {
                    return null;
                }
                $ownerId = $trip->car->user_id ?? 0;
                return [
                    'txnRef' => "rental_{$tripId}_{$ownerId}_{$timestamp}",
                    'orderInfo' => "Thanh toan thue xe chuyen di #{$tripId}"
                ];

            case 'deposit':
                return [
                    'txnRef' => "deposit_{$user->id}_{$timestamp}",
                    'orderInfo' => "Nap tien vao vi tai khoan #{$user->id}"
                ];

            case 'penalty':
                return [
                    'txnRef' => "penalty_{$tripId}_{$user->id}_{$timestamp}",
                    'orderInfo' => "Thanh toan tien phat vi pham hop dong chuyen di #{$tripId}"
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
                    'txnRef' => "ext_{$tripId}_{$extension->id}_{$ownerId}_{$timestamp}",
                    'orderInfo' => "Thanh toan phi gia han chuyen di #{$tripId}"
                ];
        }

        return null;
    }
}
