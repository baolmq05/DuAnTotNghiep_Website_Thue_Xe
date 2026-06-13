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
                    'reviews'
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
            $this->assertStringContainsString('Lê Duẩn', $car['car_location']['street_name']);
        }
    }
}
