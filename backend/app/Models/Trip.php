<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    //
    protected $fillable = ['cost', 'discount_amount', 'status', 'trip_type', 'start_at', 'end_at', 'car_id', 'user_id', 'delivery_address', 'delivery_location'];

    public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
