<?php
namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Models\FavoriteItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class FavoriteController extends Controller
{
    // Hiển thị danh sách yêu thích
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy danh sách yêu thích của người dùng hiện tại
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
                        ->withAvg('reviews', 'rating')
                        ->withCount(['trips' => function ($q) {
                            $q->where('status', 2); // Chỉ đếm các chuyến đi đã hoàn thành thành công
                        }]);
                }
            ])
                ->where('user_id', $user->id)
                ->first();

            // Kiểm tra nếu danh sách yêu thích trống
            if (!$favorites) {
                return response()->json([
                    'success' => true,
                    'message' => 'Danh sách yêu thích trống.',
                    'data' => [],
                ]);
            }

            // Trả về danh sách yêu thích
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ hoặc token đã hết hạn',
                'errors' => $e->getMessage()
            ], 401);
        }
    }

    // Thêm vào danh sách yêu thích
    public function store(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            $request->validate([
                'car_id' => 'required|exists:cars,id',
            ]);
        
            // Lấy danh sách yêu thích của người dùng hiện tại
            $favorite = Favorite::firstOrCreate([
                'user_id' => $user->id,
            ]);
        
            // Kiểm tra xem xe đã tồn tại trong danh sách chưa
            $exists = FavoriteItem::where('favorite_id', $favorite->id)
                ->where('car_id', $request->car_id)
                ->exists();
        
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Xe đã có trong danh sách yêu thích.',
                ]);
            }
        
            // Thêm xe vào danh sách yêu thích
            $favoriteItem = FavoriteItem::create([
                'favorite_id' => $favorite->id,
                'car_id' => $request->car_id,
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích.',
                'data' => $favoriteItem,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ hoặc token đã hết hạn',
                'errors' => $e->getMessage()
            ], 401);
        }
    }

    // Xóa khỏi danh sách yêu thích
    public function destroy($carId)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            // Lấy danh sách yêu thích của người dùng hiện tại
            $favorite = Favorite::where('user_id', $user->id)->first();
        
            if (!$favorite) {
                return response()->json([
                    'success' => false,
                    'message' => 'Danh sách yêu thích không tồn tại.',
                ], 404);
            }
        
            // Tìm mục yêu thích cần xóa
            $favoriteItem = FavoriteItem::where('favorite_id', $favorite->id)
                ->where('car_id', $carId)
                ->first();
        
            if (!$favoriteItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Xe không có trong danh sách yêu thích.',
                ], 404);
            }
        
            // Xóa mục yêu thích
            $favoriteItem->delete();
        
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ hoặc token đã hết hạn',
                'errors' => $e->getMessage()
            ], 401);
        }
    }
}