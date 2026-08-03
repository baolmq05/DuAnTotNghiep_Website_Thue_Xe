<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionImage extends Model
{
  protected $fillable = ['promotion_id', 'image_url'];

  public function promotion(): BelongsTo
  {
    return $this->belongsTo(Promotion::class);
  }
}