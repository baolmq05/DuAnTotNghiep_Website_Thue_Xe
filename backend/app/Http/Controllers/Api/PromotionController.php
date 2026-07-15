<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('images')->get();
        return response()->json([
            'success' => true,
            'message' => 'Danh sách khuyến mãi.',
            'data' => $promotions,
        ]);
    }
    public function show($id)
    {
        $promotion = Promotion::with('images')->find($id);
        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Khuyến mãi không tồn tại.',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Chi tiết khuyến mãi.',
            'data' => $promotion,
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|unique:promotions,code',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'discount_type' => 'required|in:0,1',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            $promotion = Promotion::create($validatedData);
    
            return response()->json([
                'success' => true,
                'data' => $promotion
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
    
            $promotion = Promotion::findOrFail($id);
    
            $validatedData = $request->validate([
                'code' => 'required|unique:promotions,code,' . $id,
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'discount_type' => 'required|in:0,1',
                'discount_value' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'usage_limit' => 'required|integer',
                'per_user_limit' => 'required|integer',
                'status' => 'required|in:0,1',
                'user_id' => 'nullable',
            ]);
    
            $promotion->update($validatedData);
    
            return response()->json([
                'success' => true,
                'data' => $promotion
            ]);
    
        } catch (\Exception $e) {
    
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa khuyến mãi thành công.',
        ]);
    }

    public function check(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'code' => 'required|string',
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s|after:start_at',
            'car_id' => 'required|exists:cars,id',
            'delivery_fee' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để sử dụng mã giảm giá.'
            ], 401);
        }

        $promotion = Promotion::where('code', $request->code)->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại.'
            ], 404);
        }

        if ($promotion->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã bị vô hiệu hóa.'
            ], 400);
        }

        $now = now()->toDateString();
        if ($promotion->start_date > $now || $promotion->end_date < $now) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã hết hạn hoặc chưa đến thời gian sử dụng.'
            ], 400);
        }

        // Check usage_limit
        if ($promotion->usage_limit !== null) {
            $totalUsages = \App\Models\PromotionUsage::where('promotion_id', $promotion->id)->count();
            if ($totalUsages >= $promotion->usage_limit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá đã hết lượt sử dụng.'
                ], 400);
            }
        }

        // Check per_user_limit
        if ($promotion->per_user_limit !== null) {
            $userUsages = \App\Models\PromotionUsage::where('promotion_id', $promotion->id)
                ->where('user_id', $user->id)
                ->count();
            if ($userUsages >= $promotion->per_user_limit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã sử dụng mã giảm giá này tối đa số lần cho phép.'
                ], 400);
            }
        }

        // Calculate pricing and discount
        $car = \App\Models\Car::find($request->car_id);
        $unitPrice = $car->unit_price;
        $insuranceFee = round($unitPrice * 0.09);
        $discountVal = $car->discount_value ?? 0;

        $start = \Carbon\Carbon::parse($request->start_at);
        $end = \Carbon\Carbon::parse($request->end_at);
        $diffMinutes = $start->diffInMinutes($end);
        $days = max(1, ceil($diffMinutes / 1440));

        $baseRentalPrice = ($unitPrice + $insuranceFee - $discountVal) * $days;
        $deliveryFee = $request->input('delivery_fee', 0);
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

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công.',
            'data' => [
                'promotion_id' => $promotion->id,
                'code' => $promotion->code,
                'name' => $promotion->name,
                'discount_type' => $promotion->discount_type,
                'discount_value' => $promotion->discount_value,
                'discount_amount' => $discountAmount,
                'new_total' => $newTotal
            ]
        ]);
    }
}

