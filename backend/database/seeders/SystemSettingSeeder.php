<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $settings = [
            // Cấu hình Tài chính & Phí (Finance & Fees)
            [
                'group'      => 'finance',
                'key'        => 'commission_rate',
                'value'      => '18', // Tiền hoa hồng của hệ thống (18%)
                'updated_by' => null,
                'updated_at' => $now,
            ],
            [
                'group'      => 'finance',
                'key'        => 'vat_rate',
                'value'      => '7', // Tiền thuế VAT (7%)
                'updated_by' => null,
                'updated_at' => $now,
            ],
            [
                'group'      => 'finance',
                'key'        => 'hol_amount_rate',
                'value'      => '2', // Tiền phạt nguội (2%)
                'updated_by' => null,
                'updated_at' => $now,
            ],
            [
                'group'      => 'finance',
                'key'        => 'deposit_rate',
                'value'      => '40', // Tỷ lệ tiền đặt cọc giữ xe (40%)
                'updated_by' => null,
                'updated_at' => $now,
            ],

        ];

        // Sử dụng updateOrInsert để tránh trùng lặp khi chạy lại Seeder nhiều lần
        foreach ($settings as $setting) {
            DB::table('system_settings')->updateOrInsert(
                [
                    'group' => $setting['group'],
                    'key'   => $setting['key'],
                ],
                [
                    'value'      => $setting['value'],
                    'updated_by' => $setting['updated_by'],
                    'updated_at' => $setting['updated_at'],
                ]
            );
        }
    }
}