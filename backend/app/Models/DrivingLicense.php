<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrivingLicense extends Model
{
    //
    protected $fillable = ['full_name', 'image', 'driving_license_number', 'DOB', 'status'];
}
