<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    //
    protected $fillable = ['feature_name', 'icon', 'description', 'status'];

    public function cars(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'car_features', 'feature_id', 'car_id');
    }
}
