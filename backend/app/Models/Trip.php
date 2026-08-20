<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enum\TripStatus;
use App\Models\PromotionUsage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Trip extends Model
{
    protected $fillable = ['cost', 'discount_amount', 'status', 'trip_type', 'start_at', 'end_at', 'car_id', 'user_id', 'delivery_address', 'delivery_location', 'trip_code'];

    protected $appends = ['payment_held', 'owner_payment_note'];

    public function getPaymentHeldAttribute(): bool
    {
        return $this->pendingBalances()->where('status', '1')->exists();
    }

    public function getOwnerPaymentNoteAttribute(): ?string
    {
        $hasHolding = $this->pendingBalances()->where('status', '1')->exists();
        if ($hasHolding) {
            return 'Tiền đã được lưu vào ví tạm. Khi hoàn thành chuyến, số tiền sẽ được chuyển vào ví của bạn.';
        }

        $hasReleased = $this->pendingBalances()->where('status', '2')->exists();
        if ($hasReleased) {
            return 'Tiền thuê xe đã được giải ngân thành công vào ví của bạn.';
        }

        if ($this->status == TripStatus::WaitingPayment->value) {
            return 'Chuyến đi đang chờ khách hàng thanh toán.';
        }

        if ($this->status == TripStatus::Pending->value) {
            return 'Đang chờ bạn duyệt yêu cầu thuê xe.';
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($trip) {
            if (empty($trip->trip_code)) {
                $trip->trip_code = self::generateUniqueTripCode();
            }
        });

        static::updated(function ($trip) {
            if (in_array((int) $trip->status, [TripStatus::UserCancel->value, TripStatus::OwnerCancel->value])) {
                PromotionUsage::where('trip_id', $trip->id)->delete();
            }

            if (in_array((int) $trip->status, [TripStatus::Complete->value, TripStatus::UserCancel->value, TripStatus::OwnerCancel->value])) {
                if ($trip->conversation) {
                    $trip->conversation->update(['status' => 0]);
                }
            }
        });
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TripImage::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }


    public function extensions(): HasMany
    {
        return $this->hasMany(TripExtension::class);
    }

    public function latestExtension(): HasOne
    {
        return $this->hasOne(TripExtension::class)->latestOfMany();
    }

    public function pendingBalances(): HasMany
    {
        return $this->hasMany(PendingBalance::class);
    }

    public function conversation(): HasOne
    {
        return $this->hasOne(ChatConversation::class, 'trip_id');
    }

    public function releasePendingBalances()
    {
        $pendingBalances = $this->pendingBalances()->where('status', '1')->get();

        foreach ($pendingBalances as $pending) {
            $receiver = $pending->receiver;
            if ($receiver) {
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $receiver->id],
                    ['amount' => 0, 'hold_balance' => 0]
                );

                $totalAmount = $pending->amount;
                $holdAmount = round($totalAmount * 0.02, 2);
                $VatAmount = round($totalAmount * 0.07, 2);
                $commissionAmount = round($totalAmount * 0.18, 2);
                $availableAmount = $totalAmount - $holdAmount - $VatAmount - $commissionAmount;
                $wallet->increment('amount', $availableAmount);
                $wallet->increment('hold_balance', $holdAmount);
            }

            $pending->update([
                'status' => '2',
                'released_at' => now(),
            ]);
        }
    }

    public function cancelPendingBalances()
    {
        $this->pendingBalances()->where('status', '1')->update([
            'status' => '3',
        ]);
    }

    /**
     * Tạo mã chuyến đi ngắn gọn (VD: TR-8K9M2P), loại bỏ ký tự dễ nhầm lẫn (0, O, 1, I)
     * và đảm bảo 100% không trùng lặp trong cơ sở dữ liệu.
     */
    public static function generateUniqueTripCode(): string
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $length = 6;

        do {
            $randomPart = '';
            for ($i = 0; $i < $length; $i++) {
                $randomPart .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $code = 'TR-' . $randomPart;
        } while (self::where('trip_code', $code)->exists());

        return $code;
    }
}

