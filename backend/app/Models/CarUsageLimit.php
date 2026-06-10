<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarUsageLimit extends Model
{
    //
    protected $fillable = ['max_daily_distance', 'extra_distance_fee', 'status'];
}
