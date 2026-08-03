<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarBrand extends Model
{
    protected $fillable = ['brand_name'];

    public function carTypes(): HasMany
    {
        return $this->hasMany(CarType::class, 'car_brand_id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'car_brand_id');
    }
}