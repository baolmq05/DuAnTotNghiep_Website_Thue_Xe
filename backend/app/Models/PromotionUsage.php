<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionUsage extends Model
{
    protected $fillable = ['user_id', 'promotion_id', 'discount_amount', 'used_at', 'trip_id'];
}
