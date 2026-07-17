<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $fillable = ['amount'];

    public function user()
    {
        return $this->hasOne(User::class, 'wallet_id');
    }
}
