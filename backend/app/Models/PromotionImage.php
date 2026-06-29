<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionImage extends Model
{
  protected $fillable = ['promotion_id', 'image_url'];

  public function promotion()
  {
    return $this->belongsTo(Promotion::class);
  }
}