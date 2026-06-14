<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteItem extends Model
{
    use HasFactory;

    protected $fillable = ['favorite_id', 'car_id'];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}