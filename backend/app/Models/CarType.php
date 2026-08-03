<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarType extends Model
{
    protected $fillable = ['type_name', 'car_brand_id'];

    public function carBrand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'car_type_id');
    }
}