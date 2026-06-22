<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarApiTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * Test list all cars without filters
     */
    public function test_can_list_all_cars(): void
    {
        $response = $this->getJson('/api/cars');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'license_plate',
                        'unit_price',
                        'car_location',
                        'car_brand',
                        'car_type',
                        'images'
                    ]
                ]
            ]);
    }

    /**
     * Test show car details
     */
    public function test_can_show_car_details(): void
    {
        $response = $this->getJson('/api/cars/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'license_plate',
                    'unit_price',
                    'car_location',
                    'car_brand',
                    'car_type',
                    'delivery_option',
                    'usage_limit',
                    'images',
                    'features',
                    'owner',
                    'reviews',
                    'trips'
                ]
            ]);
    }

    /**
     * Test filter by date range
     */
    public function test_can_filter_cars_by_date_range(): void
    {
        // Khi chọn ngày trùng với chuyến đi của car 2 (status = 1)
        // car 2 phải bị ẩn đi trong kết quả tìm kiếm
        $response = $this->getJson('/api/cars?startDate=2024-02-01 10:00:00&endDate=2024-02-01 12:00:00');

        $response->assertStatus(200);
        
        $carIds = collect($response->json('data'))->pluck('id')->toArray();
        $this->assertNotContains(2, $carIds);
    }

    /**
     * Test filter by address
     */
    public function test_can_filter_cars_by_address(): void
    {
        $response = $this->getJson('/api/cars?address=Duẩn');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        foreach ($data as $car) {
            $this->assertStringContainsString('Lê Duẩn', $car['car_location']['address']);
        }
    }

    /**
     * Test user can register a new car successfully
     */
    public function test_can_register_new_car(): void
    {
        $user = \App\Models\User::first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cars', [
                'license_plate' => '29A-99999',
                'car_brand_id' => 1,
                'car_type_id' => 1,
                'seat_count' => 5,
                'manufacture_year' => 2024,
                'fuel_type' => 'Xăng',
                'transmission' => 'Số tự động',
                'fuel_consumption' => 7.5,
                'description' => 'Mô tả xe thử nghiệm',
                'rental_terms' => 'Điều khoản thuê xe',
                'location' => '10.762622,106.660172',
                'address' => '123 Đường Lê Duẩn, Quận 1',
                'unit_price' => 800000,
                'discount_value' => 80000,
                'delivery_enabled' => '1',
                'delivery_max_distance' => 30,
                'delivery_fee' => 15000,
                'delivery_free_distance' => 5,
                'km_limit_enabled' => '1',
                'km_limit_val' => 250,
                'over_fee_val' => 5000,
                'features' => '[1,2]',
                'images' => [
                    \Illuminate\Http\UploadedFile::fake()->image('car1.jpg')
                ],
                'thumbnail_index' => 0
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Đăng ký xe thành công! Xe của bạn đang được chờ kiểm duyệt.'
            ]);

        $this->assertDatabaseHas('cars', [
            'license_plate' => '29A-99999',
            'user_id' => $user->id
        ]);
    }

    /**
     * Test user can create a trip (rent request) with status 5
     */
    public function test_can_create_trip_pending_approval(): void
    {
        $user = \App\Models\User::first();
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        // We need a car that is NOT owned by this user.
        $car = \App\Models\Car::where('user_id', '!=', $user->id)->first();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/trips', [
                'cost' => 1500000,
                'discount_amount' => 150000,
                'trip_type' => 0,
                'start_at' => '2026-07-01 08:00:00',
                'end_at' => '2026-07-03 18:00:00',
                'car_id' => $car->id,
                'delivery_address' => '456 Lê Lợi, Quận 1',
                'delivery_location' => '10.772566,106.698021'
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Gửi yêu cầu thuê xe thành công!'
            ]);

        $this->assertDatabaseHas('trips', [
            'car_id' => $car->id,
            'user_id' => $user->id,
            'status' => 5,
            'cost' => 1500000
        ]);
    }
}
