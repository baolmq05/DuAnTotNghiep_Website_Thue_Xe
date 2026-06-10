<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ViewHistory;

class ViewHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ViewHistory::create([
            'user_id' => 2,
            'car_id' => 1
        ]);
        ViewHistory::create([
            'user_id' => 3,
            'car_id' => 2
        ]);
        ViewHistory::create([
            'user_id' => 4,
            'car_id' => 3
        ]);
        ViewHistory::create([
            'user_id' => 2,
            'car_id' => 2
        ]);
        ViewHistory::create([
            'user_id' => 3,
            'car_id' => 3
        ]);
        ViewHistory::create([
            'user_id' => 4,
            'car_id' => 1
        ]);
    }
}
