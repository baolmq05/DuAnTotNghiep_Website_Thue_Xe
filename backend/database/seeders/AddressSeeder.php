<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'address_name' => 'Khu đô thị ,Hà Nội',
            'user_id' => 1,
        ]);
        Address::create([
            'address_name' => 'Số 123, Cái khế, Cần Thơ',
            'user_id' => 2,
        ]);
        Address::create([
            'address_name' => 'Số 123, Cái khế, Cần Thơ',
            'user_id' => 2,
        ]);
        Address::create([
            'address_name' => 'Số 456, Nguyễn Trãi, Hà Nội',
            'user_id' => 3,
        ]);
        Address::create([
            'address_name' => 'Số 789, Lê Lợi, TP.HCM',
            'user_id' => 4,
        ]);
    }
}
