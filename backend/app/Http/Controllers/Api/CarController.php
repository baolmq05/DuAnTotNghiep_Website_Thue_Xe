<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\FilterCarRequest;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Services\CloudinaryService;
use App\Enum\TripStatus;
use App\Models\Car;
use App\Models\Trip;
use App\Models\CarBrand;
use App\Models\CarType;
use App\Models\Feature;
use App\Models\CarLocation;
use App\Models\CarDeliveryOption;
use App\Models\CarUsageLimit;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarController extends Controller
{
    /**
     * API Lọc xe
     * GET /api/cars
     */
    public function index(FilterCarRequest $request)
    {
        // Khởi tạo query
        $query = Car::query();

        // 1. Lọc xe rảnh lịch trong khoảng thời gian [startDate, endDate]
        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = Carbon::parse($request->startDate);
            $endDate = Carbon::parse($request->endDate);

            // Tìm các xe có chuyến đi (trip) đang hoạt động / chờ duyệt giao thoa với khoảng thời gian này
            $busyCarIds = Trip::whereIn('status', [
                    TripStatus::Pending->value,
                    TripStatus::Confirmed->value,
                    TripStatus::Ongoing->value,
                    TripStatus::WaitingExtension->value,
                    TripStatus::WaitingReturn->value,
                ])
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
                $q->where('address', 'like', "%{$address}%");
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

        // Lọc theo chủ sở hữu (user_id)
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Lọc theo trạng thái xe (status)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } elseif (!$request->has('user_id')) {
            // Mặc định chỉ lấy xe đang hoạt động (status = 1) cho khách xem tìm kiếm xe
            $query->where('status', 1);
        }

        // Eager load các quan hệ & tính toán rating trung bình, tổng số chuyến đi, doanh thu tháng này
        $query->select('cars.*')
            ->selectSub(function ($q) {
                $q->selectRaw('COALESCE(SUM(cost - discount_amount), 0)')
                    ->from('trips')
                    ->whereColumn('trips.car_id', 'cars.id')
                    ->where('trips.status', TripStatus::Complete->value)
                    ->whereMonth('trips.created_at', now()->month)
                    ->whereYear('trips.created_at', now()->year);
            }, 'revenue')
            ->with([
                'carLocation',
                'carBrand',
                'carType',
                'images',
                'owner' => function ($q) {
                    $q->select('id', 'name', 'avatar');
                }
            ])
            ->withAvg(['reviews' => function ($q) {
                $q->where('review_type', 1);
            }], 'rating')
            ->withCount([
                'trips' => function ($q) {
                    $q->where('status', TripStatus::Complete->value); // Chỉ đếm các chuyến đi đã hoàn thành thành công
                }
            ]);

        // Sắp xếp theo xe nổi bật (điểm đánh giá cao nhất & nhiều chuyến đi đã hoàn thành nhất)
        if ($request->input('sort_by') == 'featured') {
            $query->orderByDesc('reviews_avg_rating')
                  ->orderByDesc('trips_count');
        }

        // Giới hạn số lượng bản ghi trả về
        if ($request->filled('limit')) {
            $query->limit(intval($request->limit));
        }

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
            'reviews' => function ($q) {
                $q->where('review_type', 1);
            },
            'reviews.reviewer' => function ($q) {
                $q->select('id', 'name', 'avatar');
            },
            'trips' => function ($q) {
                $q->whereIn('status', [
                    TripStatus::Pending->value,
                    TripStatus::Confirmed->value,
                    TripStatus::Ongoing->value,
                    TripStatus::WaitingExtension->value,
                    TripStatus::WaitingReturn->value,
                ])
                    ->select('id', 'car_id', 'user_id', 'start_at', 'end_at', 'status');
            }
        ])
            ->withAvg(['reviews' => function ($q) {
                $q->where('review_type', 1);
            }], 'rating')
            ->withCount([
                'trips' => function ($q) {
                    $q->where('status', TripStatus::Complete->value); // Chuyến đi đã hoàn thành
                }
            ])
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

    /**
     * API Lấy danh sách hãng xe
     * GET /api/car-brands
     */
    public function getBrands()
    {
        $brands = CarBrand::orderBy('brand_name', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    /**
     * API Lấy mẫu xe theo hãng xe
     * GET /api/car-brands/{id}/types
     */
    public function getTypes($brandId)
    {
        $types = CarType::where('car_brand_id', $brandId)->orderBy('type_name', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }

    /**
     * API Lấy danh sách tính năng xe
     * GET /api/car-features
     */
    public function getFeatures()
    {
        $features = Feature::where('status', 1)->orderBy('feature_name', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $features
        ]);
    }

    /**
     * API Đăng ký xe mới
     * POST /api/cars
     */
    public function store(StoreCarRequest $request)
    {
        $user = auth('api')->user();

        if (!$user || empty($user->phone)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần cập nhật số điện thoại tại trang Cá nhân trước khi đăng ký xe.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // 1. Create Location
            $location = CarLocation::create([
                'location' => $request->input('location'),
                'address' => $request->input('address'),
            ]);

            // 2. Create Delivery Option
            $deliveryOption = CarDeliveryOption::create([
                'status' => $request->input('delivery_enabled') == '1' ? 1 : 0,
                'max_distance' => $request->input('delivery_enabled') == '1' ? floatval($request->input('delivery_max_distance', 0)) : 0,
                'fee_distance' => $request->input('delivery_enabled') == '1' ? floatval($request->input('delivery_fee', 0)) : 0,
                'free_distance' => $request->input('delivery_enabled') == '1' ? floatval($request->input('delivery_free_distance', 0)) : 0,
            ]);

            // 3. Create Usage Limit
            $usageLimit = CarUsageLimit::create([
                'status' => $request->input('km_limit_enabled') == '1' ? 1 : 0,
                'max_daily_distance' => $request->input('km_limit_enabled') == '1' ? floatval($request->input('km_limit_val', 0)) : 0,
                'extra_distance_fee' => $request->input('km_limit_enabled') == '1' ? floatval($request->input('over_fee_val', 0)) : 0,
            ]);

            // Type name for car name (e.g. Camry)
            $type = CarType::find($request->car_type_id);
            $carName = $type ? $type->type_name : '';

            // 4. Create Car
            $car = Car::create([
                'name' => trim($carName),
                'license_plate' => $request->input('license_plate'),
                'VIN' => $request->input('VIN'),
                'engine_number' => $request->input('engine_number'),
                'fuel_consumption' => $request->input('fuel_consumption'),
                'unit_price' => $request->input('unit_price'),
                'discount_value' => $request->input('discount_value', 0),
                'description' => $request->input('description'),
                'rental_terms' => $request->input('rental_terms'),
                'car_location_id' => $location->id,
                'car_brand_id' => $request->car_brand_id,
                'car_type_id' => $request->car_type_id,
                'seat_count' => $request->input('seat_count'),
                'manufacture_year' => $request->input('manufacture_year') . '-01-01',
                'fuel_type' => $request->input('fuel_type'),
                'transmission' => $request->input('transmission'),
                'user_id' => $user->id,
                'delivery_option_id' => $deliveryOption->id,
                'usage_limit_id' => $usageLimit->id,
                'status' => 2 // 2: Chờ duyệt (Pending approval)
            ]);

            // 5. Sync Features
            if ($request->filled('features')) {
                // Feature IDs are passed as a JSON array or comma separated string
                $featureData = $request->input('features');
                $featureIds = is_array($featureData) ? $featureData : json_decode($featureData, true);
                if (json_last_error() !== JSON_ERROR_NONE && !is_array($featureData)) {
                    $featureIds = explode(',', $featureData);
                }
                if (is_array($featureIds)) {
                    $car->features()->sync(array_filter($featureIds));
                }
            }

            // 6. Handle Images Upload
            $imageUrls = $request->input('images');

            if (is_string($imageUrls)) {
                $imageUrls = json_decode($imageUrls, true);
            }

            if (!is_array($imageUrls)) {
                $imageUrls = [];
            }
            $thumbnailIndex = intval($request->thumbnail_index);
            foreach ($imageUrls as $index => $url) {

                CarImage::create([
                    'car_id' => $car->id,
                    'image_url' => $url,
                    'is_thumbnail' => $index == $thumbnailIndex
                ]);

            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký xe thành công! Xe của bạn đang được chờ kiểm duyệt.',
                'data' => $car
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký xe.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Cập nhật thông tin xe
     * PUT /api/cars/{id}
     */
    public function update(UpdateCarRequest $request, $id)
    {
        $user = auth('api')->user();

        $car = Car::find($id);
        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin xe'
            ], 404);
        }

        // Kiểm tra quyền sở hữu
        if ($car->user_id !== $user->id && $user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền sửa thông tin xe này.'
            ], 403);
        }

        // Kiểm tra chuyến đi đang diễn ra
        if ($car->has_ongoing_trip) {
            return response()->json([
                'success' => false,
                'message' => 'Xe đang có chuyến đi đang diễn ra, không thể thay đổi trạng thái hoặc chỉnh sửa thông tin xe.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // 1. Update/Create Location
            $location = CarLocation::updateOrCreate(
                ['id' => $car->car_location_id],
                [
                    'location' => $request->input('location'),
                    'address' => $request->input('address'),
                ]
            );

            // 2. Update/Create Delivery Option
            $deliveryOption = CarDeliveryOption::updateOrCreate(
                ['id' => $car->delivery_option_id],
                [
                    'status' => $request->input('delivery_enabled') == '1' ? 1 : 0,
                    'max_distance' => $request->input('delivery_enabled') == '1' ? floatval($request->input('delivery_max_distance', 0)) : 0,
                    'fee_distance' => $request->input('delivery_enabled') == '1' ? floatval($request->input('delivery_fee', 0)) : 0,
                    'free_distance' => $request->input('delivery_enabled') == '1' ? floatval($request->input('delivery_free_distance', 0)) : 0,
                ]
            );

            // 3. Update/Create Usage Limit
            $usageLimit = CarUsageLimit::updateOrCreate(
                ['id' => $car->usage_limit_id],
                [
                    'status' => $request->input('km_limit_enabled') == '1' ? 1 : 0,
                    'max_daily_distance' => $request->input('km_limit_enabled') == '1' ? floatval($request->input('km_limit_val', 0)) : 0,
                    'extra_distance_fee' => $request->input('km_limit_enabled') == '1' ? floatval($request->input('over_fee_val', 0)) : 0,
                ]
            );

            // Type name for car name (e.g. Camry)
            $type = CarType::find($request->car_type_id);
            $carName = $type ? $type->type_name : '';

            // 4. Update Car
            $car->update([
                'name' => trim($carName),
                'license_plate' => $request->input('license_plate'),
                'VIN' => $request->input('VIN'),
                'engine_number' => $request->input('engine_number'),
                'fuel_consumption' => $request->input('fuel_consumption'),
                'unit_price' => $request->input('unit_price'),
                'discount_value' => $request->input('discount_value', 0),
                'description' => $request->input('description'),
                'rental_terms' => $request->input('rental_terms'),
                'car_location_id' => $location->id,
                'car_brand_id' => $request->car_brand_id,
                'car_type_id' => $request->car_type_id,
                'seat_count' => $request->input('seat_count'),
                'manufacture_year' => $request->input('manufacture_year') . '-01-01',
                'fuel_type' => $request->input('fuel_type'),
                'transmission' => $request->input('transmission'),
                'delivery_option_id' => $deliveryOption->id,
                'usage_limit_id' => $usageLimit->id,
                'status' => 2 // Chuyển về trạng thái chờ duyệt sau khi chỉnh sửa
            ]);

            // 5. Sync Features
            if ($request->filled('features')) {
                $featureData = $request->input('features');
                $featureIds = is_array($featureData) ? $featureData : json_decode($featureData, true);
                if (json_last_error() !== JSON_ERROR_NONE && !is_array($featureData)) {
                    $featureIds = explode(',', $featureData);
                }
                if (is_array($featureIds)) {
                    $car->features()->sync(array_filter($featureIds));
                }
            } else {
                $car->features()->sync([]);
            }

            // 6. Handle Images
            $imageUrls = $request->input('images');
            if (is_string($imageUrls)) {
                $imageUrls = json_decode($imageUrls, true);
            }
            if (!is_array($imageUrls)) {
                $imageUrls = [];
            }
            $thumbnailIndex = intval($request->thumbnail_index);

            // Lấy danh sách ảnh cũ trước khi xóa
            $oldImageUrls = $car->images()->pluck('image_url')->toArray();

            // Xóa hình ảnh cũ ở database và cập nhật hình ảnh mới
            $car->images()->delete();
            foreach ($imageUrls as $index => $url) {
                CarImage::create([
                    'car_id' => $car->id,
                    'image_url' => $url,
                    'is_thumbnail' => $index == $thumbnailIndex
                ]);
            }

            // Tìm các ảnh cũ bị xóa bỏ khỏi danh sách mới để xóa trên Cloudinary
            $removedUrls = array_diff($oldImageUrls, $imageUrls);
            foreach ($removedUrls as $url) {
                CloudinaryService::delete($url);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin xe thành công! Xe của bạn đang chờ kiểm duyệt lại.',
                'data' => $car
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin xe.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Thay đổi trạng thái hoạt động của xe (Kích hoạt / Tạm dừng)
     * PATCH /api/cars/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $user = auth('api')->user();

        $car = Car::find($id);
        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin xe'
            ], 404);
        }

        // Kiểm tra quyền sở hữu
        if ($car->user_id !== $user->id && $user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thay đổi trạng thái xe này.'
            ], 403);
        }

        // Kiểm tra chuyến đi đang diễn ra
        if ($car->has_ongoing_trip) {
            return response()->json([
                'success' => false,
                'message' => 'Xe đang có chuyến đi đang diễn ra, không thể thay đổi trạng thái.'
            ], 400);
        }

        // Chỉ cho phép toggle giữa 0 (Dừng hoạt động) và 1 (Đang hoạt động)
        if (!in_array((int)$car->status, [0, 1])) {
            return response()->json([
                'success' => false,
                'message' => 'Xe chưa được phê duyệt hoặc đang chờ duyệt, không thể tự thay đổi trạng thái.'
            ], 400);
        }

        $newStatus = $car->status == 1 ? 0 : 1;
        
        // Cập nhật trạng thái mới
        $car->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => $newStatus == 1 ? 'Kích hoạt hoạt động xe thành công!' : 'Tạm dừng hoạt động xe thành công!',
            'data' => $car
        ], 200);
    }
}
