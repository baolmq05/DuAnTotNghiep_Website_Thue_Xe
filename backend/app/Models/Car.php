<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'license_plate',
        'VIN',
        'engine_number',
        'fuel_consumption',
        'unit_price',
        'discount_value',
        'description',
        'rental_terms',
        'car_location_id',
        'car_brand_id',
        'car_type_id',
        'seat_count',
        'manufacture_year',
        'fuel_type',
        'transmission',
        'user_id',
        'delivery_option_id',
        'status',
        'rejection_reason',
        'deletion_reason'
    ];

    protected $appends = ['has_ongoing_trip'];

    public function getHasOngoingTripAttribute(): bool
    {
        return $this->trips()->whereNotIn('status', [
            \App\Enum\TripStatus::Complete->value,
            \App\Enum\TripStatus::UserCancel->value,
            \App\Enum\TripStatus::OwnerCancel->value,
        ])->exists();
    }

    protected static function booted()
    {
        static::updating(function ($car) {
            if ($car->isDirty('status')) {
                if ($car->has_ongoing_trip) {
                    throw new \Exception('Xe đang có chuyến đi hoặc yêu cầu thuê đang diễn ra, không thể thay đổi trạng thái.');
                }
            }
        });

        static::updated(function ($car) {
            if ($car->wasChanged('status')) {
                $owner = $car->owner;
                if ($owner) {
                    $status = (int) $car->status;
                    $originalStatus = (int) $car->getOriginal('status');
                    $message = '';
                    
                    // Chỉ gửi thông báo khi thay đổi từ trạng thái Chờ duyệt (2)
                    if ($status === 1 && $originalStatus === 2) {
                        $message = "Xe '{$car->name}' (Biển số: {$car->license_plate}) của bạn đã được phê duyệt thành công. Xe đã sẵn sàng để hoạt động!";
                    } elseif ($status === 3 && $originalStatus === 2) {
                        $reason = $car->rejection_reason ? " Lý do từ chối: {$car->rejection_reason}" : " Vui lòng kiểm tra lại thông tin xe.";
                        $message = "Xe '{$car->name}' (Biển số: {$car->license_plate}) của bạn đã bị từ chối phê duyệt.{$reason}";
                    }

                    if ($message) {
                        Notification::create([
                            'user_id' => $owner->id,
                            'message' => $message,
                            'is_read' => '0',
                        ]);
                    }
                }
            }
        });
    }

    public function carLocation()
    {
        return $this->belongsTo(CarLocation::class);
    }

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class);
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }

    public function deliveryOption()
    {
        return $this->belongsTo(CarDeliveryOption::class, 'delivery_option_id');
    }

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'car_features', 'car_id', 'feature_id');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
