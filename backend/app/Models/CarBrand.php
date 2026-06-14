<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    protected $fillable = ['brand_name'];

    public function carTypes()
    {
        return $this->hasMany(CarType::class, 'car_brand_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'car_brand_id');
    }
}