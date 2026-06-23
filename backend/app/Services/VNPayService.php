<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VNPayService
{
    protected $tmnCode;
    protected $hashSecret;
    protected $url;
    protected $returnUrl;

    public function __construct()
    {
        $this->tmnCode = config('vnpay.tmn_code');
        $this->hashSecret = config('vnpay.hash_secret');
        $this->url = config('vnpay.url');
        $this->returnUrl = config('vnpay.return_url');
    }

    /**
     * Generate VNPay Payment URL
     *
     * @param string $txnRef Unique code identifying the transaction, e.g. rental_1_1718818818
     * @param float $amount Real amount in VND
     * @param string $orderInfo Payment description
     * @return string Checkout redirect URL
     */
    public function createPaymentUrl(string $txnRef, float $amount, string $orderInfo): string
    {
        $vnp_Amount = $amount * 100; // VNPay uses VND * 100
        $vnp_IpAddr = request()->ip() ?: '127.0.0.1';

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->tmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $orderInfo,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $this->returnUrl,
            "vnp_TxnRef" => $txnRef,
        ];

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $paymentUrl = $this->url . "?" . $query;
        if (isset($this->hashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
            $paymentUrl .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $paymentUrl;
    }

    /**
     * Verify callback data signature
     *
     * @param array $inputData Query parameters from VNPay request
     * @return bool True if signature matches
     */
    public function verifyCallback(array $inputData): bool
    {
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        
        // Remove hash parameters before verifying signature
        unset($inputData['vnp_SecureHashType']);
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
        
        return hash_equals(strtolower($secureHash), strtolower($vnp_SecureHash));
    }

    /**
     * Parse txnRef to retrieve transaction metadata
     *
     * @param string $txnRef E.g. rental_12_5_1718818818
     * @return array Parsed metadata array
     */
    public function parseTxnRef(string $txnRef): array
    {
        $parts = explode('_', $txnRef);
        $type = $parts[0] ?? 'unknown';
        
        if ($type === 'rental') {
            return [
                'type' => 'rental',
                'trip_id' => isset($parts[1]) ? intval($parts[1]) : null,
                'owner_id' => isset($parts[2]) ? intval($parts[2]) : null,
            ];
        }
        
        if ($type === 'deposit') {
            return [
                'type' => 'deposit',
                'user_id' => isset($parts[1]) ? intval($parts[1]) : null,
            ];
        }

        if ($type === 'penalty') {
            return [
                'type' => 'penalty',
                'trip_id' => isset($parts[1]) ? intval($parts[1]) : null,
                'user_id' => isset($parts[2]) ? intval($parts[2]) : null,
            ];
        }
        
        return ['type' => 'unknown'];
    }

    /**
     * Process payment logic based on type (Rental, Deposit, Penalty)
     *
     * @param string $txnRef E.g. rental_12_5_1718818818
     * @param float $amount Real amount in VND (already divided by 100)
     * @param string $vnpTransactionNo VNPay transaction code (vnp_TransactionNo)
     * @param string $paymentType payment type
     * @return array Status array
     */
    public function processPayment(
        string $txnRef,
        float $amount,
        string $vnpTransactionNo,
        string $paymentType
    ): array {
        $meta = $this->parseTxnRef($txnRef);

        return DB::transaction(function () use ($txnRef, $amount, $vnpTransactionNo, $paymentType, $meta) {
            // Check if transaction already processed to prevent double crediting
            $exists = Transaction::where('transaction_code', $vnpTransactionNo)->exists();
            if ($exists) {
                Log::warning("VNPay transaction {$vnpTransactionNo} was already processed.");
                return [
                    'success' => false,
                    'code' => '02',
                    'message' => 'Giao dịch đã được xác nhận trước đó.'
                ];
            }

            switch ($paymentType) {
                case 'rental':
                    $tripId = $meta['trip_id'] ?? null;
                    $ownerId = $meta['owner_id'] ?? null;

                    $trip = Trip::find($tripId);
                    if (!$trip) {
                        Log::error("VNPay processPayment - Trip ID {$tripId} not found.");
                        return [
                            'success' => false,
                            'code' => '01',
                            'message' => 'Không tìm thấy chuyến đi.'
                        ];
                    }

                    // 1. Credit the Car Owner's wallet
                    $owner = User::find($ownerId);
                    if ($owner) {
                        if (!$owner->wallet_id) {
                            $wallet = Wallet::create(['amount' => 0]);
                            $owner->wallet_id = $wallet->id;
                            $owner->save();
                        } else {
                            $wallet = $owner->wallet;
                        }
                        $wallet->increment('amount', $amount);
                    } else {
                        Log::warning("VNPay processPayment - Car owner ID {$ownerId} not found, money will only be logged.");
                    }

                    // 2. Create the Transaction record
                    Transaction::create([
                        'user_id' => $trip->user_id, // customer paying
                        'transaction_code' => $vnpTransactionNo,
                        'amount' => $amount,
                        'prepay' => $amount,
                        'trip_id' => $trip->id
                    ]);

                    // 3. Update trip status to ongoing/active (1)
                    $trip->status = 1;
                    $trip->save();

                    Log::info("VNPay rental payment successful for trip {$tripId}, credited owner {$ownerId} amount {$amount}.");
                    break;

                case 'deposit':
                    $userId = $meta['user_id'] ?? null;
                    $user = User::find($userId);

                    if (!$user) {
                        Log::error("VNPay processPayment - User ID {$userId} not found for deposit.");
                        return [
                            'success' => false,
                            'code' => '01',
                            'message' => 'Không tìm thấy người dùng.'
                        ];
                    }

                    // 1. Credit the user's wallet
                    if (!$user->wallet_id) {
                        $wallet = Wallet::create(['amount' => 0]);
                        $user->wallet_id = $wallet->id;
                        $user->save();
                    } else {
                        $wallet = $user->wallet;
                    }
                    $wallet->increment('amount', $amount);

                    // 2. Log transaction
                    Transaction::create([
                        'user_id' => $user->id,
                        'transaction_code' => $vnpTransactionNo,
                        'amount' => $amount,
                        'prepay' => 0,
                        'trip_id' => null
                    ]);

                    Log::info("VNPay deposit payment successful for user {$userId}, credited amount {$amount}.");
                    break;

                case 'penalty':
                    $tripId = $meta['trip_id'] ?? null;
                    $userId = $meta['user_id'] ?? null;

                    $user = User::find($userId);
                    if (!$user) {
                        Log::error("VNPay processPayment - User ID {$userId} not found for penalty.");
                        return [
                            'success' => false,
                            'code' => '01',
                            'message' => 'Không tìm thấy người dùng.'
                        ];
                    }

                    // 1. Log transaction representing a penalty payment
                    Transaction::create([
                        'user_id' => $user->id,
                        'transaction_code' => $vnpTransactionNo,
                        'amount' => $amount,
                        'prepay' => 0,
                        'trip_id' => $tripId
                    ]);

                    Log::info("VNPay penalty payment successful for trip {$tripId}, user {$userId}, amount {$amount}.");
                    break;

                default:
                    Log::warning("VNPay processPayment - Unrecognized payment type: {$paymentType}");
                    return [
                        'success' => false,
                        'code' => '99',
                        'message' => 'Loại thanh toán không được hỗ trợ.'
                    ];
            }

            return [
                'success' => true,
                'code' => '00',
                'message' => 'Thanh toán thành công.'
            ];
        });
    }
}
