<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    //
    protected $fillable = ['code', 'name', 'description', 'discount_type', 'discount_value', 'start_date', 'end_date', 'usage_limit', 'per_user_limit', 'status', 'user_id'];
}
