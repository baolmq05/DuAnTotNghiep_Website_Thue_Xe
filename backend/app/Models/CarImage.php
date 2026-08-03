<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    protected $fillable = ['is_thumbnail', 'image_url', 'car_id'];
}
