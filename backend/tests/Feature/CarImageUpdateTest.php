<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\CarLocation;
use App\Models\CarDeliveryOption;
use App\Models\CarImage;
use App\Models\Wallet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CarImageUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $car;
    protected $brand;
    protected $type;
    protected $location;
    protected $delivery;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Roles
        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Admin']);
        $ownerRole = Role::create(['name' => 'Car Owner', 'description' => 'Car Owner']);

        // 2. Create User
        $this->user = User::create([
            'name' => 'Car Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            'role_id' => $ownerRole->id,
            'status' => '1',
        ]);

        // 3. Authenticate User
        $this->token = JWTAuth::fromUser($this->user);

        // 4. Create Brand & Type
        $this->brand = CarBrand::create(['brand_name' => 'Toyota']);
        $this->type = CarType::create([
            'type_name' => 'Camry',
            'car_brand_id' => $this->brand->id
        ]);

        // 5. Create Location & Delivery Option
        $this->location = CarLocation::create([
            'address' => 'Hà Nội',
            'location' => '21.028511,105.804817'
        ]);

        $this->delivery = CarDeliveryOption::create([
            'status' => 1,
            'max_distance' => 20,
            'fee_distance' => 10000,
            'free_distance' => 5
        ]);

        // 6. Create Car
        $this->car = Car::create([
            'name' => 'Toyota Camry',
            'license_plate' => '29A-12345',
            'VIN' => '1HGCR2F8XHA045239',
            'engine_number' => 'K24W1-1234567',
            'fuel_consumption' => 10,
            'unit_price' => 500000,
            'discount_value' => 50000,
            'description' => 'Xe đẹp chạy êm',
            'rental_terms' => 'Giữ xe sạch sẽ',
            'car_location_id' => $this->location->id,
            'car_brand_id' => $this->brand->id,
            'car_type_id' => $this->type->id,
            'seat_count' => 5,
            'manufacture_year' => '2020-01-01',
            'fuel_type' => 'Xăng',
            'transmission' => 'Số tự động',
            'user_id' => $this->user->id,
            'delivery_option_id' => $this->delivery->id,
            'status' => 1
        ]);

        // 7. Seed Initial Images
        CarImage::create([
            'car_id' => $this->car->id,
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/car1.jpg',
            'is_thumbnail' => 1
        ]);
        CarImage::create([
            'car_id' => $this->car->id,
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/car2.jpg',
            'is_thumbnail' => 0
        ]);
    }

    /**
     * Test updating car images successfully
     */
    public function test_can_update_car_images(): void
    {
        // We will keep car1.jpg and add a new image car3.jpg, setting car3.jpg as thumbnail
        $newImages = [
            'https://res.cloudinary.com/demo/image/upload/car1.jpg',
            'https://res.cloudinary.com/demo/image/upload/car3.jpg',
        ];

        $payload = [
            'license_plate' => '29A-12345',
            'VIN' => '1HGCR2F8XHA045239',
            'engine_number' => 'K24W1-1234567',
            'car_brand_id' => $this->brand->id,
            'car_type_id' => $this->type->id,
            'seat_count' => 5,
            'manufacture_year' => 2020,
            'transmission' => 'Số tự động',
            'fuel_type' => 'Xăng',
            'fuel_consumption' => 10,
            'description' => 'Xe đẹp chạy êm đã sửa',
            'unit_price' => 500000,
            'discount_value' => 50000,
            'address' => 'Hà Nội',
            'location' => '21.028511,105.804817',
            'delivery_enabled' => '1',
            'delivery_max_distance' => 20,
            'delivery_fee' => 10000,
            'delivery_free_distance' => 5,
            'rental_terms' => 'Giữ xe sạch sẽ',
            'features' => [],
            'images' => $newImages,
            'thumbnail_index' => 1 // car3.jpg is index 1
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/cars/{$this->car->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cập nhật thông tin xe thành công! Xe của bạn đang chờ kiểm duyệt lại.'
            ]);

        // Assert database matches updated images
        $this->assertDatabaseMissing('car_images', [
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/car2.jpg'
        ]);

        $this->assertDatabaseHas('car_images', [
            'car_id' => $this->car->id,
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/car1.jpg',
            'is_thumbnail' => 0
        ]);

        $this->assertDatabaseHas('car_images', [
            'car_id' => $this->car->id,
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/car3.jpg',
            'is_thumbnail' => 1
        ]);
    }

    /**
     * Test update fails validation if images list is empty
     */
    public function test_update_car_images_validation_fails_with_empty_images(): void
    {
        $payload = [
            'license_plate' => '29A-12345',
            'VIN' => '1HGCR2F8XHA045239',
            'engine_number' => 'K24W1-1234567',
            'car_brand_id' => $this->brand->id,
            'car_type_id' => $this->type->id,
            'seat_count' => 5,
            'manufacture_year' => 2020,
            'transmission' => 'Số tự động',
            'fuel_type' => 'Xăng',
            'fuel_consumption' => 10,
            'description' => 'Xe đẹp chạy êm đã sửa',
            'unit_price' => 500000,
            'discount_value' => 50000,
            'address' => 'Hà Nội',
            'location' => '21.028511,105.804817',
            'delivery_enabled' => '1',
            'delivery_max_distance' => 20,
            'delivery_fee' => 10000,
            'delivery_free_distance' => 5,
            'rental_terms' => 'Giữ xe sạch sẽ',
            'features' => [],
            'images' => [], // Empty images
            'thumbnail_index' => 0
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/cars/{$this->car->id}", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['images']);
    }

    /**
     * Test deletes old image from Cloudinary when credentials are configured
     */
    public function test_deletes_old_image_from_cloudinary_when_credentials_configured(): void
    {
        // 1. Mock HTTP requests to Cloudinary API
        \Illuminate\Support\Facades\Http::fake([
            'https://api.cloudinary.com/*' => \Illuminate\Support\Facades\Http::response(['result' => 'ok'], 200)
        ]);

        // 2. Set environment variables
        \Illuminate\Support\Env::getRepository()->set('CLOUDINARY_API_KEY', 'test_key');
        \Illuminate\Support\Env::getRepository()->set('CLOUDINARY_API_SECRET', 'test_secret');
        $_ENV['CLOUDINARY_API_KEY'] = 'test_key';
        $_ENV['CLOUDINARY_API_SECRET'] = 'test_secret';

        // We will replace car2.jpg with car3.jpg (removing car2.jpg)
        $newImages = [
            'https://res.cloudinary.com/demo/image/upload/car1.jpg',
            'https://res.cloudinary.com/demo/image/upload/car3.jpg',
        ];

        $payload = [
            'license_plate' => '29A-12345',
            'VIN' => '1HGCR2F8XHA045239',
            'engine_number' => 'K24W1-1234567',
            'car_brand_id' => $this->brand->id,
            'car_type_id' => $this->type->id,
            'seat_count' => 5,
            'manufacture_year' => 2020,
            'transmission' => 'Số tự động',
            'fuel_type' => 'Xăng',
            'fuel_consumption' => 10,
            'description' => 'Xe đẹp chạy êm đã sửa',
            'unit_price' => 500000,
            'discount_value' => 50000,
            'address' => 'Hà Nội',
            'location' => '21.028511,105.804817',
            'delivery_enabled' => '1',
            'delivery_max_distance' => 20,
            'delivery_fee' => 10000,
            'delivery_free_distance' => 5,
            'rental_terms' => 'Giữ xe sạch sẽ',
            'features' => [],
            'images' => $newImages,
            'thumbnail_index' => 1
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/cars/{$this->car->id}", $payload);

        $response->assertStatus(200);

        // Assert that the delete API was called with the correct public_id (car2)
        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return $request->url() === 'https://api.cloudinary.com/v1_1/djbobb5oe/image/destroy' &&
                   $request['public_id'] === 'car2' &&
                   $request['api_key'] === 'test_key';
        });

        // 3. Clear environment variables
        \Illuminate\Support\Env::getRepository()->set('CLOUDINARY_API_KEY', '');
        \Illuminate\Support\Env::getRepository()->set('CLOUDINARY_API_SECRET', '');
        unset($_ENV['CLOUDINARY_API_KEY']);
        unset($_ENV['CLOUDINARY_API_SECRET']);
    }
}
