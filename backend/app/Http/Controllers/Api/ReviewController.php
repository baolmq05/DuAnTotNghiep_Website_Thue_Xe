<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Thêm Facade để query DB trực tiếp nếu cần

class ReviewController extends Controller
{
    public function getProfileReviews(Request $request, $targetId)
    {
        try {
            $isOwner = $request->query('isOwner') === 'true' ? 1 : 0;
            $user = User::with([
                'cars.images', 
                'cars.carLocation', 
                'cars.trips',
                'cars.reviews'
            ])->find($targetId);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin người dùng.'
                ], 404);
            }

            // Bốc danh sách nhận xét profile công khai
            $reviews = Review::with(['reviewer:id,name,avatar'])
                ->where('target_id', $targetId)
                ->where('review_type', $isOwner)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'reviewer_id' => $review->reviewer_id,
                        'reviewer_name' => $review->reviewer->name ?? 'Người dùng ẩn danh',
                        'reviewer_avatar' => $review->reviewer->avatar ? url($review->reviewer->avatar) : null,
                        'rating' => (float) $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at->format('d/m/Y'),
                    ];
                });

            // Các biến tích lũy tổng cho profile chủ xe
            $totalOwnerTrips = 0;
            $totalRatingSum = 0;
            $carCountWithRating = 0;

            // Xử lý map danh sách xe (Chỉ có ý nghĩa nếu là Chủ xe)
            $carsData = $user->cars ? $user->cars->map(function ($car) use ($user, &$totalOwnerTrips, &$totalRatingSum, &$carCountWithRating) {
                $carImages = [];
                if ($car->images && $car->images->isNotEmpty()) {
                    foreach ($car->images as $img) {
                        $carImages[] = [
                            'id' => $img->id,
                            'car_id' => $img->car_id,
                            'image_url' => $img->image_url ? url($img->image_url) : (isset($img->image) ? url($img->image) : ''),
                            'is_thumbnail' => $img->is_thumbnail ?? 0
                        ];
                    }
                } else if (!empty($car->image)) {
                    $carImages[] = [
                        'id' => 0,
                        'car_id' => $car->id,
                        'image_url' => url($car->image),
                        'is_thumbnail' => 1
                    ];
                }

                $singleImage = !empty($carImages) ? $carImages[0]['image_url'] : '';

                // Đếm số chuyến đi của xe (Trạng thái 4 là Complete)
                $completedTripsCount = $car->trips ? $car->trips->where('status', 4)->count() : 0;
                $totalOwnerTrips += $completedTripsCount;

                $carLoc = $car->carLocation;
                $addressText = $carLoc ? ($carLoc->address ?? $carLoc->location ?? 'Chưa xác định') : 'Chưa xác định';
                $locationText = $carLoc ? ($carLoc->location ?? '') : '';

                $carReviews = $car->reviews ? $car->reviews->where('review_type', 1) : collect();
                $finalCarRating = $carReviews->isNotEmpty() ? (float) round($carReviews->avg('rating'), 1) : 0.0;

                $totalRatingSum += $finalCarRating;
                $carCountWithRating++;

                return [
                    'id' => $car->id,
                    'name' => $car->car_name ?? $car->name,
                    'license_plate' => $car->license_plate ?? '',
                    'fuel_consumption' => $car->fuel_consumption ?? 0,
                    'unit_price' => $car->price_per_day ?? $car->unit_price ?? 0,
                    'discount_value' => $car->discount_value ?? 0,
                    'description' => $car->description ?? '',
                    'rental_terms' => $car->rental_terms ?? '',
                    'seat_count' => $car->seats ?? $car->seat_count ?? 4,
                    'manufacture_year' => $car->manufacture_year ?? '',
                    'fuel_type' => $car->fuel_type,
                    'transmission' => $car->transmission,
                    'status' => $car->status ?? 1,
                    'user_id' => $car->user_id ?? 0,
                    'reviews_avg_rating' => $finalCarRating,
                    'trips_count' => $completedTripsCount,
                    'image' => $singleImage,
                    'images' => $carImages,
                    'features' => [],
                    'car_location' => [
                        'id' => $carLoc->id ?? 0,
                        'location' => $locationText,
                        'address' => $addressText,
                    ],
                    'owner' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar ? url($user->avatar) : null,
                    ],
                ];
            })->toArray() : [];

            // 
            if ($isOwner === 1) {
                $averageProfileRating = $carCountWithRating > 0 ? round($totalRatingSum / $carCountWithRating, 1) : 0.0;
                $finalTripsCount = $totalOwnerTrips;
            } else {
                $averageProfileRating = $reviews->isNotEmpty() ? round($reviews->avg('rating'), 1) : 0.0;
                $finalTripsCount = DB::table('trips')
                    ->where('user_id', $targetId)
                    ->where('status', 4) // Hoàn thành
                    ->count();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar ? url($user->avatar) : null,
                    'joinDate' => $user->created_at ? $user->created_at->format('m/Y') : date('m/Y'),
                    'rating' => (float) $averageProfileRating,
                    'tripsCount' => $finalTripsCount,
                    'trips_count' => $finalTripsCount,
                    'cars' => $carsData,
                    'reviews' => $reviews
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}