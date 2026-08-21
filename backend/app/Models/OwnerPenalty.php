<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enum\PenaltyType;

class OwnerPenalty extends Model
{
    protected $fillable = [
        'user_id',
        'trip_id',
        'report_id',
        'penalty_type',
        'start_at',
        'end_at',
        'reason',
        'resolved_by',
    ];

    protected $casts = [
        'penalty_type' => PenaltyType::class,
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /**
     * Get the user (owner) who received the penalty.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the trip associated with the penalty.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    /**
     * Get the report associated with the penalty.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    /**
     * Get the user (admin) who issued/resolved the penalty.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
