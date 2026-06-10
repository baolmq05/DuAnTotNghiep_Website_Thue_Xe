<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarLocation extends Model
{
    //
    protected $fillable = ['province_id', 'ward_code', 'street_name'];
}
