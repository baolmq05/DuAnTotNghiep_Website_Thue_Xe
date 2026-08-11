<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use App\Models\Car;
use App\Enum\TripStatus;
use Illuminate\Support\Facades\DB;
use Exception;

class ReviewController extends Controller
{
    /**
     * Get profile details and reviews for a user (Owner or Renter).
     * GET /api/profile/reviews/{targetId}
     */
    public function getProfileReviews(Request $request, $targetId)
    {
        try {
            // Fetch user with preloaded cars and their relations
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

            $isOwner = $request->query('isOwner') == 'true';

            // 1. Get and format reviews list
            $reviews = $this->getFormattedReviews($targetId, $isOwner);

            // 2. Get and format cars list (Only applicable for owner profiles)
            $carsData = $isOwner ? $this->getFormattedCars($user->cars, $user) : [];

            // 3. Compute profile statistics
            $averageProfileRating = $this->calculateAverageRating($user, $reviews, $isOwner);
            $tripsCount = $this->calculateTripsCount($targetId, $user, $isOwner);

            return response()->json([
                'success' => true,
                'data' => [
                    'id'          => $user->id,
                    'name'        => $user->name,
                    'avatar'      => $user->avatar ? url($user->avatar) : null,
                    'joinDate'    => $user->created_at ? $user->created_at->format('m/Y') : date('m/Y'),
                    'rating'      => (float) $averageProfileRating,
                    'tripsCount'  => $tripsCount,
                    'trips_count' => $tripsCount,
                    'cars'        => $carsData,
                    'reviews'     => $reviews
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get and format the profile reviews list.
     */
    private function getFormattedReviews($targetId, bool $isOwner)
    {
        $reviewType = $isOwner ? 1 : 0;

        return Review::with(['reviewer:id,name,avatar'])
            ->where('target_id', $targetId)
            ->where('review_type', $reviewType)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($review) {
                return [
                    'id'              => $review->id,
                    'reviewer_id'     => $review->reviewer_id,
                    'reviewer_name'   => $review->reviewer->name ?? 'Người dùng ẩn danh',
                    'reviewer_avatar' => $review->reviewer->avatar ? url($review->reviewer->avatar) : null,
                    'rating'          => (float) $review->rating,
                    'comment'         => $review->comment,
                    'created_at'      => $review->created_at->format('d/m/Y'),
                ];
            });
    }

    /**
     * Get and format the list of owner's cars.
     */
    private function getFormattedCars($cars, User $user): array
    {
        if (!$cars) {
            return [];
        }

        return $cars->map(function ($car) use ($user) {
            $imageData = $this->formatCarImages($car);
            
            // Count completed trips (Status 4 = Complete)
            $completedTripsCount = $car->trips ? $car->trips->where('status', TripStatus::Complete->value)->count() : 0;

            $carLoc = $car->carLocation;
            $addressText = $carLoc ? ($carLoc->address ?? $carLoc->location ?? 'Chưa xác định') : 'Chưa xác định';
            $locationText = $carLoc ? ($carLoc->location ?? '') : '';

            // Calculate car's average rating (review_type = 1)
            $carReviews = $car->reviews ? $car->reviews->where('review_type', 1) : collect();
            $finalCarRating = $carReviews->isNotEmpty() ? (float) round($carReviews->avg('rating'), 1) : 0.0;

            return [
                'id'                 => $car->id,
                'name'               => $car->car_name ?? $car->name,
                'license_plate'      => $car->license_plate ?? '',
                'fuel_consumption'   => $car->fuel_consumption ?? 0,
                'unit_price'         => $car->price_per_day ?? $car->unit_price ?? 0,
                'discount_value'     => $car->discount_value ?? 0,
                'description'        => $car->description ?? '',
                'rental_terms'       => $car->rental_terms ?? '',
                'seat_count'         => $car->seats ?? $car->seat_count ?? 4,
                'manufacture_year'   => $car->manufacture_year ?? '',
                'fuel_type'          => $car->fuel_type,
                'transmission'       => $car->transmission,
                'status'             => $car->status ?? 1,
                'user_id'            => $car->user_id ?? 0,
                'reviews_avg_rating' => $finalCarRating,
                'trips_count'        => $completedTripsCount,
                'image'              => $imageData['singleImage'],
                'images'             => $imageData['carImages'],
                'features'           => [],
                'car_location'       => [
                    'id'       => $carLoc->id ?? 0,
                    'location' => $locationText,
                    'address'  => $addressText,
                ],
                'owner'              => [
                    'id'     => $user->id,
                    'name'   => $user->name,
                    'avatar' => $user->avatar ? url($user->avatar) : null,
                ],
            ];
        })->toArray();
    }

    /**
     * Format and generate car images urls list.
     */
    private function formatCarImages(Car $car): array
    {
        $carImages = [];

        if ($car->images && $car->images->isNotEmpty()) {
            foreach ($car->images as $img) {
                $carImages[] = [
                    'id'           => $img->id,
                    'car_id'       => $img->car_id,
                    'image_url'    => $img->image_url ? url($img->image_url) : (isset($img->image) ? url($img->image) : ''),
                    'is_thumbnail' => $img->is_thumbnail ?? 0
                ];
            }
        } elseif (!empty($car->image)) {
            $carImages[] = [
                'id'           => 0,
                'car_id'       => $car->id,
                'image_url'    => url($car->image),
                'is_thumbnail' => 1
            ];
        }

        $singleImage = !empty($carImages) ? $carImages[0]['image_url'] : '';

        return [
            'carImages'   => $carImages,
            'singleImage' => $singleImage
        ];
    }

    /**
     * Calculate profile average rating (Helper).
     */
    private function calculateAverageRating(User $user, $reviews, bool $isOwner): float
    {
        if ($isOwner) {
            $cars = $user->cars;
            if (!$cars || $cars->isEmpty()) {
                return 0.0;
            }

            $totalRatingSum = 0;
            $carCount = 0;

            foreach ($cars as $car) {
                $carReviews = $car->reviews ? $car->reviews->where('review_type', 1) : collect();
                $finalCarRating = $carReviews->isNotEmpty() ? (float) round($carReviews->avg('rating'), 1) : 0.0;
                $totalRatingSum += $finalCarRating;
                $carCount++;
            }

            return $carCount > 0 ? round($totalRatingSum / $carCount, 1) : 0.0;
        }

        return $reviews->isNotEmpty() ? (float) round($reviews->avg('rating'), 1) : 0.0;
    }

    /**
     * Calculate total trips count (Helper).
     */
    private function calculateTripsCount($targetId, User $user, bool $isOwner): int
    {
        if ($isOwner) {
            $cars = $user->cars;
            if (!$cars) {
                return 0;
            }

            $totalOwnerTrips = 0;
            foreach ($cars as $car) {
                $totalOwnerTrips += $car->trips ? $car->trips->where('status', TripStatus::Complete->value)->count() : 0;
            }
            return $totalOwnerTrips;
        }

        return DB::table('trips')
            ->where('user_id', $targetId)
            ->where('status', TripStatus::Complete->value) // Completed trips
            ->count();
    }
}