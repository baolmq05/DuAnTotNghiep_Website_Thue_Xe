<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::create([
            'user_id' => 1,
            'transaction_code' => 'TXN001',
            'amount' => 1000000,
            'prepay' => 200000,
            'trip_id' => 1
        ]);
        Transaction::create([
            'user_id' => 2,
            'transaction_code' => 'TXN002',
            'amount' => 1500000,
            'prepay' => 300000,
            'trip_id' => 2
        ]);
    }
}
