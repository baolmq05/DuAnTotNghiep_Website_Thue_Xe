<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'phone' => '0123456789',
            'avatar' => 'https://res.cloudinary.com/djbobb5oe/image/upload/v1764143352/tu1d4wbkkdshpsghluu2.jpg',
            'gender' => '0',
            'DOB' => '1990-01-01',
            'national_number' => '123456789',
            'status' => '1',
            'role_id' => 1,
        ]);
        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => '12345678',
            'phone' => '0987654321',
            'avatar' => 'https://res.cloudinary.com/djbobb5oe/image/upload/v1763230603/Js_nang_cao/qjbp8r3onpdgy3w4omhe.jpg',
            'gender' => '1',
            'DOB' => '1992-05-15',
            'national_number' => '987654321',
            'status' => '1',
            'role_id' => 2,
        ]);
        User::create([
            'name' => 'User2',
            'email' => 'user2@example.com',
            'email_verified_at' => now(),
            'password' => '12345678',
            'phone' => '0987654322',
            'avatar' => 'https://res.cloudinary.com/djbobb5oe/image/upload/v1763230603/Js_nang_cao/qjbp8r3onpdgy3w4omhe.jpg',
            'gender' => '1',
            'DOB' => '1992-05-15',
            'national_number' => '987654322',
            'status' => '1',
            'role_id' => 2,
            'driving_license_id' => 3,
        ]);
        User::create([
            'name' => 'Car Owner',
            'email' => 'carowner@example.com',
            'email_verified_at' => now(),
            'password' => '12345678',
            'phone' => '0123456788',
            'avatar' => 'https://res.cloudinary.com/djbobb5oe/image/upload/v1763878840/sv9679whfelnmj65so7h.jpg',
            'gender' => '0',
            'DOB' => '1985-10-10',
            'national_number' => '111111111',
            'status' => '1',
            'role_id' => 3,
            'driving_license_id' => 1,
        ]);
        User::create([
            'name' => 'Car Owner2',
            'email' => 'carowner2@example.com',
            'email_verified_at' => now(),
            'password' => '12345678',
            'phone' => '0987654323',
            'avatar' => 'https://res.cloudinary.com/djbobb5oe/image/upload/v1763878840/sv9679whfelnmj65so7h.jpg',
            'gender' => '0',
            'DOB' => '1985-10-10',
            'national_number' => '222222222',
            'status' => '1',
            'role_id' => 3,
            'driving_license_id' => 2,
        ]);
    }
}
