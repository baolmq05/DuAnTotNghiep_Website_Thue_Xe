<?php

namespace App\Services;

use App\Enum\TripStatus;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SeePayService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('sepay.api_key');
    }

    /**
     * Verify incoming webhook headers
     */
    public function verifyWebhook(array $headers): bool
    {
        $authHeader = $headers['authorization'] ?? $headers['Authorization'] ?? '';
        if (is_array($authHeader)) {
            $authHeader = $authHeader[0] ?? '';
        }
        
        if (empty($authHeader)) {
            return false;
        }

        // Expected format: "Apikey YOUR_API_KEY"
        if (preg_match('/Apikey\s+(.*)/i', $authHeader, $matches)) {
            $receivedKey = trim($matches[1]);
            return hash_equals($this->apiKey, $receivedKey);
        }

        return false;
    }

    /**
     * Parse payment description to get metadata
     */
    public function parseContent(string $content): array
    {
        $content = strtoupper($content);
        
        if (preg_match('/RENTAL\s+(\d+)/', $content, $matches)) {
            return [
                'type' => 'rental',
                'trip_id' => intval($matches[1]),
            ];
        }
        
        if (preg_match('/DEPOSIT\s+(\d+)/', $content, $matches)) {
            return [
                'type' => 'deposit',
                'user_id' => intval($matches[1]),
            ];
        }

        if (preg_match('/PENALTY\s+(\d+)/', $content, $matches)) {
            return [
                'type' => 'penalty',
                'trip_id' => intval($matches[1]),
            ];
        }

        if (preg_match('/EXT\s+(\d+)/', $content, $matches)) {
            return [
                'type' => 'extension',
                'trip_id' => intval($matches[1]),
            ];
        }
        
        return ['type' => 'unknown'];
    }

    /**
     * Process credit logic based on transfer details
     */
    public function processPayment(
        string $content,
        float $amount,
        string $transactionNo
    ): array {
        $meta = $this->parseContent($content);
        $paymentType = $meta['type'] ?? 'unknown';

        return DB::transaction(function () use ($content, $amount, $transactionNo, $paymentType, $meta) {
            // Check if transaction already processed
            $exists = Transaction::where('transaction_code', $transactionNo)->exists();
            if ($exists) {
                Log::warning("SeePay transaction {$transactionNo} was already processed.");
                return [
                    'success' => false,
                    'code' => '02',
                    'message' => 'Giao dịch đã được xác nhận trước đó.'
                ];
            }

            switch ($paymentType) {
                case 'rental':
                    $tripId = $meta['trip_id'] ?? null;
                    $trip = Trip::with(['car', 'user'])->find($tripId);
                    if (!$trip) {
                        Log::error("SeePay processPayment - Trip ID {$tripId} not found.");
                        return [
                            'success' => false,
                            'code' => '01',
                            'message' => 'Không tìm thấy chuyến đi.'
                        ];
                    }

                    $ownerId = $trip->car->user_id ?? 0;

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
                    }

                    // 2. Create the Transaction record
                    Transaction::create([
                        'user_id' => $trip->user_id,
                        'transaction_code' => $transactionNo,
                        'amount' => $amount,
                        'prepay' => $amount,
                        'trip_id' => $trip->id
                    ]);

                    // 3. Update trip status to Confirmed/active
                    $trip->status = TripStatus::Confirmed->value;
                    $trip->save();

                    // 4. Notifications
                    $carName = $trip->car->name ?? 'xe';
                    $renterName = $trip->user->name ?? 'Khách hàng';

                    if ($ownerId) {
                        \App\Models\Notification::create([
                            'user_id' => $ownerId,
                            'message' => "Khách hàng {$renterName} đã thanh toán thành công tiền thuê xe '{$carName}' cho chuyến đi #{$trip->id} qua Chuyển khoản ngân hàng (SeePay).",
                            'is_read' => '0',
                        ]);
                    }

                    if ($trip->user_id) {
                        \App\Models\Notification::create([
                            'user_id' => $trip->user_id,
                            'message' => "Bạn đã thanh toán thành công tiền thuê xe '{$carName}' cho chuyến đi #{$trip->id} qua Chuyển khoản ngân hàng (SeePay). Chuyến đi đã được xác nhận.",
                            'is_read' => '0',
                        ]);
                    }

                    Log::info("SeePay rental payment successful for trip {$tripId}, credited owner {$ownerId} amount {$amount}.");
                    break;

                case 'deposit':
                    $userId = $meta['user_id'] ?? null;
                    $user = User::find($userId);

                    if (!$user) {
                        Log::error("SeePay processPayment - User ID {$userId} not found for deposit.");
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
                        'transaction_code' => $transactionNo,
                        'amount' => $amount,
                        'prepay' => 0,
                        'trip_id' => null
                    ]);

                    Log::info("SeePay deposit payment successful for user {$userId}, credited amount {$amount}.");
                    break;

                case 'penalty':
                    $tripId = $meta['trip_id'] ?? null;
                    $trip = Trip::find($tripId);
                    if (!$trip) {
                        Log::error("SeePay processPayment - Trip ID {$tripId} not found for penalty.");
                        return [
                            'success' => false,
                            'code' => '01',
                            'message' => 'Không tìm thấy chuyến đi.'
                        ];
                    }

                    // 1. Log transaction representing a penalty payment
                    Transaction::create([
                        'user_id' => $trip->user_id,
                        'transaction_code' => $transactionNo,
                        'amount' => $amount,
                        'prepay' => 0,
                        'trip_id' => $tripId
                    ]);

                    Log::info("SeePay penalty payment successful for trip {$tripId}, amount {$amount}.");
                    break;

                case 'extension':
                    $tripId = $meta['trip_id'] ?? null;
                    $trip = Trip::with('car')->find($tripId);
                    if (!$trip) {
                        Log::error("SeePay processPayment - Trip ID {$tripId} not found.");
                        return [
                            'success' => false,
                            'code' => '01',
                            'message' => 'Không tìm thấy chuyến đi.'
                        ];
                    }
                    $extension = $trip->extensions()->where('status', 2)->latest()->first();
                    if (!$extension) {
                        return [
                            'success' => true,
                            'code' => '00',
                            'message' => 'Gia hạn đã được xác nhận trước đó.'
                        ];
                    }

                    $ownerId = $trip->car->user_id ?? 0;
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
                    }

                    Transaction::create([
                        'user_id' => $trip->user_id,
                        'transaction_code' => $transactionNo,
                        'amount' => $amount,
                        'prepay' => 0,
                        'trip_id' => $trip->id
                    ]);

                    $extension->update(['status' => 3]);
                    $trip->update([
                        'end_at' => $extension->end_date,
                        'extended_end_at' => null,
                        'cost' => $trip->cost + $amount,
                    ]);

                    \App\Models\Notification::create([
                        'user_id' => $trip->car->user_id ?? ($ownerId ?? 0),
                        'message' => "Khách hàng đã thanh toán thành công phí gia hạn chuyến đi #{$trip->id} qua Chuyển khoản ngân hàng (SeePay). Thời gian trả xe mới là " . date('H:i d/m/Y', strtotime($extension->end_date)) . ".",
                        'is_read' => '0',
                    ]);

                    if ($trip->user_id) {
                        \App\Models\Notification::create([
                            'user_id' => $trip->user_id,
                            'message' => "Bạn đã thanh toán thành công phí gia hạn chuyến đi #{$trip->id} qua Chuyển khoản ngân hàng (SeePay). Thời gian trả xe mới là " . date('H:i d/m/Y', strtotime($extension->end_date)) . ".",
                            'is_read' => '0',
                        ]);
                    }
                    break;

                default:
                    Log::warning("SeePay processPayment - Unrecognized payment code: {$content}");
                    return [
                        'success' => false,
                        'code' => '99',
                        'message' => 'Nội dung chuyển khoản không được hỗ trợ hoặc không đúng cú pháp.'
                    ];
            }

            return [
                'success' => true,
                'code' => '00',
                'message' => 'Thanh toán thành công.'
            ];
        });
    }

    /**
     * Parse refund content to get refund ID
     */
    public function parseRefundContent(string $content): ?int
    {
        $content = strtoupper($content);
        if (preg_match('/REF\s+(\d+)/', $content, $matches)) {
            return intval($matches[1]);
        }
        return null;
    }

    /**
     * Process cash out (refund payout) from admin to customer
     */
    public function processRefundPayout(
        string $content,
        float $amount,
        string $transactionNo
    ): array {
        $refundId = $this->parseRefundContent($content);
        if (!$refundId) {
            return [
                'success' => false,
                'message' => 'Nội dung chuyển khoản không hợp lệ cho yêu cầu hoàn tiền.'
            ];
        }

        return DB::transaction(function () use ($refundId, $amount, $transactionNo) {
            $refund = \App\Models\Refund::find($refundId);
            if (!$refund) {
                Log::error("SeePay Webhook - Refund ID {$refundId} not found.");
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy yêu cầu hoàn tiền.'
                ];
            }

            if ($refund->status === 'completed') {
                return [
                    'success' => true,
                    'message' => 'Yêu cầu hoàn tiền đã hoàn thành trước đó.'
                ];
            }

            if (!in_array($refund->status, ['pending', 'processing'])) {
                return [
                    'success' => false,
                    'message' => 'Yêu cầu hoàn tiền ở trạng thái không thể xử lý.'
                ];
            }

            // Update refund status to completed and set transaction ID
            $refund->update([
                'status' => 'completed',
                'transaction_id' => $transactionNo,
                'description' => 'Hoàn tiền tự động qua SeePay payout.'
            ]);

            Log::info("SeePay Webhook - Refund {$refundId} automatically marked COMPLETED via cash-out webhook.");

            return [
                'success' => true,
                'message' => 'Xử lý hoàn tiền tự động thành công.'
            ];
        });
    }
}
