<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DrivingLicense;

class DrivingLicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DrivingLicense::create([
            'full_name' => 'Nguyễn Văn A',
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQO543ii_vq7ztBZoaUmpxI5mgY8z6zy1-GBYXs9_K5Ig&s=10',
            'driving_license_number' => 'B123456789',
            'DOB' => '1990-01-01',
        ]);
        DrivingLicense::create([
            'full_name' => 'Trần Thị B',
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQO543ii_vq7ztBZoaUmpxI5mgY8z6zy1-GBYXs9_K5Ig&s=10',
            'driving_license_number' => 'C987654321',
            'DOB' => '1992-05-15',
        ]);
        DrivingLicense::create([
            'full_name' => 'Lê Văn C',
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQO543ii_vq7ztBZoaUmpxI5mgY8z6zy1-GBYXs9_K5Ig&s=10',
            'driving_license_number' => 'D456789123',
            'DOB' => '1988-10-20',
        ]);
    }
}
