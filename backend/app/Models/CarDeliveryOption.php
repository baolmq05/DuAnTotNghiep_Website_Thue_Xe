<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarDeliveryOption extends Model
{
    protected $fillable = ['max_distance', 'fee_distance', 'free_distance', 'status'];
}
