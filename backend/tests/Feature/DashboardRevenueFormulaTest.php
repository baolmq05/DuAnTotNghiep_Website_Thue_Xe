<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Trip;
use App\Models\User;
use App\Models\SystemSetting;
use App\Enum\TripStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRevenueFormulaTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_revenue_calculation_with_27_percent_and_promo_discount()
    {
        // 0. Seed roles
        \App\Models\Role::firstOrCreate(['id' => 1], ['name' => 'Admin']);
        \App\Models\Role::firstOrCreate(['id' => 2], ['name' => 'Host']);
        \App\Models\Role::firstOrCreate(['id' => 3], ['name' => 'Customer']);

        $brand = \App\Models\CarBrand::firstOrCreate(['id' => 1], ['brand_name' => 'Toyota']);
        $type = \App\Models\CarType::firstOrCreate(['id' => 1], ['type_name' => 'Sedan', 'car_brand_id' => $brand->id]);
        $location = \App\Models\CarLocation::firstOrCreate(['id' => 1], ['location' => 'TP.HCM', 'address' => '123 Nguyen Hue']);
        $delivery = \App\Models\CarDeliveryOption::firstOrCreate(['id' => 1], ['max_distance' => 20, 'fee_distance' => 10000, 'free_distance' => 5, 'status' => 1]);
        $limit = \App\Models\CarUsageLimit::firstOrCreate(['id' => 1], ['max_daily_distance' => 300, 'extra_distance_fee' => 5000, 'status' => 1]);

        $owner = User::firstOrCreate(
            ['email' => 'owner_dash_test@gmail.com'],
            ['name' => 'Owner Dash', 'password' => bcrypt('password123'), 'phone' => '0987654321', 'role_id' => 2]
        );

        $renter = User::firstOrCreate(
            ['email' => 'renter_dash_test@gmail.com'],
            ['name' => 'Renter Dash', 'password' => bcrypt('password123'), 'phone' => '0912345678', 'role_id' => 3]
        );

        $car = Car::create([
            'name' => 'Test Car',
            'license_plate' => '51A-111.11',
            'VIN' => 'VIN11111111111111',
            'engine_number' => 'ENG111111',
            'fuel_consumption' => '7L/100km',
            'manufacture_year' => 2024,
            'user_id' => $owner->id,
            'car_brand_id' => $brand->id,
            'car_type_id' => $type->id,
            'car_location_id' => $location->id,
            'delivery_option_id' => $delivery->id,
            'usage_limit_id' => $limit->id,
            'unit_price' => 1000000,
            'discount_value' => 0,
            'status' => 1,
            'transmission' => 1,
            'fuel_type' => 1,
            'seat_count' => 5,
            'description' => 'Test car',
        ]);

        // 1 Trip completed: cost = 1,000,000, car_discount = 100,000, promo_discount = 100,000
        // Logic mới: Chiết khấu của chủ xe (car_discount_amount) không ảnh hưởng tới 27% doanh thu sàn.
        // Formula: (cost * 27%) - promo_discount = (1,000,000 * 27%) - 100,000 = 270,000 - 100,000 = 170,000
        Trip::create([
            'cost' => 1000000,
            'car_discount_amount' => 100000,
            'promo_discount_amount' => 100000,
            'discount_amount' => 100000,
            'status' => TripStatus::Complete->value,
            'trip_type' => 0,
            'start_at' => now()->startOfMonth(),
            'end_at' => now()->startOfMonth()->addDay(),
            'car_id' => $car->id,
            'user_id' => $renter->id,
            'created_at' => now(),
        ]);

        $token = auth('api')->login($owner);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard');

        $response->assertStatus(200);

        $monthIndex = now()->month - 1; // 0-based index for chart data T1...T12
        $revenue = $response->json("data.chart.datasets.0.data.{$monthIndex}");

        $this->assertEquals(170000, $revenue);
    }
}
