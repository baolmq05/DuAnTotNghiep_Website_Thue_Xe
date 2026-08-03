<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wallet;
use App\Models\User;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['amount' => 5000000, 'hold_balance' => 0]
            );
        }
    }
}
