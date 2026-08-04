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
        // Check login
        $user = auth('api')->user();

        // Auto update trip status by time
        Trip::where('status', TripStatus::Ongoing->value)
            ->where('end_at', '<', now('Asia/Ho_Chi_Minh')->toDateTimeString())
            ->update(['status' => TripStatus::WaitingReturn->value]);

        // Get all trip and car of user
        $tripQuery = Trip::where('user_id', $user->id)->with(['car', 'extensions', 'latestExtension']);

        // Search car name or license plate
        if ($request->filled('search')) {
            $search = $request->search;
            $tripQuery->where(function ($q) use ($search) {
                $q->whereHas('car', function ($carQuery) use ($search) {
                    $carQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('license_plate', 'like', "%{$search}%");
                });
            });
        }

        // Fillter trip status
        if ($request->filled('status') && in_array($request->status, [0, 1, 2, 3, 4, 5, 6, 7, 8])) {
            $status = (int) $request->status;
            $tripQuery->where('status', $status);
        }

        // Fillter trip type
        if ($request->filled('trip_type') && in_array($request->trip_type, [0, 1])) {
            $trip_type = (int) $request->trip_type;
            $tripQuery->where('trip_type', $trip_type);
        }

        // Sort trip type
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
                case TripStatus::Pending->value:
                    $trip->status_text = 'Chờ duyệt';
                    break;
                case TripStatus::WaitingPayment->value:
                    $trip->status_text = 'Chờ thanh toán';
                    break;
                case TripStatus::Confirmed->value:
                    $trip->status_text = 'Chưa bắt đầu';
                    break;
                case TripStatus::Ongoing->value:
                    $trip->status_text = 'Đang diễn ra';
                    break;
                case TripStatus::Complete->value:
                    $trip->status_text = 'Đã hoàn thành';
                    break;
                case TripStatus::UserCancel->value:
                    $trip->status_text = 'Đã hủy bởi bạn';
                    break;
                case TripStatus::OwnerCancel->value:
                    $trip->status_text = 'Đã hủy bởi chủ xe';
                    break;
                case TripStatus::WaitingExtension->value:
                    $trip->status_text = 'Chờ gia hạn';
                    break;
                case TripStatus::WaitingReturn->value:
                    $trip->status_text = 'Chờ trả xe';
                    break;
                default:
                    $trip->status_text = 'Không xác định';
                    break;
            }

            $trip->trip_type_text = ($trip->trip_type == 0) ? 'Thuê theo ngày' : 'Thuê theo km';
        }

        // Return json
        return response()->json([
            'success' => true,
            'data' => $trips
        ], 200);
    }
}