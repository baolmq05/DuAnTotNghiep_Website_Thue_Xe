<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CarController extends Controller
{
    /**
     * API Lọc xe
     * GET /api/cars
     */
    public function index(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'startDate' => 'nullable|date_format:Y-m-d H:i:s',
            'endDate' => 'nullable|date_format:Y-m-d H:i:s|after:startDate',
            'address' => 'nullable|string|max:255',
            'brand_id' => 'nullable|integer|exists:car_brands,id',
            'type_id' => 'nullable|integer|exists:car_types,id',
            'seat_count' => 'nullable|integer|min:2',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|gte:min_price',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu tìm kiếm không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        // Khởi tạo query
        $query = Car::query();

        // 1. Lọc xe rảnh lịch trong khoảng thời gian [startDate, endDate]
        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = Carbon::parse($request->startDate);
            $endDate = Carbon::parse($request->endDate);

            // Tìm các xe có chuyến đi (trip) đang hoạt động giao thoa với khoảng thời gian này
            $busyCarIds = Trip::whereIn('status', [0, 1]) // 0: chưa bắt đầu, 1: đang diễn ra
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_at', '<=', $endDate)
                      ->where('end_at', '>=', $startDate);
                })
                ->pluck('car_id')
                ->unique()
                ->toArray();

            $query->whereNotIn('id', $busyCarIds);
        }

        // 2. Lọc theo địa chỉ
        if ($request->has('address')) {
            $address = $request->address;
            $query->whereHas('carLocation', function ($q) use ($address) {
                $q->where('street_name', 'like', "%{$address}%");
            });
        }

        // 3. Lọc theo các tiêu chí khác
        if ($request->has('brand_id')) {
            $query->where('car_brand_id', $request->brand_id);
        }

        if ($request->has('type_id')) {
            $query->where('car_type_id', $request->type_id);
        }

        if ($request->has('seat_count')) {
            $query->where('seat_count', $request->seat_count);
        }

        if ($request->has('min_price')) {
            $query->where('unit_price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('unit_price', '<=', $request->max_price);
        }

        // Eager load các quan hệ & tính toán rating trung bình, tổng số chuyến đi
        $query->with(['carLocation', 'carBrand', 'carType', 'images'])
            ->withAvg('reviews', 'rating')
            ->withCount(['trips' => function ($q) {
                $q->where('status', 2); // Chỉ đếm các chuyến đi đã hoàn thành thành công
            }]);

        // Thực thi query
        $cars = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách xe thành công',
            'data' => $cars
        ]);
    }

    /**
     * API Xem chi tiết xe
     * GET /api/cars/{id}
     */
    public function show($id)
    {
        $car = Car::with([
            'carLocation',
            'carBrand',
            'carType',
            'deliveryOption',
            'usageLimit',
            'images',
            'features',
            'owner' => function ($q) {
                $q->select('id', 'name', 'avatar', 'phone', 'gender');
            },
            'reviews.reviewer' => function ($q) {
                $q->select('id', 'name', 'avatar');
            }
        ])
        ->withAvg('reviews', 'rating')
        ->withCount(['trips' => function ($q) {
            $q->where('status', 2); // Chuyến đi đã hoàn thành
        }])
        ->find($id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin xe'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết xe thành công',
            'data' => $car
        ]);
    }
}
