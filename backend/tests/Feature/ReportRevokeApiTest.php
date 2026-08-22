<?php

namespace Tests\Feature;

use App\Enum\ReportStatus;
use App\Enum\ReportType;
use App\Enum\TripStatus;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarDeliveryOption;
use App\Models\CarLocation;
use App\Models\CarType;
use App\Models\CarUsageLimit;
use App\Models\Notification;
use App\Models\Report;
use App\Models\Role;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReportRevokeApiTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $renter;
    private User $otherUser;
    private Car $car;
    private Trip $trip;
    private Report $report;
    private string $renterToken;
    private string $otherToken;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->owner = User::factory()->create(['status' => 1, 'role_id' => 2]);
        $this->renter = User::factory()->create(['status' => 1, 'role_id' => 3]);
        $this->otherUser = User::factory()->create(['status' => 1, 'role_id' => 3]);

        $this->car = Car::create([
            'name' => 'Toyota Vios 2023 Luxury',
            'license_plate' => '51K-123.45',
            'VIN' => 'VIN12345678901234',
            'engine_number' => 'ENG123456',
            'fuel_consumption' => '6.5L/100km',
            'unit_price' => 800000,
            'seat_count' => 5,
            'manufacture_year' => 2023,
            'fuel_type' => 1,
            'transmission' => 1,
            'user_id' => $this->owner->id,
            'car_brand_id' => $brand->id,
            'car_type_id' => $type->id,
            'car_location_id' => $location->id,
            'delivery_option_id' => $delivery->id,
            'usage_limit_id' => $limit->id,
            'status' => 1,
        ]);

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

        $this->report = Report::create([
            'trip_id' => $this->trip->id,
            'reporter_id' => $this->renter->id,
            'report_type' => ReportType::WrongCar->value,
            'title' => 'Giao sai xe so với thông tin mô tả',
            'description' => 'Xe thực tế nhận được không giống với hình ảnh và thông tin đăng ký.',
            'status' => ReportStatus::Pending->value,
        ]);

        $this->renterToken = JWTAuth::fromUser($this->renter);
        $this->otherToken = JWTAuth::fromUser($this->otherUser);
    }

    public function test_cannot_revoke_report_without_auth(): void
    {
        $response = $this->postJson("/api/reports/{$this->report->id}/revoke");
        $response->assertStatus(401);
    }

    public function test_cannot_revoke_non_existent_report(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->renterToken)
            ->postJson("/api/reports/99999/revoke");
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Không tìm thấy khiếu nại này.'
            ]);
    }

    public function test_cannot_revoke_others_report(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->otherToken)
            ->postJson("/api/reports/{$this->report->id}/revoke");
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Bạn không có quyền thu hồi khiếu nại này.'
            ]);
    }

    public function test_reporter_can_revoke_pending_report_and_notifies_owner(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->renterToken)
            ->postJson("/api/reports/{$this->report->id}/revoke");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Thu hồi khiếu nại thành công.',
            ]);

        $this->report->refresh();
        $this->assertEquals(ReportStatus::Cancelled, $this->report->status);

        // Verify notification was created for owner
        $notification = Notification::where('user_id', $this->owner->id)->first();
        $this->assertNotNull($notification);
        $this->assertEquals("Khách hàng đã thu hồi khiếu nại đối với chuyến đi #{$this->trip->id}.", $notification->message);
        $this->assertEquals('0', $notification->is_read);
    }

    public function test_cannot_revoke_already_processed_report(): void
    {
        $this->report->update(['status' => ReportStatus::Resolved]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->renterToken)
            ->postJson("/api/reports/{$this->report->id}/revoke");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Khiếu nại này không ở trạng thái chờ xử lý nên không thể thu hồi.'
            ]);
    }
}
