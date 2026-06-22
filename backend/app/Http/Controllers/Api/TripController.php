<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    /**
     * API Tạo chuyến đi mới (Yêu cầu thuê xe)
     * POST /api/trips
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'cost' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'trip_type' => 'required|in:0,1',
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s|after:start_at',
            'car_id' => 'required|exists:cars,id',
            'delivery_address' => 'nullable|string',
            'delivery_location' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $car = Car::find($request->car_id);
        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin xe'
            ], 404);
        }

        if ($car->user_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không thể thuê xe của chính mình!'
            ], 400);
        }

        // Tạo chuyến đi với status là 5 (Chờ duyệt)
        $trip = Trip::create([
            'cost' => $request->cost,
            'discount_amount' => $request->discount_amount ?? 0,
            'status' => 5, // 5: Chờ duyệt
            'trip_type' => $request->trip_type,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'car_id' => $request->car_id,
            'user_id' => $user->id,
            'delivery_address' => $request->delivery_address,
            'delivery_location' => $request->delivery_location,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gửi yêu cầu thuê xe thành công!',
            'data' => $trip
        ], 201);
    }

    /**
     * API Lấy danh sách chuyến đi của tôi
     * GET /api/trips
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        // Chuyến đã đặt (Renter)
        $bookedTrips = Trip::where('user_id', $user->id)
            ->with(['car.carLocation', 'car.images', 'car.carBrand', 'car.carType'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Xe cho thuê của tôi (Owner)
        $ownerTrips = Trip::whereHas('car', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['car.carLocation', 'car.images', 'car.carBrand', 'car.carType', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'booked' => $bookedTrips,
                'owner' => $ownerTrips
            ]
        ]);
    }
}
