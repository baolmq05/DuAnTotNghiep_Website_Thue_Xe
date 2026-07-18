<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Car;
use App\Models\PendingBalance;
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

        // Kiểm tra số điện thoại
        if (empty($user->phone)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần cập nhật số điện thoại trước khi thực hiện thuê xe. Vui lòng cập nhật tại trang Cá nhân.'
            ], 400);
        }

        // Kiểm tra giấy phép lái xe
        if (!$user->drivingLicense) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chưa cập nhật thông tin giấy phép lái xe. Vui lòng cập nhật thông tin tại trang Cá nhân.'
            ], 400);
        }

        if ($user->drivingLicense->status === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Giấy phép lái xe của bạn đang chờ duyệt. Vui lòng đợi quản trị viên phê duyệt để thuê xe.'
            ], 400);
        }

        if ($user->drivingLicense->status === 2) {
            return response()->json([
                'success' => false,
                'message' => 'Giấy phép lái xe của bạn đã bị từ chối. Vui lòng cập nhật lại thông tin tại trang Cá nhân.'
            ], 400);
        }

        if ($user->drivingLicense->status !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Giấy phép lái xe không hợp lệ. Vui lòng kiểm tra lại.'
            ], 400);
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
            'promo_code' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric|min:0',
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

        // Tính toán các thông số thuê xe
        $start = \Carbon\Carbon::parse($request->start_at);
        $end = \Carbon\Carbon::parse($request->end_at);
        $diffMinutes = $start->diffInMinutes($end);
        $days = max(1, ceil($diffMinutes / 1440));

        $unitPrice = $car->unit_price;
        $insuranceFee = 0;
        $discountVal = $car->discount_value ?? 0;
        $carDiscountTotal = $discountVal * $days;

        $baseRentalPrice = ($unitPrice + $insuranceFee) * $days;
        $deliveryFee = $request->input('delivery_fee', 0);
        $totalPriceBeforePromo = $baseRentalPrice + $deliveryFee;

        $promoDiscount = 0;
        $promotion = null;

        if ($request->filled('promo_code')) {
            $promotion = \App\Models\Promotion::where('code', $request->promo_code)->first();

            if (!$promotion || $promotion->status != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không hợp lệ.'
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

            if ($promotion->discount_type == 0) { // percentage
                $promoDiscount = round(($baseRentalPrice * $promotion->discount_value) / 100);
            } else { // fixed amount
                $promoDiscount = $promotion->discount_value;
            }

            $promoDiscount = min($promoDiscount, $baseRentalPrice);
        }

        $calculatedCost = max(0, $totalPriceBeforePromo - $promoDiscount);
        $calculatedDiscountAmount = $carDiscountTotal + $promoDiscount;

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Tạo chuyến đi với trạng thái Pending (Chờ duyệt)
            $trip = Trip::create([
                'cost' => $calculatedCost,
                'discount_amount' => $calculatedDiscountAmount,
                'status' => TripStatus::Pending->value,
                'trip_type' => $request->trip_type,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
                'car_id' => $request->car_id,
                'user_id' => $user->id,
                'delivery_address' => $request->delivery_address,
                'delivery_location' => $request->delivery_location,
            ]);

            if ($promotion) {
                \App\Models\PromotionUsage::create([
                    'user_id' => $user->id,
                    'promotion_id' => $promotion->id,
                    'discount_amount' => $promoDiscount,
                    'used_at' => now(),
                    'trip_id' => $trip->id
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gửi yêu cầu thuê xe thành công!',
                'data' => $trip
            ], 201);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thực hiện thuê xe: ' . $e->getMessage()
            ], 500);
        }
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

        // Tự động quét và cập nhật các chuyến đi hết giờ
        $this->autoUpdateExpiredTrips();

        // Chuyến đã đặt (Renter)
        $bookedTrips = Trip::where('user_id', $user->id)
            ->with(['car.carLocation', 'car.images', 'car.carBrand', 'car.carType', 'reviews.reviewer', 'extensions', 'latestExtension'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Xe cho thuê của tôi (Owner)
        $ownerTrips = Trip::whereHas('car', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['car.carLocation', 'car.images', 'car.carBrand', 'car.carType', 'user', 'reviews.reviewer', 'extensions', 'latestExtension'])
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

    /**
     * API Duyệt yêu cầu thuê xe
     * PUT /api/trips/{id}/confirm
     */
    public function confirm($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        // Kiểm tra quyền chủ xe
        if ($trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền phê duyệt yêu cầu này.'
            ], 403);
        }

        if ($trip->status !== TripStatus::Pending->value) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu này đã được xử lý.'
            ], 400);
        }

        // Cập nhật trạng thái thành WaitingPayment (Chờ thanh toán)
        $trip->update(['status' => TripStatus::WaitingPayment->value]);

        // Tạo thông báo cho khách thuê
        \App\Models\Notification::create([
            'user_id' => $trip->user_id,
            'message' => "Yêu cầu thuê xe '{$trip->car->name}' của bạn đã được chủ xe xác nhận. Vui lòng tiến hành thanh toán.",
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Xác nhận cho thuê xe thành công!',
            'data' => $trip
        ]);
    }

    /**
     * API Từ chối yêu cầu thuê xe
     * PUT /api/trips/{id}/reject
     */
    public function reject(Request $request, $id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        // Kiểm tra quyền chủ xe
        if ($trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền từ chối yêu cầu này.'
            ], 403);
        }

        if ($trip->status !== TripStatus::Pending->value) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu này đã được xử lý.'
            ], 400);
        }

        $reason = $request->input('reason', 'Chủ xe bận lịch đột xuất');

        // Cập nhật trạng thái thành OwnerCancel (Chủ xe hủy)
        $trip->update(['status' => TripStatus::OwnerCancel->value]);

        // Tạo thông báo cho khách thuê
        \App\Models\Notification::create([
            'user_id' => $trip->user_id,
            'message' => "Yêu cầu thuê xe '{$trip->car->name}' của bạn đã bị từ chối. Lý do: {$reason}.",
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã từ chối yêu cầu thuê xe thành công!',
            'data' => $trip
        ]);
    }

    /**
     * API Lấy chi tiết chuyến đi
     * GET /api/trips/{id}
     */
    public function show($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        // Tự động quét và cập nhật các chuyến đi hết giờ
        $this->autoUpdateExpiredTrips();

        $trip = Trip::with([
            'car.carLocation',
            'car.images',
            'car.carBrand',
            'car.carType',
            'car.owner',
            'user',
            'images',
            'transactions',
            'reviews.reviewer',
            'extensions',
            'latestExtension'
        ])->find($id);

        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy chuyến đi.'
            ], 404);
        }

        // Kiểm tra quyền: phải là renter (user_id) hoặc owner của xe (car->user_id)
        if ($trip->user_id !== $user->id && $trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xem thông tin chuyến đi này.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $trip
        ]);
    }

    /**
     * API Bắt đầu chuyến đi (upload ảnh trước chuyến đi & đổi trạng thái sang 3 - Ongoing)
     * POST /api/trips/{id}/start
     */
    public function startTrip(Request $request, $id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        // Kiểm tra quyền: renter hoặc owner
        if ($trip->user_id !== $user->id && $trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        if ($trip->status !== TripStatus::Confirmed->value) {
            return response()->json([
                'success' => false,
                'message' => 'Chuyến đi không ở trạng thái Đang xác nhận để bắt đầu.'
            ], 400);
        }

        // Xác thực ảnh tải lên từ cloud
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|min:1',
            'images.*' => 'required|string|url',
        ], [
            'images.required' => 'Bạn phải tải lên ít nhất 1 ảnh xe trước khi bắt đầu chuyến đi.',
            'images.min' => 'Bạn phải tải lên ít nhất 1 ảnh xe trước khi bắt đầu chuyến đi.',
            'images.*.url' => 'Đường dẫn hình ảnh không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $imageUrls = $request->input('images');
            foreach ($imageUrls as $index => $url) {
                // Lưu vào bảng trip_images
                \App\Models\TripImage::create([
                    'trip_id' => $trip->id,
                    'image_url' => $url,
                    'type' => 0, // Trước chuyến đi
                    'is_thumbnail' => $index === 0 ? 1 : 0,
                ]);
            }

            // Cập nhật trạng thái chuyến đi thành Ongoing (3)
            $trip->update(['status' => TripStatus::Ongoing->value]);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bắt đầu chuyến đi thành công!',
                'data' => $trip->load(['car', 'images'])
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi bắt đầu chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Gửi yêu cầu gia hạn chuyến đi
     * POST /api/trips/{id}/extension-request
     */
    public function requestExtension(Request $request, $id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        // Kiểm tra quyền: chỉ renter (người thuê) mới được yêu cầu gia hạn
        if ($trip->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        // Chuyến đi phải đang diễn ra (status = 3 - Ongoing)
        if ($trip->status !== TripStatus::Ongoing->value) {
            return response()->json([
                'success' => false,
                'message' => 'Chuyến đi không ở trạng thái hợp lệ để yêu cầu gia hạn.'
            ], 400);
        }

        // Nhận vào end_date từ lịch hoặc extended_days
        $extendedEndAt = null;
        if ($request->filled('end_date')) {
            $extendedEndAt = \Carbon\Carbon::parse($request->input('end_date'));
        } elseif ($request->filled('extended_days')) {
            $extendedDays = (int)$request->input('extended_days');
            if ($extendedDays <= 0) {
                return response()->json(['success' => false, 'message' => 'Số ngày gia hạn không hợp lệ'], 422);
            }
            $extendedEndAt = \Carbon\Carbon::parse($trip->end_at)->addDays($extendedDays);
        } else {
            return response()->json(['success' => false, 'message' => 'Vui lòng cung cấp thời gian gia hạn mới (end_date)'], 422);
        }

        if ($extendedEndAt->lte(\Carbon\Carbon::parse($trip->end_at))) {
            return response()->json([
                'success' => false,
                'message' => 'Thời gian gia hạn mới phải sau thời gian kết thúc hiện tại.'
            ], 400);
        }

        // Kiểm tra xem xe có bị trùng lịch bận nào trong khoảng thời gian gia hạn [end_at, extendedEndAt] hay không
        $overlap = Trip::where('car_id', $trip->car_id)
            ->where('id', '!=', $trip->id)
            ->whereIn('status', [
                TripStatus::Pending->value,
                TripStatus::WaitingPayment->value,
                TripStatus::Confirmed->value,
                TripStatus::Ongoing->value
            ])
            ->where(function ($query) use ($trip, $extendedEndAt) {
                $query->where('start_at', '<', $extendedEndAt)
                      ->where('end_at', '>', $trip->end_at);
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'success' => false,
                'message' => 'Xe đã có lịch bận khác trong thời gian đề xuất gia hạn. Không thể gia hạn!'
            ], 400);
        }

        // Tính toán số tiền gia hạn (extension_amount)
        if ($request->filled('extension_amount') && (float)$request->input('extension_amount') >= 0) {
            $extensionAmount = (float)$request->input('extension_amount');
        } else {
            // Tính số ngày làm tròn lên
            $diffMinutes = \Carbon\Carbon::parse($trip->end_at)->diffInMinutes($extendedEndAt);
            $diffDays = max(1, ceil($diffMinutes / 1440));
            $extensionAmount = $diffDays * ($trip->car->unit_price ?? 0);
        }

        // Tạo hoặc cập nhật bản ghi trong bảng trip_extensions với status = 1 (Đã gửi yêu cầu gia hạn)
        $extension = \App\Models\TripExtension::create([
            'trip_id' => $trip->id,
            'extension_amount' => $extensionAmount,
            'status' => 1,
            'start_date' => $trip->end_at,
            'end_date' => $extendedEndAt,
        ]);

        // Yêu cầu gia hạn được lưu thông tin trong bảng trip_extensions (không sửa status của trip)

        // Tạo thông báo cho chủ xe
        \App\Models\Notification::create([
            'user_id' => $trip->car->user_id,
            'message' => "Khách hàng {$user->name} đã gửi yêu cầu gia hạn cho chuyến đi #{$trip->id} đến " . $extendedEndAt->format('H:i d/m/Y') . " (Phí gia hạn: " . number_format($extensionAmount) . " VNĐ).",
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gửi yêu cầu gia hạn thành công, đang chờ chủ xe duyệt!',
            'data' => $trip->load(['car', 'images', 'extensions', 'latestExtension'])
        ]);
    }

    /**
     * API Duyệt yêu cầu gia hạn chuyến đi (Owner đồng ý -> chuyển status gia hạn sang 2 - Chờ thanh toán)
     * PUT /api/trips/{id}/extension-approve
     */
    public function approveExtension($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        // Kiểm tra quyền chủ xe
        if ($trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền phê duyệt yêu cầu này.'
            ], 403);
        }

        $extension = $trip->extensions()->where('status', 1)->latest()->first();
        if (!$extension) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu gia hạn đang chờ duyệt.'
            ], 400);
        }

        $extendedEndAt = $extension->end_date;

        // Kiểm tra trùng lịch lần cuối trước khi phê duyệt
        $overlap = Trip::where('car_id', $trip->car_id)
            ->where('id', '!=', $trip->id)
            ->whereIn('status', [
                TripStatus::Pending->value,
                TripStatus::WaitingPayment->value,
                TripStatus::Confirmed->value,
                TripStatus::Ongoing->value
            ])
            ->where(function ($query) use ($trip, $extendedEndAt) {
                $query->where('start_at', '<', $extendedEndAt)
                      ->where('end_at', '>', $trip->end_at);
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'success' => false,
                'message' => 'Xe đã có lịch bận khác trong khoảng thời gian gia hạn này.'
            ], 400);
        }

        // Cập nhật trạng thái gia hạn thành 2 - Chờ thanh toán gia hạn
        $extension->update(['status' => 2]);

        // Tạo thông báo cho khách thuê
        \App\Models\Notification::create([
            'user_id' => $trip->user_id,
            'message' => "Yêu cầu gia hạn chuyến đi #{$trip->id} (xe {$trip->car->name}) đã được chủ xe chấp nhận. Vui lòng thanh toán phí gia hạn (" . number_format($extension->extension_amount) . " VNĐ) để hoàn tất.",
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã duyệt yêu cầu gia hạn, chờ khách hàng thanh toán!',
            'data' => $trip->load(['car', 'images', 'extensions', 'latestExtension'])
        ]);
    }

    /**
     * API Khách hàng thanh toán phí gia hạn (chuyển status gia hạn sang 3 - Đã gia hạn & cập nhật end_at chuyến đi)
     * POST /api/trips/{id}/extension-pay
     */
    public function payExtension(Request $request, $id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        if ($trip->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        $extension = $trip->extensions()->where('status', 2)->latest()->first();
        if (!$extension) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu gia hạn đang chờ thanh toán.'
            ], 404);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $amount = (float)$extension->extension_amount;

            // Kiểm tra ví user và thực hiện thanh toán nếu có
            if ($user->wallet_id && $amount > 0) {
                $wallet = $user->wallet;
                if ($wallet && $wallet->amount >= $amount) {
                    $wallet->decrement('amount', $amount);
                }
            }

            // Ghi nhận giao dịch thanh toán gia hạn
            if ($amount > 0) {
                $transaction = \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'trip_id' => $trip->id,
                    'amount' => $amount,
                    'prepay' => 0,
                    'transaction_code' => 'EXT_' . time() . '_' . $trip->id,
                ]);

                // Tạo bản ghi pending_balances để giữ tiền
                PendingBalance::create([
                    'transaction_id' => $transaction->id,
                    'trip_id' => $trip->id,
                    'payer_id' => $user->id,
                    'receiver_id' => $trip->car->user_id,
                    'amount' => $amount,
                    'status' => '1',
                    'expired_at' => \Carbon\Carbon::parse($extension->end_date)->addDays(3),
                    'released_at' => null
                ]);
            }

            // Cập nhật trạng thái gia hạn thành 3 - Đã gia hạn
            $extension->update(['status' => 3]);

            // Cập nhật thời gian trả xe mới cho trip và cộng tiền gia hạn vào cost của trip
            $trip->update([
                'end_at' => $extension->end_date,
                'cost' => $trip->cost + $amount,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            // Tạo thông báo
            \App\Models\Notification::create([
                'user_id' => $trip->car->user_id,
                'message' => "Khách hàng {$user->name} đã thanh toán thành công phí gia hạn chuyến đi #{$trip->id}. Thời gian trả xe mới là " . date('H:i d/m/Y', strtotime($extension->end_date)) . ".",
                'is_read' => '0',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thanh toán phí gia hạn thành công! Chuyến đi đã được cập nhật thời gian mới.',
                'data' => $trip->load(['car', 'images', 'extensions', 'latestExtension'])
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thanh toán phí gia hạn: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Từ chối yêu cầu gia hạn chuyến đi (Owner hoặc Renter hủy -> status gia hạn sang 4 - Bị từ chối)
     * PUT /api/trips/{id}/extension-reject
     */
    public function rejectExtension(Request $request, $id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        // Kiểm tra quyền: chủ xe hoặc khách thuê đều có thể hủy yêu cầu gia hạn
        if ($trip->car->user_id !== $user->id && $trip->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        $extension = $trip->extensions()->whereIn('status', [1, 2])->latest()->first();
        if (!$extension) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu gia hạn đang chờ xử lý.'
            ], 400);
        }

        $reason = $request->input('reason', 'Yêu cầu gia hạn bị từ chối/hủy');
        $extension->update(['status' => 4]); // 4: Bị từ chối gia hạn

        // Yêu cầu gia hạn bị từ chối được lưu trong trip_extensions (không sửa status của trip)

        // Tạo thông báo
        $notifyUserId = ($trip->car->user_id === $user->id) ? $trip->user_id : $trip->car->user_id;
        \App\Models\Notification::create([
            'user_id' => $notifyUserId,
            'message' => "Yêu cầu gia hạn cho chuyến đi #{$trip->id} (xe {$trip->car->name}) đã bị từ chối/hủy. Lý do: {$reason}.",
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã từ chối/hủy yêu cầu gia hạn chuyến đi!',
            'data' => $trip->load(['car', 'images', 'extensions', 'latestExtension'])
        ]);
    }

    private function autoUpdateExpiredTrips()
    {
        Trip::where('status', TripStatus::Ongoing->value)
            ->where('end_at', '<', now('Asia/Ho_Chi_Minh')->toDateTimeString())
            ->update(['status' => TripStatus::WaitingReturn->value]);
    }

    /**
     * API Khách thuê bấm trả xe (Trả xe sớm)
     * POST /api/trips/{id}/return-request
     */
    public function requestReturn($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        if ($trip->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        if ($trip->status !== TripStatus::Ongoing->value) {
            return response()->json([
                'success' => false,
                'message' => 'Chuyến đi không ở trạng thái Đang diễn ra.'
            ], 400);
        }

        $trip->update(['status' => TripStatus::WaitingReturn->value]);

        \App\Models\Notification::create([
            'user_id' => $trip->car->user_id,
            'message' => "Khách hàng {$user->name} đã yêu cầu trả xe sớm cho chuyến đi #{$trip->id} (xe {$trip->car->name}). Vui lòng xác nhận hoàn thành chuyến xe.",
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gửi yêu cầu trả xe thành công!',
            'data' => $trip
        ]);
    }

    /**
     * API Chủ xe xác nhận hoàn thành chuyến xe (upload ảnh sau chuyến đi & đổi trạng thái sang 4 - Complete)
     * POST /api/trips/{id}/complete
     */
    public function completeTrip(Request $request, $id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        if ($trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        if ($trip->status !== TripStatus::WaitingReturn->value) {
            return response()->json([
                'success' => false,
                'message' => 'Chuyến đi không ở trạng thái Chờ trả xe để hoàn thành.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'images' => 'required|array|min:1',
            'images.*' => 'required|string|url',
        ], [
            'images.required' => 'Bạn phải tải lên ít nhất 1 ảnh xe sau chuyến đi để hoàn thành.',
            'images.min' => 'Bạn phải tải lên ít nhất 1 ảnh xe sau chuyến đi để hoàn thành.',
            'images.*.url' => 'Đường dẫn hình ảnh không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $imageUrls = $request->input('images');
            foreach ($imageUrls as $url) {
                \App\Models\TripImage::create([
                    'trip_id' => $trip->id,
                    'image_url' => $url,
                    'type' => 1,
                    'is_thumbnail' => 0,
                ]);
            }

            $trip->update(['status' => TripStatus::Complete->value]);

            // Giải ngân tiền từ pending_balances sang ví chủ xe
            $trip->releasePendingBalances();

            \App\Models\Notification::create([
                'user_id' => $trip->user_id,
                'message' => "Chuyến đi #{$trip->id} (xe {$trip->car->name}) của bạn đã được chủ xe xác nhận hoàn thành.",
                'is_read' => '0',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hoàn thành chuyến đi thành công!',
                'data' => $trip->load(['car', 'images'])
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi hoàn thành chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Gửi đánh giá cho chuyến đi
     * POST /api/trips/{id}/reviews
     */
    public function storeReview(Request $request, $id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with('car')->find($id);
        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        // Kiểm tra quyền: renter hoặc owner
        if ($trip->user_id !== $user->id && $trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        // Chỉ cho phép đánh giá khi chuyến đi đã hoàn thành (status = 4)
        if ($trip->status !== TripStatus::Complete->value) {
            return response()->json([
                'success' => false,
                'message' => 'Chuyến đi phải hoàn thành mới có thể đánh giá.'
            ], 400);
        }

        // Xác thực
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Bạn chưa chọn số sao đánh giá.',
            'rating.integer' => 'Số sao đánh giá phải là số nguyên.',
            'rating.min' => 'Số sao đánh giá tối thiểu là 1 sao.',
            'rating.max' => 'Số sao đánh giá tối đa là 5 sao.',
            'comment.max' => 'Bình luận không được vượt quá 1000 ký tự.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Xác định review_type và target_id
        if ($trip->user_id === $user->id) {
            // Renter đánh giá Owner
            $reviewType = 1;
            $targetId = $trip->car->user_id;
        } else {
            // Owner đánh giá Renter
            $reviewType = 0;
            $targetId = $trip->user_id;
        }

        // Kiểm tra xem đã đánh giá chưa (tránh trùng lặp)
        $exists = \App\Models\Review::where('trip_id', $trip->id)
            ->where('reviewer_id', $user->id)
            ->where('review_type', $reviewType)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã gửi đánh giá cho chuyến đi này rồi.'
            ], 400);
        }

        try {
            $review = \App\Models\Review::create([
                'trip_id' => $trip->id,
                'reviewer_id' => $user->id,
                'target_id' => $targetId,
                'car_id' => $trip->car_id,
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'review_type' => $reviewType
            ]);

            // Tạo thông báo cho người được đánh giá
            \App\Models\Notification::create([
                'user_id' => $targetId,
                'message' => "Bạn đã nhận được đánh giá {$request->input('rating')} sao cho chuyến đi #{$trip->id}.",
                'is_read' => '0',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gửi đánh giá thành công!',
                'data' => $review->load('reviewer')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi gửi đánh giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Hủy chuyến đi (Dành cho Renter)
     * POST /api/trips/{id}/cancel
     */
    public function cancelTrip($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with(['car.owner', 'user'])->findOrFail($id);

        // Chỉ khách thuê mới được quyền hủy chuyến đi này
        if ($trip->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền hủy chuyến đi này.'
            ], 403);
        }

        // Chỉ được hủy khi trạng thái là 0 (Pending), 1 (WaitingPayment), 2 (Confirmed)
        if (!in_array($trip->status, [0, 1, 2])) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chỉ được phép hủy chuyến đi ở trạng thái Chờ duyệt, Chờ thanh toán, hoặc Đã xác nhận.'
            ], 400);
        }

        // Tính phí hủy chuyến và hoàn tiền theo chính sách
        // 1. Tổng tiền thực tế của chuyến đi (giá trị chuyến đi)
        $tripValue = $trip->cost - $trip->discount_amount;
        $bookingTime = $trip->created_at->timezone('Asia/Ho_Chi_Minh');
        $startTime = \Carbon\Carbon::parse($trip->start_at, 'Asia/Ho_Chi_Minh');
        $now = now()->timezone('Asia/Ho_Chi_Minh');

        // 2. Xác định phần trăm phí hủy chuyến
        // - Trong vòng 1h sau khi đặt xe (booking time): Miễn phí
        if ($bookingTime->diffInMinutes($now) <= 60) {
            $feePercent = 0;
        } else {
            // - Trước chuyến đi 7 ngày (Sau 1h khi đặt): 10% giá trị chuyến đi
            // - Trong vòng 7 ngày trước chuyến đi (Sau 1h khi đặt): 40% giá trị chuyến đi
            if ($now->copy()->addDays(7)->lte($startTime)) {
                $feePercent = 0.10; // 10%
            } else {
                $feePercent = 0.40; // 40%
            }
        }

        $cancellationFee = $tripValue * $feePercent;

        // 3. Số tiền đang bị tạm giữ (đã thanh toán cọc/toàn bộ)
        $totalPaid = $trip->pendingBalances()->where('status', '1')->sum('amount');

        // 4. Tính toán tiền hoàn cho renter và đền bù cho owner
        $refundAmount = max(0, $totalPaid - $cancellationFee);
        $compensationFee = min($totalPaid, $cancellationFee);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Hoàn tiền cho Khách thuê
            if ($refundAmount > 0) {
                $renter = $trip->user;
                if (!$renter->wallet_id) {
                    $wallet = \App\Models\Wallet::create(['amount' => 0]);
                    $renter->wallet_id = $wallet->id;
                    $renter->save();
                } else {
                    $wallet = $renter->wallet;
                }
                $wallet->increment('amount', $refundAmount);
            }

            // Đền bù cho Chủ xe
            if ($compensationFee > 0) {
                $owner = $trip->car->owner;
                if (!$owner->wallet_id) {
                    $wallet = \App\Models\Wallet::create(['amount' => 0]);
                    $owner->wallet_id = $wallet->id;
                    $owner->save();
                } else {
                    $wallet = $owner->wallet;
                }
                $wallet->increment('amount', $compensationFee);
            }

            // Cập nhật trạng thái pending_balances thành cancelled ('3')
            $trip->pendingBalances()->where('status', '1')->update([
                'status' => '3', // cancelled
            ]);

            // Cập nhật trạng thái chuyến đi thành UserCancel (5)
            $trip->update([
                'status' => \App\Enum\TripStatus::UserCancel->value
            ]);

            // Tạo thông báo cho chủ xe biết chuyến đi đã bị hủy
            \App\Models\Notification::create([
                'user_id' => $trip->car->user_id,
                'message' => "Khách hàng đã hủy chuyến đi #{$trip->id}. Phí đền bù nhận được: " . number_format($compensationFee, 0, ',', '.') . " đ.",
                'is_read' => '0',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hủy chuyến đi thành công.',
                'data' => [
                    'trip_id' => $trip->id,
                    'trip_value' => $tripValue,
                    'cancellation_fee' => $cancellationFee,
                    'paid_amount' => $totalPaid,
                    'refund_amount' => $refundAmount,
                    'compensation_fee' => $compensationFee,
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi hủy chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Hủy chuyến đi (Dành cho Owner)
     * POST /api/trips/{id}/owner-cancel
     */
    public function cancelTripByOwner($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $trip = Trip::with(['car.owner', 'user'])->findOrFail($id);

        // Chỉ chủ xe mới được quyền hủy chuyến đi này
        if ($trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền hủy chuyến đi này.'
            ], 403);
        }

        // Chỉ được hủy khi trạng thái là 0 (Pending), 1 (WaitingPayment), 2 (Confirmed)
        if (!in_array($trip->status, [0, 1, 2])) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn chỉ được phép hủy chuyến đi ở trạng thái Chờ duyệt, Chờ thanh toán, hoặc Đã xác nhận.'
            ], 400);
        }

        // Số tiền đang bị tạm giữ (đã thanh toán cọc/toàn bộ)
        $totalPaid = $trip->pendingBalances()->where('status', '1')->sum('amount');

        // Đối với chủ xe hủy: Hoàn lại 100% tiền đã đóng cho Renter, không tính phí hủy
        $refundAmount = $totalPaid;

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Hoàn tiền cho Khách thuê
            if ($refundAmount > 0) {
                $renter = $trip->user;
                if (!$renter->wallet_id) {
                    $wallet = \App\Models\Wallet::create(['amount' => 0]);
                    $renter->wallet_id = $wallet->id;
                    $renter->save();
                } else {
                    $wallet = $renter->wallet;
                }
                $wallet->increment('amount', $refundAmount);
            }

            // Cập nhật trạng thái pending_balances thành cancelled ('3')
            $trip->pendingBalances()->where('status', '1')->update([
                'status' => '3', // cancelled
            ]);

            // Cập nhật trạng thái chuyến đi thành OwnerCancel (6)
            $trip->update([
                'status' => \App\Enum\TripStatus::OwnerCancel->value
            ]);

            // Tạo thông báo cho khách thuê biết chủ xe đã hủy chuyến
            \App\Models\Notification::create([
                'user_id' => $trip->user_id,
                'message' => "Chủ xe đã hủy chuyến đi #{$trip->id}. Số tiền " . number_format($refundAmount, 0, ',', '.') . " đ đã được hoàn lại vào ví của bạn.",
                'is_read' => '0',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Chủ xe hủy chuyến đi thành công.',
                'data' => [
                    'trip_id' => $trip->id,
                    'paid_amount' => $totalPaid,
                    'refund_amount' => $refundAmount,
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi hủy chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
