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
}
