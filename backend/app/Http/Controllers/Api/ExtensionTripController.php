<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripExtension;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\PendingBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use App\Http\Requests\Trip\RequestExtensionRequest;

class ExtensionTripController extends Controller
{
    /**
     * API Gửi yêu cầu gia hạn chuyến đi
     * POST /api/trips/{id}/extension-request
     */
    public function requestExtension(RequestExtensionRequest $request, $id)
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
            $extendedEndAt = Carbon::parse($request->input('end_date'));
        } elseif ($request->filled('extended_days')) {
            $extendedDays = (int)$request->input('extended_days');
            if ($extendedDays <= 0) {
                return response()->json(['success' => false, 'message' => 'Số ngày gia hạn không hợp lệ'], 422);
            }
            $extendedEndAt = Carbon::parse($trip->end_at)->addDays($extendedDays);
        } else {
            return response()->json(['success' => false, 'message' => 'Vui lòng cung cấp thời gian gia hạn mới (end_date)'], 422);
        }

        if ($extendedEndAt->lte(Carbon::parse($trip->end_at))) {
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
            $diffMinutes = Carbon::parse($trip->end_at)->diffInMinutes($extendedEndAt);
            $diffDays = max(1, ceil($diffMinutes / 1440));
            $extensionAmount = $diffDays * ($trip->car->unit_price ?? 0);
        }

        // Tạo hoặc cập nhật bản ghi trong bảng trip_extensions với status = 1 (Đã gửi yêu cầu gia hạn)
        $extension = TripExtension::create([
            'trip_id' => $trip->id,
            'extension_amount' => $extensionAmount,
            'status' => 1,
            'start_date' => $trip->end_at,
            'end_date' => $extendedEndAt,
        ]);

        // Yêu cầu gia hạn được lưu thông tin trong bảng trip_extensions (không sửa status của trip)

        // Tạo thông báo cho chủ xe
        Notification::create([
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
        Notification::create([
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
            DB::beginTransaction();

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
                $transaction = Transaction::create([
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
                    'expired_at' => Carbon::parse($extension->end_date)->addDays(3),
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

            DB::commit();

            // Tạo thông báo cho chủ xe và khách thuê
            Notification::create([
                'user_id' => $trip->car->user_id,
                'message' => "Khách hàng {$user->name} đã thanh toán thành công phí gia hạn chuyến đi #{$trip->id}. Thời gian trả xe mới là " . date('H:i d/m/Y', strtotime($extension->end_date)) . ".",
                'is_read' => '0',
            ]);

            Notification::create([
                'user_id' => $user->id,
                'message' => "Bạn đã thanh toán thành công phí gia hạn chuyến đi #{$trip->id}. Thời gian trả xe mới là " . date('H:i d/m/Y', strtotime($extension->end_date)) . ".",
                'is_read' => '0',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thanh toán phí gia hạn thành công! Chuyến đi đã được cập nhật thời gian mới.',
                'data' => $trip->load(['car', 'images', 'extensions', 'latestExtension'])
            ]);
        } catch (Exception $e) {
            DB::rollBack();
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
        Notification::create([
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
}
