<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportImage extends Model
{
    protected $fillable = [
        'image_url',
        'report_id',
    ];

    /**
     * Get the report that this image belongs to.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
