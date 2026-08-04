<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Models\Favorite;
use App\Models\FavoriteItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class FavoriteController extends Controller
{
    /**
     * Get favorite list of current user.
     * GET /api/favorites
     */
    public function index()
    {
        try {
            $user = auth('api')->user();

            // List favorite of current user
            $favorites = Favorite::with([
                'items.car' => function ($query) {
                    $query->with([
                        'carLocation',
                        'carBrand',
                        'carType',
                        'images',
                        'owner' => function ($q) {
                            $q->select('id', 'name', 'avatar');
                        }
                    ])
                        ->withAvg([
                            'reviews' => function ($q) {
                                $q->where('review_type', 1);
                            }
                        ], 'rating')
                        ->withCount([
                            'trips' => function ($q) {
                                $q->where('status', TripStatus::Complete->value); // Chỉ đếm các chuyến đi đã hoàn thành thành công
                            }
                        ]);
                }
            ])
                ->where('user_id', $user->id)
                ->first();

            // Check
            if (!$favorites) {
                return response()->json([
                    'success' => true,
                    'message' => 'Danh sách yêu thích trống.',
                    'data' => [],
                ]);
            }

            // Return
            return response()->json([
                'success' => true,
                'message' => 'Danh sách yêu thích.',
                'data' => $favorites->items->map(function ($item) {
                    if (!$item->car) {
                        return null;
                    }
                    return [
                        'id' => $item->id,
                        'car_id' => $item->car->id,
                        'car_name' => $item->car->name,
                        'created_at' => $item->created_at,
                        'car' => $item->car,
                    ];
                })->filter()->values(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách yêu thích.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a car to favorites.
     * POST /api/favorites
     */
    public function store(Request $request)
    {
        try {
            $user = auth('api')->user();

            $request->validate([
                'car_id' => 'required|exists:cars,id',
            ]);

            // Get favorite of current user
            $favorite = Favorite::firstOrCreate([
                'user_id' => $user->id,
            ]);

            // Check exist
            $exists = FavoriteItem::where('favorite_id', $favorite->id)
                ->where('car_id', $request->car_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Xe đã có trong danh sách yêu thích.',
                ]);
            }

            // Eloquent
            $favoriteItem = FavoriteItem::create([
                'favorite_id' => $favorite->id,
                'car_id' => $request->car_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích.',
                'data' => $favoriteItem,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm yêu thích.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a car from favorites.
     * DELETE /api/favorites/{car_id}
     */
    public function destroy($carId)
    {
        try {
            $user = auth('api')->user();

            // Get favorite of current user
            $favorite = Favorite::where('user_id', $user->id)->first();

            if (!$favorite) {
                return response()->json([
                    'success' => false,
                    'message' => 'Danh sách yêu thích không tồn tại.',
                ], 404);
            }

            // Find item
            $favoriteItem = FavoriteItem::where('favorite_id', $favorite->id)
                ->where('car_id', $carId)
                ->first();

            if (!$favoriteItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Xe không có trong danh sách yêu thích.',
                ], 404);
            }

            // Remove
            $favoriteItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa yêu thích.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}