<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    protected $fillable = ['type_name', 'car_brand_id'];

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'car_type_id');
    }
}