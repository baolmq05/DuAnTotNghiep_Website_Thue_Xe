<?php

namespace Tests\Feature;

use App\Enum\TripStatus;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarDeliveryOption;
use App\Models\CarLocation;
use App\Models\CarType;
use App\Models\DrivingLicense;
use App\Models\Notification;
use App\Models\PendingBalance;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\User;
use App\Models\Wallet;
use App\Services\VNPayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class VNPayPaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $renter;
    private Car $car;
    private Trip $trip;
    private VNPayService $vnpayService;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Roles
        Role::firstOrCreate(['id' => 1], ['name' => 'Admin']);
        Role::firstOrCreate(['id' => 2], ['name' => 'Host']);
        Role::firstOrCreate(['id' => 3], ['name' => 'Customer']);

        // 2. Car metadata
        $brand = CarBrand::firstOrCreate(['id' => 1], ['brand_name' => 'Mazda']);
        $type = CarType::firstOrCreate(['id' => 1], ['type_name' => 'Sedan', 'car_brand_id' => $brand->id]);
        $location = CarLocation::firstOrCreate(['id' => 1], ['location' => 'TP.HCM', 'address' => '456 Le Loi']);
        $delivery = CarDeliveryOption::firstOrCreate(['id' => 1], [
            'max_distance' => 20,
            'fee_distance' => 10000,
            'free_distance' => 5,
            'status' => 1,
        ]);

        // 3. Users
        $this->owner = User::firstOrCreate(
            ['email' => 'vnpay_owner@example.com'],
            ['name' => 'Chủ xe VNPay', 'password' => bcrypt('secret123'), 'phone' => '0981111111', 'role_id' => 2]
        );

        $this->renter = User::firstOrCreate(
            ['email' => 'vnpay_renter@example.com'],
            ['name' => 'Khách thuê VNPay', 'password' => bcrypt('secret123'), 'phone' => '0982222222', 'role_id' => 3]
        );

        // GPLX for renter
        $license = DrivingLicense::updateOrCreate(
            ['user_id' => $this->renter->id],
            [
                'full_name' => 'Khách thuê VNPay',
                'DOB' => '1995-01-01',
                'driving_license_number' => 'GPLX888888',
                'image' => 'licenses/vnpay_sample.jpg',
                'status' => 1
            ]
        );
        $this->renter->update(['driving_license_id' => $license->id]);

        // 4. Car
        $this->car = Car::create([
            'name' => 'Mazda 3 2024 Luxury',
            'user_id' => $this->owner->id,
            'car_brand_id' => $brand->id,
            'car_type_id' => $type->id,
            'car_location_id' => $location->id,
            'delivery_option_id' => $delivery->id,
            'license_plate' => '51K-888.88',
            'VIN' => 'VIN999888777666',
            'engine_number' => 'ENG999888',
            'manufacture_year' => 2024,
            'unit_price' => 1000000,
            'status' => 1,
            'transmission' => 1,
            'fuel_type' => 1,
            'seat_count' => 5,
            'fuel_consumption' => '7L/100km',
            'description' => 'Xe mới sạch sẽ',
        ]);

        // 5. Trip (Waiting payment status)
        $this->trip = Trip::create([
            'user_id' => $this->renter->id,
            'car_id' => $this->car->id,
            'start_at' => now()->addDay()->format('Y-m-d 08:00:00'),
            'end_at' => now()->addDays(3)->format('Y-m-d 08:00:00'),
            'cost' => 2000000,
            'discount_amount' => 0,
            'status' => TripStatus::WaitingPayment->value,
            'deposit_type' => 1,
        ]);

        $this->vnpayService = app(VNPayService::class);
    }

    /**
     * Test creating VNPay checkout URL for rental payment
     */
    public function test_can_create_vnpay_payment_url_for_rental(): void
    {
        $token = JWTAuth::fromUser($this->renter);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/vnpay/create-payment', [
                'payment_type' => 'rental',
                'amount' => 2000000,
                'trip_id' => $this->trip->id
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $paymentUrl = $response->json('payment_url');
        $this->assertNotEmpty($paymentUrl);
        $this->assertStringContainsString('vnp_Amount=200000000', $paymentUrl);
        $this->assertStringContainsString('vnp_SecureHash=', $paymentUrl);
        $this->assertStringContainsString('rental_' . $this->trip->id, $paymentUrl);
    }

    /**
     * Test creating VNPay checkout URL for wallet deposit
     */
    public function test_can_create_vnpay_payment_url_for_wallet_deposit(): void
    {
        $token = JWTAuth::fromUser($this->renter);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/vnpay/create-payment', [
                'payment_type' => 'deposit',
                'amount' => 500000,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $paymentUrl = $response->json('payment_url');
        $this->assertNotEmpty($paymentUrl);
        $this->assertStringContainsString('vnp_Amount=50000000', $paymentUrl);
        $this->assertStringContainsString('deposit_' . $this->renter->id, $paymentUrl);
    }

    /**
     * Test IPN webhook processes successful rental payment
     */
    public function test_vnpay_ipn_handles_successful_rental_payment(): void
    {
        $txnRef = "rental_{$this->trip->id}_{$this->owner->id}_" . time();
        $amount = 2000000;
        $vnpAmount = $amount * 100;
        $transactionNo = 'VNP_TXN_' . rand(100000, 999999);

        $inputData = [
            'vnp_TmnCode' => config('vnpay.tmn_code'),
            'vnp_Amount' => (string) $vnpAmount,
            'vnp_BankCode' => 'NCB',
            'vnp_BankTranNo' => 'VNP123456',
            'vnp_CardType' => 'ATM',
            'vnp_OrderInfo' => 'Thanh toan thue xe',
            'vnp_PayDate' => date('YmdHis'),
            'vnp_ResponseCode' => '00',
            'vnp_TmnCode' => config('vnpay.tmn_code'),
            'vnp_TransactionNo' => $transactionNo,
            'vnp_TransactionStatus' => '00',
            'vnp_TxnRef' => $txnRef,
        ];

        // Generate signature
        $hashSecret = config('vnpay.hash_secret');
        ksort($inputData);
        $hashData = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if (substr($key, 0, 4) === 'vnp_') {
                if ($i == 1) {
                    $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
                } else {
                    $hashData .= urlencode($key) . '=' . urlencode($value);
                    $i = 1;
                }
            }
        }
        $inputData['vnp_SecureHash'] = hash_hmac('sha512', $hashData, $hashSecret);

        // Call IPN
        $response = $this->getJson('/api/vnpay/ipn?' . http_build_query($inputData));

        $response->assertStatus(200)
            ->assertJson([
                'RspCode' => '00',
                'Message' => 'Confirm Success'
            ]);

        // Verify database updates
        $this->trip->refresh();
        $this->assertEquals(TripStatus::Confirmed->value, $this->trip->status);

        // Transaction record exists
        $txn = Transaction::where('transaction_code', $transactionNo)->first();
        $this->assertNotNull($txn);
        $this->assertEquals($amount, (float) $txn->amount);
        $this->assertEquals($this->trip->id, $txn->trip_id);

        // PendingBalance record exists
        $pending = PendingBalance::where('transaction_id', $txn->id)->first();
        $this->assertNotNull($pending);
        $this->assertEquals($this->owner->id, $pending->receiver_id);
        $this->assertEquals($this->renter->id, $pending->payer_id);

        // Notifications created
        $this->assertTrue(Notification::where('user_id', $this->owner->id)->exists());
        $this->assertTrue(Notification::where('user_id', $this->renter->id)->exists());
    }

    /**
     * Test IPN rejects invalid checksum
     */
    public function test_vnpay_ipn_rejects_invalid_checksum(): void
    {
        $inputData = [
            'vnp_Amount' => '200000000',
            'vnp_ResponseCode' => '00',
            'vnp_TransactionNo' => '12345678',
            'vnp_TxnRef' => "rental_{$this->trip->id}_{$this->owner->id}_" . time(),
            'vnp_SecureHash' => 'INVALID_HASH_VALUE_HERE'
        ];

        $response = $this->getJson('/api/vnpay/ipn?' . http_build_query($inputData));

        $response->assertStatus(200)
            ->assertJson([
                'RspCode' => '97',
                'Message' => 'Signature invalid'
            ]);
    }

    /**
     * Test verify endpoint from frontend redirection
     */
    public function test_vnpay_verify_endpoint_processes_and_returns_status(): void
    {
        $token = JWTAuth::fromUser($this->renter);
        $txnRef = "rental_{$this->trip->id}_{$this->owner->id}_" . time();
        $amount = 2000000;
        $transactionNo = 'VNP_VERIFY_' . rand(100000, 999999);

        $inputData = [
            'vnp_Amount' => (string) ($amount * 100),
            'vnp_BankCode' => 'NCB',
            'vnp_OrderInfo' => 'Thanh toan thue xe',
            'vnp_PayDate' => date('YmdHis'),
            'vnp_ResponseCode' => '00',
            'vnp_TmnCode' => config('vnpay.tmn_code'),
            'vnp_TransactionNo' => $transactionNo,
            'vnp_TransactionStatus' => '00',
            'vnp_TxnRef' => $txnRef,
        ];

        $hashSecret = config('vnpay.hash_secret');
        ksort($inputData);
        $hashData = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if (substr($key, 0, 4) === 'vnp_') {
                if ($i == 1) {
                    $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
                } else {
                    $hashData .= urlencode($key) . '=' . urlencode($value);
                    $i = 1;
                }
            }
        }
        $inputData['vnp_SecureHash'] = hash_hmac('sha512', $hashData, $hashSecret);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vnpay/verify?' . http_build_query($inputData));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Thanh toán thành công.',
                'data' => [
                    'transaction_no' => $transactionNo,
                    'amount' => $amount,
                    'payment_type' => 'rental'
                ]
            ]);

        $this->trip->refresh();
        $this->assertEquals(TripStatus::Confirmed->value, $this->trip->status);
    }
}
