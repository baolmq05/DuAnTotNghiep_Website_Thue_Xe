<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            'wallet_id',
            'wallet_id',
            'id'
        );
    }

    protected static function booted()
    {
        static::updating(function ($refund) {
            // Check if status is changed to failed or canceled
            if ($refund->isDirty('status') && in_array($refund->status, ['failed', 'canceled'])) {
                $oldStatus = $refund->getOriginal('status');

                // Only refund if previous status was pending or processing (held amount)
                if (in_array($oldStatus, ['pending', 'processing'])) {
                    $wallet = $refund->wallet;
                    if ($wallet) {
                        // 1. Return money to wallet
                        $wallet->increment('amount', $refund->amount);

                        // 2. Find the user of this wallet
                        $user = $refund->user;
                        if ($user) {
                            // 3. Create a transaction log in history
                            \App\Models\Transaction::create([
                                'user_id' => $user->id,
                                'transaction_code' => 'RF' . strtoupper(uniqid()),
                                'amount' => $refund->amount,
                                'prepay' => 0,
                                'description' => 'Hoàn trả tiền yêu cầu rút/hoàn tiền thất bại/hủy (Yêu cầu #' . $refund->id . ')'
                            ]);
                        }
                    }
                }
            }
        });
    }
}
