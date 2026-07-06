<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
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

        // Tạo chuyến đi với trạng thái Pending (Chờ duyệt)
        $trip = Trip::create([
            'cost' => $request->cost,
            'discount_amount' => $request->discount_amount ?? 0,
            'status' => TripStatus::Pending->value,
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

        $trip = Trip::with([
            'car.carLocation',
            'car.images',
            'car.carBrand',
            'car.carType',
            'car.owner',
            'user',
            'images',
            'transactions'
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
                'message' => 'Chuyến đi không ở trạng thái Đã xác nhận để bắt đầu.'
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
                'message' => 'Chuyến đi không ở trạng thái Đang diễn ra để yêu cầu gia hạn.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'extended_days' => 'required|integer|min:1',
        ], [
            'extended_days.required' => 'Vui lòng cung cấp số ngày cần gia hạn.',
            'extended_days.integer' => 'Số ngày gia hạn phải là số nguyên.',
            'extended_days.min' => 'Số ngày gia hạn tối thiểu là 1 ngày.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $extendedDays = (int)$request->input('extended_days');
        
        // Tính toán ngày trả xe mới
        $extendedEndAt = \Carbon\Carbon::parse($trip->end_at)->addDays($extendedDays);

        // Kiểm tra xem xe có bị trùng lịch bận nào trong khoảng thời gian gia hạn [end_at, extendedEndAt] hay không
        $overlap = Trip::where('car_id', $trip->car_id)
            ->where('id', '!=', $trip->id)
            ->whereIn('status', [
                TripStatus::Pending->value,
                TripStatus::WaitingPayment->value,
                TripStatus::Confirmed->value,
                TripStatus::Ongoing->value,
                TripStatus::WaitingExtension->value
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

        // Cập nhật trạng thái thành Chờ gia hạn (7) và lưu ngày đề xuất
        $trip->update([
            'status' => TripStatus::WaitingExtension->value,
            'extended_end_at' => $extendedEndAt,
        ]);

        // Tạo thông báo cho chủ xe
        \App\Models\Notification::create([
            'user_id' => $trip->car->user_id,
            'message' => "Khách hàng {$user->name} đã gửi yêu cầu gia hạn thêm {$extendedDays} ngày (đến " . $extendedEndAt->format('H:i d/m/Y') . ") cho chuyến đi #{$trip->id} (xe {$trip->car->name}).",
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gửi yêu cầu gia hạn thành công, đang chờ chủ xe duyệt!',
            'data' => $trip
        ]);
    }

    /**
     * API Duyệt yêu cầu gia hạn chuyến đi
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

        // Chuyến đi phải ở trạng thái Chờ gia hạn (7)
        if ($trip->status !== TripStatus::WaitingExtension->value) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không ở trạng thái Chờ gia hạn.'
            ], 400);
        }

        if (empty($trip->extended_end_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có dữ liệu thời gian gia hạn đề xuất.'
            ], 400);
        }

        $extendedEndAt = $trip->extended_end_at;

        // Kiểm tra trùng lịch lần cuối trước khi cập nhật
        $overlap = Trip::where('car_id', $trip->car_id)
            ->where('id', '!=', $trip->id)
            ->whereIn('status', [
                TripStatus::Pending->value,
                TripStatus::WaitingPayment->value,
                TripStatus::Confirmed->value,
                TripStatus::Ongoing->value,
                TripStatus::WaitingExtension->value
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

        // Cập nhật ngày kết thúc mới và trả trạng thái về Đang diễn ra (3)
        $trip->update([
            'end_at' => $extendedEndAt,
            'extended_end_at' => null,
            'status' => TripStatus::Ongoing->value
        ]);

        // Tạo thông báo cho khách thuê
        \App\Models\Notification::create([
            'user_id' => $trip->user_id,
            'message' => "Yêu cầu gia hạn cho chuyến đi #{$trip->id} (xe {$trip->car->name}) của bạn đã được chủ xe chấp nhận. Thời hạn trả xe mới là " . date('H:i d/m/Y', strtotime($extendedEndAt)) . ".",
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã duyệt yêu cầu gia hạn thành công!',
            'data' => $trip
        ]);
    }

    /**
     * API Từ chối yêu cầu gia hạn chuyến đi
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

        // Kiểm tra quyền chủ xe
        if ($trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này.'
            ], 403);
        }

        // Chuyến đi phải ở trạng thái Chờ gia hạn (7)
        if ($trip->status !== TripStatus::WaitingExtension->value) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không ở trạng thái Chờ gia hạn.'
            ], 400);
        }

        $reason = $request->input('reason', 'Chủ xe từ chối gia hạn chuyến đi');

        // Hủy thông tin đề xuất gia hạn, trả trạng thái về Đang diễn ra (3)
        $trip->update([
            'extended_end_at' => null,
            'status' => TripStatus::Ongoing->value
        ]);

        // Tạo thông báo cho khách thuê
        \App\Models\Notification::create([
            'user_id' => $trip->user_id,
            'message' => "Yêu cầu gia hạn cho chuyến đi #{$trip->id} (xe {$trip->car->name}) của bạn đã bị từ chối. Lý do: {$reason}.",
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã từ chối yêu cầu gia hạn chuyến đi!',
            'data' => $trip
        ]);
    }
}

