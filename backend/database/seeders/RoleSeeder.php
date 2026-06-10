<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'description' => 'Quản trị viên có quyền quản lý toàn bộ hệ thống',
        ]);
        Role::create([
            'name' => 'User',
            'description' => 'Người dùng có quyền thuê xe và sử dụng dịch vụ',
        ]);
        Role::create([
            'name' => 'Car Owner',
            'description' => 'Chủ xe có quyền quản lý và cho thuê xe',
        ]);
    }
}
