<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SeePayService;
use App\Models\Trip;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SeePay\GetPaymentInfoRequest;
use App\Http\Requests\SeePay\CheckStatusRequest;
use Exception;

class SeePayController extends Controller
{
    protected $sepayService;

    public function __construct(SeePayService $sepayService)
    {
        $this->sepayService = $sepayService;
    }

    /**
     * Public Webhook endpoint for SeePay
     * POST /api/sepay/webhook
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Verify authenticity using headers ApiKey
            if (!$this->sepayService->verifyWebhook($request->headers->all())) {
                Log::warning('SeePay Webhook - Unverified access attempt.');
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $data = $request->json()->all();
            Log::info('SeePay Webhook Received:', $data);

            $transferType = $data['transferType'] ?? 'in';
            $content = $data['content'] ?? '';
            $amount = floatval($data['transferAmount'] ?? 0);
            $referenceCode = $data['referenceCode'] ?? '';

            if (empty($content) || $amount <= 0 || empty($referenceCode)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing transaction data'
                ], 400);
            }

            if ($transferType == 'out') {
                $result = $this->sepayService->processRefundPayout($content, $amount, $referenceCode);
            } else {
                $result = $this->sepayService->processPayment($content, $amount, $referenceCode);
            }

            if ($result['success'] || (isset($result['code']) && $result['code'] == '02')) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);

        } catch (Exception $e) {
            Log::error('SeePay Webhook Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'System error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Payment configuration and generated description
     * GET /api/sepay/payment-info
     */
    public function getPaymentInfo(GetPaymentInfoRequest $request)
    {
        try {
            $user = auth('api')->user();

            $paymentType = $request->input('payment_type');
            $tripId = $request->input('trip_id');

            $trip = null;
            if ($tripId && in_array($paymentType, ['rental', 'penalty', 'extension'])) {
                $trip = Trip::find($tripId);
            }

            // Generate correct transfer description
            switch ($paymentType) {
                case 'rental':
                    $description = "RENTAL " . ($trip && $trip->trip_code ? $trip->trip_code : $tripId);
                    break;
                case 'deposit':
                    $description = "DEPOSIT " . $user->id;
                    break;
                case 'penalty':
                    $description = "PENALTY " . ($trip && $trip->trip_code ? $trip->trip_code : $tripId);
                    break;
                case 'extension':
                    $description = "EXT " . ($trip && $trip->trip_code ? $trip->trip_code : $tripId);
                    break;
                default:
                    $description = "PAY";
            }

            return response()->json([
                'success' => true,
                'bank_name' => config('sepay.bank_name'),
                'account_number' => config('sepay.account_number'),
                'account_name' => config('sepay.account_name'),
                'description' => $description
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if payment has been received (Polling)
     * GET /api/sepay/check-status
     */
    public function checkStatus(CheckStatusRequest $request)
    {
        try {
            $user = auth('api')->user();

            $paymentType = $request->input('payment_type');
            $id = intval($request->input('id'));
            $amount = floatval($request->input('amount'));

            $isPaid = false;

            switch ($paymentType) {
                case 'rental':
                    // Check if trip status changed to Confirmed (2) and transaction exists
                    $trip = Trip::find($id);
                    if ($trip && $trip->status == 2) {
                        $isPaid = Transaction::where('trip_id', $id)
                            ->where('amount', $amount)
                            ->exists();
                    }
                    break;

                case 'extension':
                    $trip = Trip::find($id);
                    if ($trip) {
                        $extension = $trip->extensions()->where('status', 3)->latest()->first();
                        if ($extension) {
                            $isPaid = Transaction::where('trip_id', $id)
                                ->where('amount', $amount)
                                ->exists();
                        }
                    }
                    break;

                case 'penalty':
                    $isPaid = Transaction::where('trip_id', $id)
                        ->where('amount', $amount)
                        ->exists();
                    break;

                case 'deposit':
                    // Check if a deposit transaction exists for the user in the last 30 minutes
                    $isPaid = Transaction::where('user_id', $user->id)
                        ->whereNull('trip_id')
                        ->where('amount', $amount)
                        ->where('created_at', '>=', now()->subMinutes(30))
                        ->exists();
                    break;
            }

            return response()->json([
                'success' => true,
                'paid' => $isPaid
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
