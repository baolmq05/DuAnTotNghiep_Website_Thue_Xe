<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    //
    protected $fillable = [
        'name',
        'license_plate',
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
        'usage_limit_id'
    ];

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

    public function usageLimit()
    {
        return $this->belongsTo(CarUsageLimit::class, 'usage_limit_id');
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
