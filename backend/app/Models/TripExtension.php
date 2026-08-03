<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripExtension extends Model
{
    protected $fillable = [
        'trip_id',
        'extension_amount',
        'status',
        'start_date',
        'end_date',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
