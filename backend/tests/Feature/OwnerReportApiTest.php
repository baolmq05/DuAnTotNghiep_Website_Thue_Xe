<?php

namespace Tests\Feature;

use App\Enum\PenaltyType;
use App\Enum\ReportStatus;
use App\Enum\ReportType;
use App\Enum\TripStatus;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarDeliveryOption;
use App\Models\CarLocation;
use App\Models\CarType;
use App\Models\CarUsageLimit;
use App\Models\OwnerPenalty;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\Role;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OwnerReportApiTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $renter;
    private User $otherOwner;
    private Car $car;
    private Trip $trip;
    private Report $report;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Dữ liệu bảng cha
        Role::firstOrCreate(['id' => 1], ['name' => 'Admin', 'description' => 'Quản trị viên']);
        Role::firstOrCreate(['id' => 2], ['name' => 'Host', 'description' => 'Chủ xe']);
        Role::firstOrCreate(['id' => 3], ['name' => 'Customer', 'description' => 'Khách hàng']);

        $location = CarLocation::firstOrCreate(['id' => 1], ['location' => 'TP. Hồ Chí Minh', 'address' => '123 Nguyen Hue']);
        $brand = CarBrand::firstOrCreate(['id' => 1], ['brand_name' => 'Toyota']);
        $type = CarType::firstOrCreate(['id' => 1], ['type_name' => 'Sedan', 'car_brand_id' => $brand->id]);
        $delivery = CarDeliveryOption::firstOrCreate(['id' => 1], [
            'max_distance' => 20,
            'fee_distance' => 10000,
            'free_distance' => 5,
            'status' => 1,
        ]);
        $limit = CarUsageLimit::firstOrCreate(['id' => 1], [
            'max_daily_distance' => 300,
            'extra_distance_fee' => 5000,
            'status' => 1,
        ]);

        // 2. Tạo Owner
        $this->owner = User::factory()->create([
            'status' => 1,
            'role_id' => 2, // Host / Owner
        ]);

        // 3. Tạo Renter
        $this->renter = User::factory()->create([
            'status' => 1,
            'role_id' => 3, // Customer
        ]);

        // 4. Tạo Chủ xe khác
        $this->otherOwner = User::factory()->create([
            'status' => 1,
            'role_id' => 2,
        ]);

        // 5. Tạo Xe thuộc Owner
        $this->car = Car::create([
            'name' => 'Toyota Vios 2023 Luxury',
            'license_plate' => '51K-123.45',
            'VIN' => 'VIN12345678901234',
            'engine_number' => 'ENG123456',
            'fuel_consumption' => '6.5L/100km',
            'unit_price' => 800000,
            'seat_count' => 5,
            'manufacture_year' => 2023,
            'fuel_type' => 1, // Xăng
            'transmission' => 1, // Tự động
            'user_id' => $this->owner->id,
            'car_brand_id' => $brand->id,
            'car_type_id' => $type->id,
            'car_location_id' => $location->id,
            'delivery_option_id' => $delivery->id,
            'usage_limit_id' => $limit->id,
            'status' => 1,
        ]);

        // 6. Tạo Chuyến đi
        $this->trip = Trip::create([
            'trip_code' => 'TR-TEST01',
            'car_id' => $this->car->id,
            'user_id' => $this->renter->id,
            'cost' => 1600000,
            'discount_amount' => 0,
            'status' => TripStatus::Ongoing->value,
            'trip_type' => 0,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'delivery_address' => '123 Nguyen Hue, Quan 1, TP.HCM',
        ]);

        // 7. Tạo Báo cáo cho chuyến đi
        $this->report = Report::create([
            'trip_id' => $this->trip->id,
            'reporter_id' => $this->renter->id,
            'report_type' => ReportType::WrongCar->value,
            'title' => 'Giao sai xe so với thông tin mô tả',
            'description' => 'Xe thực tế nhận được không giống với hình ảnh và thông tin đăng ký.',
            'status' => ReportStatus::Pending->value,
        ]);

        ReportImage::create([
            'report_id' => $this->report->id,
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/sample.jpg',
        ]);

        $this->token = JWTAuth::fromUser($this->owner);
    }

    /**
     * Test cannot access owner report APIs without authentication.
     */
    public function test_cannot_access_owner_reports_without_auth(): void
    {
        $this->getJson('/api/owner/reports')->assertStatus(401);
        $this->getJson('/api/owner/reports/' . $this->report->id)->assertStatus(401);
        $this->getJson('/api/owner/reports/summary')->assertStatus(401);
    }

    /**
     * Test owner can get reports list.
     */
    public function test_owner_can_get_reports_list(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/owner/reports');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'report_type',
                        'report_type_text',
                        'title',
                        'description',
                        'status',
                        'status_text',
                        'trip' => [
                            'id',
                            'trip_code',
                            'cost',
                        ],
                        'car' => [
                            'id',
                            'name',
                            'license_plate',
                            'brand_name',
                            'type_name',
                        ],
                        'reporter' => [
                            'id',
                            'name',
                        ],
                        'images' => [
                            '*' => ['id', 'image_url'],
                        ],
                    ],
                ],
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ]);

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($this->report->id, $response->json('data.0.id'));
        $this->assertEquals('Giao sai xe', $response->json('data.0.report_type_text'));
        $this->assertEquals('Chờ xử lý', $response->json('data.0.status_text'));
        $this->assertEquals('Toyota', $response->json('data.0.car.brand_name'));
        $this->assertEquals('Sedan', $response->json('data.0.car.type_name'));
    }

    /**
     * Test owner cannot see other owners' reports in list.
     */
    public function test_owner_does_not_see_other_owners_reports(): void
    {
        $otherToken = JWTAuth::fromUser($this->otherOwner);

        $response = $this->withHeader('Authorization', 'Bearer ' . $otherToken)
            ->getJson('/api/owner/reports');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
    }

    /**
     * Test filter reports by status and search keyword.
     */
    public function test_filter_and_search_reports(): void
    {
        // 1. Filter by matching status (Pending = 0)
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/owner/reports?status=0');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));

        // 2. Filter by non-matching status (Resolved = 1)
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/owner/reports?status=1');
        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));

        // 3. Search matching keyword
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/owner/reports?search=Toyota');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));

        // 4. Search non-matching keyword
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/owner/reports?search=MercedesNonExistent');
        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
    }

    /**
     * Test owner can view single report detail.
     */
    public function test_owner_can_view_report_detail(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/owner/reports/' . $this->report->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $this->report->id,
                    'title' => $this->report->title,
                    'trip' => [
                        'trip_code' => 'TR-TEST01',
                    ],
                    'car' => [
                        'license_plate' => '51K-123.45',
                        'brand_name' => 'Toyota',
                        'type_name' => 'Sedan',
                    ],
                ],
            ]);
    }

    /**
     * Test unauthorized owner cannot view other owner's report.
     */
    public function test_other_owner_cannot_view_report(): void
    {
        $otherToken = JWTAuth::fromUser($this->otherOwner);

        $response = $this->withHeader('Authorization', 'Bearer ' . $otherToken)
            ->getJson('/api/owner/reports/' . $this->report->id);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Bạn không có quyền xem báo cáo này.',
            ]);
    }

    /**
     * Test owner reports summary endpoint.
     */
    public function test_owner_reports_summary_with_strikes_and_status(): void
    {
        // Add 1 active warning strike (expires in 7 days)
        OwnerPenalty::create([
            'user_id' => $this->owner->id,
            'trip_id' => $this->trip->id,
            'report_id' => $this->report->id,
            'penalty_type' => PenaltyType::Warning1->value,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addDays(7),
            'reason' => 'Cảnh cáo lần 1: Giao sai xe cho khách thuê',
            'resolved_by' => 1,
        ]);

        // Add 1 expired strike
        OwnerPenalty::create([
            'user_id' => $this->owner->id,
            'trip_id' => null,
            'report_id' => null,
            'penalty_type' => PenaltyType::Warning1->value,
            'start_at' => Carbon::now()->subMonth(),
            'end_at' => Carbon::now()->subDays(5),
            'reason' => 'Cảnh cáo cũ đã hết hạn',
            'resolved_by' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/owner/reports/summary');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'account_status' => 'ACTIVE',
                    'is_account_suspended' => false,
                    'active_strikes' => 1, // Only 1 active strike
                    'total_strikes' => 2,  // 2 strikes in total history
                    'reports' => [
                        'total' => 1,
                        'pending' => 1,
                        'resolved' => 0,
                        'rejected' => 0,
                    ],
                    'penalties_breakdown' => [
                        'warnings' => 2,
                        'car_suspensions' => 0,
                        'account_suspensions' => 0,
                    ],
                ],
            ]);

        $this->assertCount(1, $response->json('data.active_penalties'));
        $this->assertCount(1, $response->json('data.recent_reports'));
    }

    /**
     * Test summary reports SUSPENDED status when active account suspension penalty exists.
     */
    public function test_summary_reports_suspended_account_status(): void
    {
        // Add active account suspension penalty
        OwnerPenalty::create([
            'user_id' => $this->owner->id,
            'trip_id' => $this->trip->id,
            'report_id' => $this->report->id,
            'penalty_type' => PenaltyType::AccountSuspension->value,
            'start_at' => Carbon::now()->subHour(),
            'end_at' => Carbon::now()->addDays(30),
            'reason' => 'Tạm khóa tài khoản do vi phạm nghiêm trọng',
            'resolved_by' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/owner/reports/summary');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'account_status' => 'SUSPENDED',
                    'is_account_suspended' => true,
                    'active_strikes' => 1,
                ],
            ]);
    }
}
