<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\PromotionUsage;
use App\Models\Car;
use Carbon\Carbon;
use App\Http\Requests\Promotion\StorePromotionRequest;
use App\Http\Requests\Promotion\UpdatePromotionRequest;
use App\Http\Requests\Promotion\CheckPromotionRequest;
use Exception;

class PromotionController extends Controller
{
    /**
     * Get active promotion list.
     */
    public function index()
    {
        $promotions = Promotion::with('images')
            ->where('end_date', '>=', now()->toDateString())
            ->where('status', '1')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Danh sách khuyến mãi.',
            'data' => $promotions,
        ]);
    }

    /**
     * Get detail of a specific active promotion.
     */
    public function show($id)
    {
        $promotion = Promotion::with('images')->find($id);

        if (!$promotion || $promotion->end_date < now()->toDateString() || $promotion->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Khuyến mãi không tồn tại hoặc đã hết hạn.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Chi tiết khuyến mãi.',
            'data' => $promotion,
        ]);
    }

    /**
     * Store a new promotion.
     */
    public function store(StorePromotionRequest $request)
    {
        try {
            $promotion = Promotion::create($request->validated());

            return response()->json([
                'success' => true,
                'data' => $promotion
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Update an existing promotion.
     */
    public function update(UpdatePromotionRequest $request, $id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => $promotion
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    /**
     * Delete a promotion.
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa khuyến mãi thành công.',
        ]);
    }

    /**
     * Check and calculate promotion.
     */
    public function check(CheckPromotionRequest $request)
    {
        $user = auth('api')->user();

        $promotion = Promotion::where('code', $request->code)->first();
        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại.'
            ], 404);
        }

        // Validate promotion status, expiration, and usage limits
        $validationResult = $this->validatePromotionRules($promotion, $user);
        if ($validationResult !== null) {
            return response()->json([
                'success' => false,
                'message' => $validationResult['message']
            ], 400);
        }

        // Calculate pricing and final discount amount
        $car = Car::find($request->car_id);
        $pricingData = $this->calculateDiscountData(
            $promotion,
            $car,
            $request->start_at,
            $request->end_at,
            floatval($request->input('delivery_fee', 0))
        );

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công.',
            'data' => $pricingData
        ]);
    }

    /**
     * Validate rules of a promotion (Helper).
     */
    private function validatePromotionRules(Promotion $promotion, $user): ?array
    {
        if ($promotion->status != 1) {
            return ['message' => 'Mã giảm giá đã bị vô hiệu hóa.'];
        }

        $now = now()->toDateString();
        if ($promotion->start_date > $now || $promotion->end_date < $now) {
            return ['message' => 'Mã giảm giá đã hết hạn hoặc chưa đến thời gian sử dụng.'];
        }

        // Check total usage limit
        if ($promotion->usage_limit !== null) {
            $totalUsages = PromotionUsage::where('promotion_id', $promotion->id)->count();
            if ($totalUsages >= $promotion->usage_limit) {
                return ['message' => 'Mã giảm giá đã hết lượt sử dụng.'];
            }
        }

        // Check limit per user
        if ($promotion->per_user_limit !== null) {
            $userUsages = PromotionUsage::where('promotion_id', $promotion->id)
                ->where('user_id', $user->id)
                ->count();
            if ($userUsages >= $promotion->per_user_limit) {
                return ['message' => 'Bạn đã sử dụng mã giảm giá này tối đa số lần cho phép.'];
            }
        }

        return null;
    }

    /**
     * Calculate base rental price, discount amount, and new total (Helper).
     */
    private function calculateDiscountData(Promotion $promotion, Car $car, string $startAt, string $endAt, float $deliveryFee): array
    {
        $unitPrice = $car->unit_price;
        $insuranceFee = 0;
        $discountVal = $car->discount_value ?? 0;

        $start = Carbon::parse($startAt);
        $end = Carbon::parse($endAt);
        $diffMinutes = $start->diffInMinutes($end);
        $days = max(1, ceil($diffMinutes / 1440));

        $baseRentalPrice = ($unitPrice + $insuranceFee - $discountVal) * $days;
        $totalPriceBeforePromo = $baseRentalPrice + $deliveryFee;

        $discountAmount = 0;
        if ($promotion->discount_type == 0) { // percentage
            $discountAmount = round(($baseRentalPrice * $promotion->discount_value) / 100);
        } else { // fixed amount
            $discountAmount = $promotion->discount_value;
        }

        // Cap the discount amount at base rental price
        $discountAmount = min($discountAmount, $baseRentalPrice);
        $newTotal = max(0, $totalPriceBeforePromo - $discountAmount);

        return [
            'promotion_id'    => $promotion->id,
            'code'            => $promotion->code,
            'name'            => $promotion->name,
            'discount_type'   => $promotion->discount_type,
            'discount_value'  => $promotion->discount_value,
            'discount_amount' => $discountAmount,
            'new_total'       => $newTotal
        ];
    }
}
