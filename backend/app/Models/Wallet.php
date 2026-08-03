<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wallet extends Model
{
    protected $fillable = ['amount', 'hold_balance'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'wallet_id');
    }
}
