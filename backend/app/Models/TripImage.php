<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripImage extends Model
{
  protected $fillable = ['is_thumbnail','type','trip_id', 'image_url'];
}   