<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $query = Car::query()->where('status', 1);

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

        // Lọc theo chủ sở hữu (user_id)
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Lọc theo trạng thái xe (status)
        if ($request->has('status')) {
            $query->where('status', $request->status);
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
            'license_plate' => 'required|string|max:12|unique:cars,license_plate',
            'car_brand_id' => 'required|exists:car_brands,id',
            'car_type_id' => 'required|exists:car_types,id',
            'seat_count' => 'required|integer|min:2',
            'manufacture_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'fuel_type' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'fuel_consumption' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'rental_terms' => 'nullable|string',
            
            // Location
            'street_name' => 'required|string',
            
            // Pricing & Discount
            'unit_price' => 'required|numeric|min:0',
            'discount_value' => 'nullable|numeric|min:0',
            
            // Delivery options
            'delivery_enabled' => 'required|in:0,1',
            'delivery_max_distance' => 'nullable|numeric|min:0',
            'delivery_fee' => 'nullable|numeric|min:0',
            
            // Usage limit
            'km_limit_enabled' => 'required|in:0,1',
            'km_limit_val' => 'nullable|numeric|min:0',
            'over_fee_val' => 'nullable|numeric|min:0',
            
            // Features
            'features' => 'nullable|string', // JSON array string or comma separated
            
            // Images
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'thumbnail_index' => 'required|integer|min:0',
        ], [
            'license_plate.required' => 'Biển số xe không được để trống.',
            'license_plate.unique' => 'Biển số xe này đã được đăng ký trên hệ thống.',
            'car_brand_id.required' => 'Hãng xe không được để trống.',
            'car_brand_id.exists' => 'Hãng xe không tồn tại.',
            'car_type_id.required' => 'Mẫu xe không được để trống.',
            'car_type_id.exists' => 'Mẫu xe không tồn tại.',
            'images.required' => 'Bạn cần tải lên ít nhất 1 hình ảnh xe.',
            'images.min' => 'Bạn cần tải lên ít nhất 1 hình ảnh xe.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // 1. Create Location
            $location = CarLocation::create([
                'province_id' => $request->input('province_id', 1),
                'ward_code' => $request->input('ward_code', 1),
                'street_name' => $request->input('street_name'),
            ]);

            // 2. Create Delivery Option
            $deliveryOption = CarDeliveryOption::create([
                'status' => $request->input('delivery_enabled') == '1' ? 1 : 0,
                'max_distance' => $request->input('delivery_enabled') == '1' ? floatval($request->input('delivery_max_distance', 0)) : 0,
                'fee_distance' => $request->input('delivery_enabled') == '1' ? floatval($request->input('delivery_fee', 0)) : 0,
            ]);

            // 3. Create Usage Limit
            $usageLimit = CarUsageLimit::create([
                'status' => $request->input('km_limit_enabled') == '1' ? 1 : 0,
                'max_daily_distance' => $request->input('km_limit_enabled') == '1' ? floatval($request->input('km_limit_val', 0)) : 0,
                'extra_distance_fee' => $request->input('km_limit_enabled') == '1' ? floatval($request->input('over_fee_val', 0)) : 0,
            ]);

            // Brand & Type name for automatic full name of car (e.g. Toyota Camry)
            $brand = CarBrand::find($request->car_brand_id);
            $type = CarType::find($request->car_type_id);
            $carName = ($brand ? $brand->brand_name : '') . ' ' . ($type ? $type->type_name : '');

            // 4. Create Car
            $car = Car::create([
                'name' => trim($carName),
                'license_plate' => $request->input('license_plate'),
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
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $thumbnailIndex = intval($request->input('thumbnail_index', 0));

                foreach ($images as $index => $imageFile) {
                    // Save file to storage
                    $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                    $path = $imageFile->storeAs('cars', $filename, 'public');
                    
                    // URL link
                    $imageUrl = asset('storage/' . $path);

                    CarImage::create([
                        'car_id' => $car->id,
                        'image_url' => $imageUrl,
                        'is_thumbnail' => ($index === $thumbnailIndex) ? 1 : 0
                    ]);
                }
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
}
