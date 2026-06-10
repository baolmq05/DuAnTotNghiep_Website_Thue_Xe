<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = ['trip_id', 'reviewer_id', 'target_id', 'car_id', 'rating', 'comment', 'review_type'];
}
