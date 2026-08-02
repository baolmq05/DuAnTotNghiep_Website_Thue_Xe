<?php

namespace App\Actions\Trip;

use App\Enum\TripStatus;
use App\Models\Car;
use App\Models\Trip;
use App\Models\Promotion;
use App\Models\PromotionUsage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Exception;

class CreateTripAction
{
    public function execute(User $user, array $data): Trip
    {
        // 1. Kiểm tra điều kiện tài khoản của người thuê
        $this->validateRenterEligibility($user);

        // 2. Kiểm tra thông tin xe
        $car = Car::find($data['car_id']);
        if (!$car) {
            throw new InvalidArgumentException('Không tìm thấy thông tin xe.');
        }

        if ($car->user_id === $user->id) {
            throw new InvalidArgumentException('Bạn không thể thuê xe của chính mình!');
        }

        // 3. Tính toán thời gian & chi phí thuê xe
        $start = Carbon::parse($data['start_at']);
        $end = Carbon::parse($data['end_at']);
        $diffMinutes = $start->diffInMinutes($end);
        $days = max(1, ceil($diffMinutes / 1440));

        $unitPrice = $car->unit_price;
        $insuranceFee = 0;
        $discountVal = $car->discount_value ?? 0;
        $carDiscountTotal = $discountVal * $days;

        $baseRentalPrice = ($unitPrice + $insuranceFee) * $days;
        $baseRentalPriceForPromo = max(0, ($unitPrice + $insuranceFee - $discountVal) * $days);
        $deliveryFee = $data['delivery_fee'] ?? 0;
        $totalPriceBeforePromo = $baseRentalPrice + $deliveryFee;

        // 4. Kiểm tra & tính toán mã giảm giá (nếu có)
        $promoDiscount = 0;
        $promotion = null;

        if (!empty($data['promo_code'])) {
            $promotion = $this->validateAndCalculatePromotion($data['promo_code'], $user->id, $baseRentalPriceForPromo);
            if ($promotion) {
                if ($promotion->discount_type == 0) { // Giảm theo %
                    $promoDiscount = round(($baseRentalPriceForPromo * $promotion->discount_value) / 100);
                } else { // Giảm tiền cố định
                    $promoDiscount = $promotion->discount_value;
                }
                $promoDiscount = min($promoDiscount, $baseRentalPriceForPromo);
            }
        }

        $calculatedCost = $totalPriceBeforePromo;
        $calculatedDiscountAmount = $carDiscountTotal + $promoDiscount;

        // 5. Giao dịch Database tạo Trip và PromotionUsage
        try {
            DB::beginTransaction();

            $trip = Trip::create([
                'cost' => $calculatedCost,
                'discount_amount' => $calculatedDiscountAmount,
                'status' => TripStatus::Pending->value,
                'trip_type' => $data['trip_type'] ?? 0,
                'start_at' => $data['start_at'],
                'end_at' => $data['end_at'],
                'car_id' => $data['car_id'],
                'user_id' => $user->id,
                'delivery_address' => $data['delivery_address'] ?? null,
                'delivery_location' => $data['delivery_location'] ?? null,
            ]);

            if ($promotion) {
                PromotionUsage::create([
                    'user_id' => $user->id,
                    'promotion_id' => $promotion->id,
                    'discount_amount' => $promoDiscount,
                    'used_at' => now(),
                    'trip_id' => $trip->id
                ]);
            }

            DB::commit();

            return $trip;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateRenterEligibility(User $user): void
    {
        if (empty($user->phone)) {
            throw new InvalidArgumentException('Bạn cần cập nhật số điện thoại trước khi thực hiện thuê xe. Vui lòng cập nhật tại trang Cá nhân.');
        }

        if (!$user->drivingLicense) {
            throw new InvalidArgumentException('Bạn chưa cập nhật thông tin giấy phép lái xe. Vui lòng cập nhật thông tin tại trang Cá nhân.');
        }

        if ($user->drivingLicense->status === 0) {
            throw new InvalidArgumentException('Giấy phép lái xe của bạn đang chờ duyệt. Vui lòng đợi quản trị viên phê duyệt để thuê xe.');
        }

        if ($user->drivingLicense->status === 2) {
            throw new InvalidArgumentException('Giấy phép lái xe của bạn đã bị từ chối. Vui lòng cập nhật lại thông tin tại trang Cá nhân.');
        }

        if ($user->drivingLicense->status !== 1) {
            throw new InvalidArgumentException('Giấy phép lái xe không hợp lệ. Vui lòng kiểm tra lại.');
        }
    }

    private function validateAndCalculatePromotion(string $promoCode, int $userId, float $basePrice): ?Promotion
    {
        $promotion = Promotion::where('code', $promoCode)->first();

        if (!$promotion || $promotion->status != 1) {
            throw new InvalidArgumentException('Mã giảm giá không hợp lệ.');
        }

        $now = now()->toDateString();
        if ($promotion->start_date > $now || $promotion->end_date < $now) {
            throw new InvalidArgumentException('Mã giảm giá đã hết hạn hoặc chưa đến thời gian sử dụng.');
        }

        if ($promotion->usage_limit !== null) {
            $totalUsages = PromotionUsage::where('promotion_id', $promotion->id)->count();
            if ($totalUsages >= $promotion->usage_limit) {
                throw new InvalidArgumentException('Mã giảm giá đã hết lượt sử dụng.');
            }
        }

        if ($promotion->per_user_limit !== null) {
            $userUsages = PromotionUsage::where('promotion_id', $promotion->id)
                ->where('user_id', $userId)
                ->count();
            if ($userUsages >= $promotion->per_user_limit) {
                throw new InvalidArgumentException('Bạn đã sử dụng mã giảm giá này tối đa số lần cho phép.');
            }
        }

        return $promotion;
    }
}
