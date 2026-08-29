<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enum\ReportStatus;
use App\Enum\ReportType;

class Report extends Model
{
    protected $fillable = [
        'trip_id',
        'reporter_id',
        'report_type',
        'title',
        'description',
        'status',
        'previous_trip_status',
        'admin_note',
        'deadline_at',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'report_type' => ReportType::class,
        'status' => ReportStatus::class,
        'deadline_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the trip that was reported.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the user who reported.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Get the user (admin) who resolved the report.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Get the images uploaded as evidence for this report.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ReportImage::class);
    }

    /**
     * Get the penalty associated with this report, if any.
     */
    public function penalty(): HasOne
    {
        return $this->hasOne(OwnerPenalty::class, 'report_id');
    }
}
