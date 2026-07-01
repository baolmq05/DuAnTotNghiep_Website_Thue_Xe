<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;

class MyTripController extends Controller
{
    public function index(Request $request)
    {
        // kt đăng nhập
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để xem lịch trình xe.'
            ], 401);
        }

        // lấy tất cả trip và car của user
        $tripQuery = Trip::where('user_id', $user->id)->with(['car']);

        // tk biển số
        if ($request->filled('search')) {
            $search = $request->search;
            $tripQuery->where(function ($q) use ($search) {
                $q->whereHas('car', function ($carQuery) use ($search) {
                    $carQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('license_plate', 'like', "%{$search}%");
                });
            });
        }

        // lọc trạng thái
        if ($request->filled('status') && in_array($request->status, [0, 1, 2, 3, 4, 5, 6])) {
            $status = (int)$request->status;
            $tripQuery->where('status', $status);
        }

        // loc loại thuê
        if ($request->filled('trip_type') && in_array($request->trip_type, [0, 1])) {
            $trip_type = (int)$request->trip_type;
            $tripQuery->where('trip_type', $trip_type);
        }

        // lọc loại chuyến đi
        $sortBy = $request->input('sort_by', 'latest');

        switch ($sortBy) {
            case 'oldest':
                $tripQuery->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $tripQuery->orderBy('cost', 'asc');
                break;
            case 'price_desc':
                $tripQuery->orderBy('cost', 'desc');
                break;
            case 'latest':
            default:
                $tripQuery->orderBy('created_at', 'desc');
                break;
        }
        $trips = $tripQuery->get();

        foreach ($trips as $trip) {
            switch ($trip->status) {
                case TripStatus::Pending->value: $trip->status_text = 'Chờ duyệt'; break;
                case TripStatus::WaitingPayment->value: $trip->status_text = 'Chờ thanh toán'; break;
                case TripStatus::Confirmed->value: $trip->status_text = 'Chưa bắt đầu'; break;
                case TripStatus::Ongoing->value: $trip->status_text = 'Đang diễn ra'; break;
                case TripStatus::Complete->value: $trip->status_text = 'Đã hoàn thành'; break;
                case TripStatus::UserCancel->value: $trip->status_text = 'Đã hủy bởi bạn'; break;
                case TripStatus::OwnerCancel->value: $trip->status_text = 'Đã hủy bởi chủ xe'; break;
                default: $trip->status_text = 'Không xác định'; break;
            }

            $trip->trip_type_text = ($trip->trip_type == 0) ? 'Thuê theo ngày' : 'Thuê theo km';
        }

        // 8. Trả về kết quả JSON cho Front-end
        return response()->json([
            'success' => true,
            'data' => $trips
        ], 200);
    }
}