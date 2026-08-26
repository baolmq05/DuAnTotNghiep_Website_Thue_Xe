<?php

namespace Tests\Feature;

use App\Actions\Trip\CreateTripAction;
use App\Models\Car;
use App\Models\DrivingLicense;
use App\Models\Promotion;
use App\Models\PromotionUsage;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionFinancialLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_trip_creation_calculates_discount_breakdown_correctly()
    {
        // 0. Seed bảng cha
        \App\Models\Role::firstOrCreate(['id' => 1], ['name' => 'Admin']);
        \App\Models\Role::firstOrCreate(['id' => 2], ['name' => 'Host']);
        \App\Models\Role::firstOrCreate(['id' => 3], ['name' => 'Customer']);

        $brand = \App\Models\CarBrand::firstOrCreate(['id' => 1], ['brand_name' => 'Toyota']);
        $type = \App\Models\CarType::firstOrCreate(['id' => 1], ['type_name' => 'Sedan', 'car_brand_id' => $brand->id]);
        $location = \App\Models\CarLocation::firstOrCreate(['id' => 1], ['location' => 'TP.HCM', 'address' => '123 Nguyen Hue']);

        // 1. Tạo chủ xe & người thuê
        $owner = User::firstOrCreate(
            ['email' => 'test_owner_promo@gmail.com'],
            ['name' => 'Owner Promo', 'password' => bcrypt('password123'), 'phone' => '0987654321', 'role_id' => 2]
        );

        $renter = User::firstOrCreate(
            ['email' => 'test_renter_promo@gmail.com'],
            ['name' => 'Renter Promo', 'password' => bcrypt('password123'), 'phone' => '0912345678', 'role_id' => 3]
        );

        // Duyệt GPLX cho người thuê
        $license = DrivingLicense::updateOrCreate(
            ['user_id' => $renter->id],
            [
                'full_name' => 'Renter Promo',
                'DOB' => '1995-01-01',
                'driving_license_number' => 'GPLX999999',
                'image' => 'licenses/sample.jpg',
                'status' => 1
            ]
        );
        $renter->update(['driving_license_id' => $license->id]);
        $renter->refresh();

        $delivery = \App\Models\CarDeliveryOption::firstOrCreate(['id' => 1], [
            'max_distance' => 20,
            'fee_distance' => 10000,
            'free_distance' => 5,
            'status' => 1,
        ]);
        $limit = \App\Models\CarUsageLimit::firstOrCreate(['id' => 1], [
            'max_daily_distance' => 300,
            'extra_distance_fee' => 5000,
            'status' => 1,
        ]);

        // 2. Tạo xe với đơn giá 1.000.000đ và giảm giá xe 100.000đ/ngày
        $car = Car::create([
            'name' => 'Toyota Camry 2024 Test',
            'license_plate' => '51A-999.99',
            'VIN' => 'VIN12345678901234',
            'engine_number' => 'ENG123456',
            'fuel_consumption' => '7.5L/100km',
            'manufacture_year' => 2024,
            'user_id' => $owner->id,
            'car_brand_id' => $brand->id,
            'car_type_id' => $type->id,
            'car_location_id' => $location->id,
            'delivery_option_id' => $delivery->id,
            'usage_limit_id' => $limit->id,
            'unit_price' => 1000000,
            'discount_value' => 100000,
            'status' => 1,
            'transmission' => 1,
            'fuel_type' => 1,
            'seat_count' => 5,
            'description' => 'Test car',
        ]);

        // 3. Tạo mã Voucher Admin giảm 200.000đ cố định
        $promo = Promotion::create([
            'name' => 'Admin Promo Voucher 200K',
            'code' => 'ADMINPROMO200K',
            'description' => 'Mã giảm giá 200k',
            'discount_type' => 1, // Tiền cố định
            'discount_value' => 200000,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'status' => 1,
            'usage_limit' => 100,
            'per_user_limit' => 5,
        ]);

        // 4. Thực thi tạo chuyến đi thuê 2 ngày
        $action = new CreateTripAction();
        $trip = $action->execute($renter, [
            'car_id' => $car->id,
            'start_at' => now()->addDay()->format('Y-m-d 08:00:00'),
            'end_at' => now()->addDays(3)->format('Y-m-d 08:00:00'), // 2 ngày
            'delivery_fee' => 50000,
            'promo_code' => 'ADMINPROMO200K',
        ]);

        // 5. Kiểm tra tính toán:
        // Đơn giá gốc 2 ngày = 1.000.000 * 2 + 50.000 = 2.050.000đ
        $this->assertEquals(2050000, (float) $trip->cost);

        // Giảm giá của chủ xe = 100.000 * 2 = 200.000đ
        $this->assertEquals(200000, (float) $trip->car_discount_amount);

        // Voucher Admin = 200.000đ
        $this->assertEquals(200000, (float) $trip->promo_discount_amount);

        // Tổng giảm giá = 400.000đ
        $this->assertEquals(400000, (float) $trip->discount_amount);

        // Doanh thu của chủ xe = 2.050.000 - 200.000 = 1.850.000đ (KHÔNG BỊ TRỪ 200k voucher của Admin)
        $this->assertEquals(1850000, (float) $trip->owner_gross_revenue);

        // Tiền khách thực tế phải trả qua cổng = 2.050.000 - 400.000 = 1.650.000đ
        $customerPayment = (float) ($trip->cost - $trip->discount_amount);
        $this->assertEquals(1650000, $customerPayment);

        // Kiểm tra PromotionUsage đã được tạo
        $this->assertDatabaseHas('promotion_usages', [
            'trip_id' => $trip->id,
            'promotion_id' => $promo->id,
            'discount_amount' => 200000,
        ]);
    }

    public function test_owner_payout_on_completion_is_based_on_owner_gross_revenue_not_affected_by_admin_promo()
    {
        // 0. Seed bảng cha
        \App\Models\Role::firstOrCreate(['id' => 1], ['name' => 'Admin']);
        \App\Models\Role::firstOrCreate(['id' => 2], ['name' => 'Host']);
        \App\Models\Role::firstOrCreate(['id' => 3], ['name' => 'Customer']);

        $brand = \App\Models\CarBrand::firstOrCreate(['id' => 1], ['brand_name' => 'Toyota']);
        $type = \App\Models\CarType::firstOrCreate(['id' => 1], ['type_name' => 'Sedan', 'car_brand_id' => $brand->id]);
        $location = \App\Models\CarLocation::firstOrCreate(['id' => 1], ['location' => 'TP.HCM', 'address' => '123 Nguyen Hue']);
        $delivery = \App\Models\CarDeliveryOption::firstOrCreate(['id' => 1], ['max_distance' => 20, 'fee_distance' => 10000, 'free_distance' => 5, 'status' => 1]);
        $limit = \App\Models\CarUsageLimit::firstOrCreate(['id' => 1], ['max_daily_distance' => 300, 'extra_distance_fee' => 5000, 'status' => 1]);

        $owner = User::firstOrCreate(
            ['email' => 'test_owner_payout@gmail.com'],
            ['name' => 'Owner Payout', 'password' => bcrypt('password123'), 'phone' => '0987654321', 'role_id' => 2]
        );

        $renter = User::firstOrCreate(
            ['email' => 'test_renter_payout@gmail.com'],
            ['name' => 'Renter Payout', 'password' => bcrypt('password123'), 'phone' => '0912345678', 'role_id' => 3]
        );

        $license = DrivingLicense::updateOrCreate(
            ['user_id' => $renter->id],
            ['full_name' => 'Renter Payout', 'DOB' => '1995-01-01', 'driving_license_number' => 'GPLX888888', 'image' => 'licenses/sample.jpg', 'status' => 1]
        );
        $renter->update(['driving_license_id' => $license->id]);
        $renter->refresh();

        $car = Car::create([
            'name' => 'Mazda 3 2024 Test',
            'license_plate' => '51B-888.88',
            'VIN' => 'VIN98765432109876',
            'engine_number' => 'ENG888888',
            'fuel_consumption' => '6.5L/100km',
            'manufacture_year' => 2024,
            'user_id' => $owner->id,
            'car_brand_id' => $brand->id,
            'car_type_id' => $type->id,
            'car_location_id' => $location->id,
            'delivery_option_id' => $delivery->id,
            'usage_limit_id' => $limit->id,
            'unit_price' => 1000000,
            'discount_value' => 100000, // Chủ xe giảm 100k/ngày
            'status' => 1,
            'transmission' => 1,
            'fuel_type' => 1,
            'seat_count' => 5,
            'description' => 'Test car',
        ]);

        $promo = Promotion::create([
            'name' => 'Admin Promo Voucher 300K',
            'code' => 'ADMINPROMO300K',
            'description' => 'Mã giảm giá 300k',
            'discount_type' => 1,
            'discount_value' => 300000, // Admin giảm 300k
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'status' => 1,
            'usage_limit' => 100,
            'per_user_limit' => 5,
        ]);

        // Tạo chuyến đi 2 ngày: Giá gốc 2tr, chủ xe giảm 200k, admin voucher giảm 300k
        $action = new CreateTripAction();
        $trip = $action->execute($renter, [
            'car_id' => $car->id,
            'start_at' => now()->addDay()->format('Y-m-d 08:00:00'),
            'end_at' => now()->addDays(3)->format('Y-m-d 08:00:00'), // 2 ngày
            'delivery_fee' => 0,
            'promo_code' => 'ADMINPROMO300K',
        ]);

        // Giả lập thanh toán VNPay: Khách trả = 2.000.000 - 200.000 (xe) - 300.000 (voucher) = 1.500.000đ
        $customerPaid = (float) ($trip->cost - $trip->discount_amount);
        $this->assertEquals(1500000, $customerPaid);

        $transaction = \App\Models\Transaction::create([
            'user_id' => $renter->id,
            'transaction_code' => 'VNPAY_TEST_PAYOUT',
            'amount' => $customerPaid,
            'prepay' => $customerPaid,
            'trip_id' => $trip->id
        ]);

        // PendingBalance được tạo theo đúng Doanh thu chủ xe (1.800.000đ)
        $ownerGross = $trip->owner_gross_revenue;
        $this->assertEquals(1800000, $ownerGross);

        \App\Models\PendingBalance::create([
            'transaction_id' => $transaction->id,
            'trip_id' => $trip->id,
            'payer_id' => $renter->id,
            'receiver_id' => $owner->id,
            'amount' => $ownerGross,
            'status' => '1',
            'expired_at' => now()->addDays(5),
            'released_at' => null
        ]);

        // Giải ngân khi hoàn thành chuyến đi
        $trip->releasePendingBalances();

        // Kiểm tra ví chủ xe:
        // Doanh thu chủ xe = 1.800.000đ
        // Hoa hồng 18% = 324.000đ
        // VAT 7% = 126.000đ
        // Giữ phạt nguội 2% = 36.000đ
        // Khả dụng (73%) = 1.314.000đ
        $wallet = \App\Models\Wallet::where('user_id', $owner->id)->first();
        $this->assertNotNull($wallet);
        $this->assertEquals(1314000, (float) $wallet->amount);
        $this->assertEquals(36000, (float) $wallet->hold_balance);

        // Tổng tiền chủ xe nhận (1.314.000 + 36.000) = 1.350.000đ = 75% của 1.800.000đ
        $this->assertEquals(1350000, (float) ($wallet->amount + $wallet->hold_balance));
    }
}
