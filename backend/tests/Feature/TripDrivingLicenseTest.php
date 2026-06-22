<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Car;
use App\Models\DrivingLicense;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TripDrivingLicenseTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function getHeadersForUser(User $user): array
    {
        $token = JWTAuth::fromUser($user);
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }

    /**
     * Test creating a trip fails if the user does not have a driving license.
     */
    public function test_create_trip_fails_without_driving_license(): void
    {
        $user = User::first();
        // Force license to be null
        $user->update(['driving_license_id' => null]);
        $user->load('drivingLicense');

        $car = Car::where('user_id', '!=', $user->id)->first();

        $response = $this->withHeaders($this->getHeadersForUser($user))
            ->postJson('/api/trips', [
                'cost' => 1000000,
                'trip_type' => 0,
                'start_at' => '2026-07-01 08:00:00',
                'end_at' => '2026-07-02 08:00:00',
                'car_id' => $car->id,
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Bạn chưa cập nhật thông tin giấy phép lái xe. Vui lòng cập nhật thông tin tại trang Cá nhân.',
            ]);
    }

    /**
     * Test creating a trip fails if the user's driving license is pending approval.
     */
    public function test_create_trip_fails_with_pending_driving_license(): void
    {
        $user = User::first();
        $license = DrivingLicense::create([
            'full_name' => 'Nguyễn Văn Test',
            'image' => 'http://example.com/license.jpg',
            'driving_license_number' => 'TEST00001',
            'DOB' => '1995-01-01',
            'status' => 0 // Pending
        ]);
        $user->update(['driving_license_id' => $license->id]);
        $user->load('drivingLicense');

        $car = Car::where('user_id', '!=', $user->id)->first();

        $response = $this->withHeaders($this->getHeadersForUser($user))
            ->postJson('/api/trips', [
                'cost' => 1000000,
                'trip_type' => 0,
                'start_at' => '2026-07-01 08:00:00',
                'end_at' => '2026-07-02 08:00:00',
                'car_id' => $car->id,
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Giấy phép lái xe của bạn đang chờ duyệt. Vui lòng đợi quản trị viên phê duyệt để thuê xe.',
            ]);
    }

    /**
     * Test creating a trip fails if the user's driving license is rejected.
     */
    public function test_create_trip_fails_with_rejected_driving_license(): void
    {
        $user = User::first();
        $license = DrivingLicense::create([
            'full_name' => 'Nguyễn Văn Test',
            'image' => 'http://example.com/license.jpg',
            'driving_license_number' => 'TEST00002',
            'DOB' => '1995-01-01',
            'status' => 2 // Rejected
        ]);
        $user->update(['driving_license_id' => $license->id]);
        $user->load('drivingLicense');

        $car = Car::where('user_id', '!=', $user->id)->first();

        $response = $this->withHeaders($this->getHeadersForUser($user))
            ->postJson('/api/trips', [
                'cost' => 1000000,
                'trip_type' => 0,
                'start_at' => '2026-07-01 08:00:00',
                'end_at' => '2026-07-02 08:00:00',
                'car_id' => $car->id,
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Giấy phép lái xe của bạn đã bị từ chối. Vui lòng cập nhật lại thông tin tại trang Cá nhân.',
            ]);
    }

    /**
     * Test creating a trip succeeds if the user's driving license is approved.
     */
    public function test_create_trip_succeeds_with_approved_driving_license(): void
    {
        $user = User::first();
        $license = DrivingLicense::create([
            'full_name' => 'Nguyễn Văn Test',
            'image' => 'http://example.com/license.jpg',
            'driving_license_number' => 'TEST00003',
            'DOB' => '1995-01-01',
            'status' => 1 // Approved
        ]);
        $user->update(['driving_license_id' => $license->id]);
        $user->load('drivingLicense');

        $car = Car::where('user_id', '!=', $user->id)->first();

        $response = $this->withHeaders($this->getHeadersForUser($user))
            ->postJson('/api/trips', [
                'cost' => 1000000,
                'trip_type' => 0,
                'start_at' => '2026-07-01 08:00:00',
                'end_at' => '2026-07-02 08:00:00',
                'car_id' => $car->id,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Gửi yêu cầu thuê xe thành công!',
            ]);
    }

    /**
     * Test creating a trip fails if the user does not have a phone number.
     */
    public function test_create_trip_fails_without_phone_number(): void
    {
        $user = User::first();
        // Force phone to be null
        $user->update(['phone' => null]);

        $car = Car::where('user_id', '!=', $user->id)->first();

        $response = $this->withHeaders($this->getHeadersForUser($user))
            ->postJson('/api/trips', [
                'cost' => 1000000,
                'trip_type' => 0,
                'start_at' => '2026-07-01 08:00:00',
                'end_at' => '2026-07-02 08:00:00',
                'car_id' => $car->id,
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Bạn cần cập nhật số điện thoại trước khi thực hiện thuê xe. Vui lòng cập nhật tại trang Cá nhân.',
            ]);
    }
}
