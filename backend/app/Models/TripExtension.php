<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripExtension extends Model
{
    protected $fillable = [
        'trip_id',
        'extension_amount',
        'status',
        'start_date',
        'end_date',
    ];

    public function trip(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
