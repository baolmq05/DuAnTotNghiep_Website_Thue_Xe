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
        static::created(function ($refund) {
            // Deduct the wallet balance immediately when a withdrawal request is created as Active (Pending/Processing)
            $deductedStatuses = [
                RefundStatus::Pending,
                RefundStatus::Processing,
                RefundStatus::Completed
            ];

            if (in_array($refund->status, $deductedStatuses)) {
                $wallet = $refund->wallet;
                if ($wallet) {
                    $wallet->decrement('amount', $refund->amount);

                    // If created directly as completed, also log transaction
                    if ($refund->status == RefundStatus::Completed) {
                        $user = $refund->user;
                        if ($user) {
                            \App\Models\Transaction::create([
                                'user_id' => $user->id,
                                'transaction_code' => 'WD' . ($refund->transaction_id ?: $refund->id),
                                'amount' => -$refund->amount,
                                'prepay' => 0,
                            ]);
                        }
                    }
                }
            }
        });

        static::updating(function ($refund) {
            if ($refund->isDirty('status')) {
                $oldStatus = $refund->getOriginal('status');
                if (!$oldStatus instanceof RefundStatus) {
                    $oldStatus = RefundStatus::tryFrom($oldStatus);
                }
                $newStatus = $refund->status;

                $deductedStatuses = [
                    RefundStatus::Pending,
                    RefundStatus::Processing,
                    RefundStatus::Completed
                ];

                $oldWasDeducted = in_array($oldStatus, $deductedStatuses);
                $newIsDeducted = in_array($newStatus, $deductedStatuses);

                $wallet = $refund->wallet;
                if ($wallet) {
                    // Transition 1: From deducted status to non-deducted status (e.g. Pending -> Canceled)
                    if ($oldWasDeducted && !$newIsDeducted) {
                        $wallet->increment('amount', $refund->amount);

                        // If old status was Completed, we also delete the transaction log if it exists
                        if ($oldStatus == RefundStatus::Completed) {
                            $user = $refund->user;
                            if ($user) {
                                $codes = [
                                    'WD' . $refund->transaction_id,
                                    'WD' . $refund->id,
                                ];
                                \App\Models\Transaction::where('user_id', $user->id)
                                    ->whereIn('transaction_code', $codes)
                                    ->delete();
                            }
                        }
                    }
                    // Transition 2: From non-deducted status to deducted status (e.g. Canceled -> Pending)
                    elseif (!$oldWasDeducted && $newIsDeducted) {
                        $wallet->decrement('amount', $refund->amount);
                    }
                }

                // Handle Transaction and Notification creation when entering Completed state
                if ($newStatus == RefundStatus::Completed && $oldStatus != RefundStatus::Completed) {
                    $user = $refund->user;
                    if ($user) {
                        \App\Models\Transaction::create([
                            'user_id' => $user->id,
                            'transaction_code' => 'WD' . ($refund->transaction_id ?: $refund->id),
                            'amount' => -$refund->amount,
                            'prepay' => 0,
                        ]);

                        \App\Models\Notification::create([
                            'user_id' => $user->id,
                            'message' => 'Yêu cầu rút tiền ' . number_format($refund->amount) . ' VNĐ của bạn đã được phê duyệt thành công và đang được chuyển vào tài khoản.',
                            'is_read' => '0',
                        ]);
                    }
                }

                // Handle Notification when withdrawal is Canceled or Failed
                if (in_array($newStatus, [RefundStatus::Canceled, RefundStatus::Failed]) && !in_array($oldStatus, [RefundStatus::Canceled, RefundStatus::Failed])) {
                    $user = $refund->user;
                    if ($user) {
                        \App\Models\Notification::create([
                            'user_id' => $user->id,
                            'message' => 'Yêu cầu rút tiền ' . number_format($refund->amount) . ' VNĐ của bạn đã bị từ chối/hủy. Số tiền đã được hoàn lại về ví của bạn.',
                            'is_read' => '0',
                        ]);
                    }
                }
            }
        });
    }
}
