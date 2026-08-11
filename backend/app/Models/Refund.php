<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Enum\RefundStatus;

class Refund extends Model
{
    use SoftDeletes;
    protected $table = 'refunds';
    protected $fillable = [
        'wallet_id',
        'amount',
        'status',
        'transaction_id',
        'description',
    ];

    protected $casts = [
        'status' => RefundStatus::class,
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Wallet::class,
            'id',
            'id',
            'wallet_id',
            'user_id'
        );
    }

    protected static function booted()
    {
        static::updating(function ($refund) {
            // Check if status is changed to Completed
            if ($refund->isDirty('status') && $refund->status == RefundStatus::Completed) {
                $oldStatus = $refund->getOriginal('status');
                if (!$oldStatus instanceof RefundStatus) {
                    $oldStatus = RefundStatus::tryFrom($oldStatus);
                }

                // Only deduct if previous status was pending or processing (not completed yet)
                if (in_array($oldStatus, [RefundStatus::Pending, RefundStatus::Processing])) {
                    $wallet = $refund->wallet;
                    if ($wallet) {
                        // 1. Deduct money from wallet
                        $wallet->decrement('amount', $refund->amount);

                        // 2. Find the user of this wallet
                        $user = $refund->user;
                        if ($user) {
                            // 3. Create a transaction log in history (representing successful withdrawal)
                            \App\Models\Transaction::create([
                                'user_id' => $user->id,
                                'transaction_code' => 'WD' . ($refund->transaction_id ?: strtoupper(uniqid())),
                                'amount' => -$refund->amount,
                                'prepay' => 0,
                                'description' => $refund->description ?: ('Rút tiền về ngân hàng thành công (Yêu cầu #' . $refund->id . ')')
                            ]);
                        }
                    }
                }
            }
        });
    }
}
