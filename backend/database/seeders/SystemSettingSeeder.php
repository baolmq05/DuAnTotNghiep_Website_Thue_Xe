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
                'value'      => '18', // Tiền hoa hồng hệ thống (18%)
                'updated_by' => null,
                'updated_at' => $now,
            ],
            [
                'group'      => 'finance',
                'key'        => 'vat_rate',
                'value'      => '7', // Thuế VAT (7%)
                'updated_by' => null,
                'updated_at' => $now,
            ],
            [
                'group'      => 'finance',
                'key'        => 'fee_2_percent',
                'value'      => '2', // Tiền giữ phạt nguội (2%)
                'updated_by' => null,
                'updated_at' => $now,
            ],
            [
                'group'      => 'finance',
                'key'        => 'rental_fee',
                'value'      => '5', // Tiền phí thuê xe (5%)
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

            // Cấu hình chung cho hệ thống (General)
            [
                'group'      => 'general',
                'key'        => 'site_name',
                'value'      => 'Hệ Thống Thuê Xe Tự Lái',
                'updated_by' => null,
                'updated_at' => $now,
            ],
            [
                'group'      => 'general',
                'key'        => 'site_logo',
                'value'      => '/uploads/logo.png',
                'updated_by' => null,
                'updated_at' => $now,
            ],

            // Cấu hình Email (Mail)
            [
                'group'      => 'mail',
                'key'        => 'smtp_host',
                'value'      => 'smtp.gmail.com',
                'updated_by' => null,
                'updated_at' => $now,
            ],
            [
                'group'      => 'mail',
                'key'        => 'smtp_port',
                'value'      => '587',
                'updated_by' => null,
                'updated_at' => $now,
            ],

            // Cấu hình Thanh toán (Payment)
            [
                'group'      => 'payment',
                'key'        => 'vnpay_merchant_id',
                'value'      => 'VNPAY_DEMO_CODE',
                'updated_by' => null,
                'updated_at' => $now,
            ],
        ];

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