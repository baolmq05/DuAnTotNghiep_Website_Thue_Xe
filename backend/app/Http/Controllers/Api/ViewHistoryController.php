<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ViewHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewHistoryController extends Controller
{
    /**
     * Ghi nhận hoặc cập nhật thời gian xem xe của user
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'car_id' => 'required|integer|exists:cars,id',
        ]);

        $userId = Auth::id();
        $carId = (int) $request->input('car_id');

        $viewHistory = ViewHistory::updateOrCreate(
            ['user_id' => $userId, 'car_id' => $carId],
            ['updated_at' => now()]
        );

        return response()->json([
            'success' => true,
            'message' => 'Đã ghi nhận lịch sử xem xe.',
            'data' => $viewHistory
        ]);
    }

    /**
     * Lấy danh sách lịch sử xe đã xem của user
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $limit = (int) $request->get('limit', 20);

        $viewHistories = ViewHistory::where('user_id', $userId)
            ->with([
                'car' => function ($query) {
                    $query->with(['images', 'carBrand', 'carType', 'carLocation', 'owner'])
                        ->withAvg(['reviews as reviews_avg_rating' => function ($q) {
                            $q->where('review_type', 1);
                        }], 'rating')
                        ->withCount(['trips as trips_count' => function ($q) {
                            $q->where('status', 4); // Chỉ đếm các chuyến đi đã hoàn thành (Complete)
                        }]);
                }
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'Lấy lịch sử xem xe thành công.',
            'data' => $viewHistories
        ]);
    }

    /**
     * Xóa 1 xe khỏi lịch sử xem
     */
    public function destroy(int $carId): JsonResponse
    {
        $userId = Auth::id();

        $deleted = ViewHistory::where('user_id', $userId)
            ->where('car_id', $carId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa xe khỏi lịch sử xem.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy xe trong lịch sử xem.'
        ], 404);
    }
}
