<?php

namespace App\Actions\Trip;

use App\Enum\TripStatus;
use App\Models\Notification;
use App\Models\Review;
use App\Models\Trip;
use App\Models\User;
use InvalidArgumentException;
use Exception;

class StoreTripReviewAction
{
    public function execute(int $tripId, User $user, array $data): Review
    {
        $trip = Trip::with('car')->find($tripId);
        if (!$trip) {
            throw new InvalidArgumentException('Không tìm thấy thông tin chuyến đi.');
        }

        if ($trip->user_id !== $user->id && $trip->car->user_id !== $user->id) {
            throw new InvalidArgumentException('Bạn không có quyền thực hiện hành động này.');
        }

        if ($trip->status !== TripStatus::Complete->value) {
            throw new InvalidArgumentException('Chuyến đi phải hoàn thành mới có thể đánh giá.');
        }

        // Xác định review_type và target_id
        if ($trip->user_id === $user->id) {
            // Renter đánh giá Owner
            $reviewType = 0;
            $targetId = $trip->car->user_id;
        } else {
            // Owner đánh giá Renter
            $reviewType = 1;
            $targetId = $trip->user_id;
        }

        // Kiểm tra trùng lặp đánh giá
        $exists = Review::where('trip_id', $trip->id)
            ->where('reviewer_id', $user->id)
            ->where('review_type', $reviewType)
            ->exists();

        if ($exists) {
            throw new InvalidArgumentException('Bạn đã gửi đánh giá cho chuyến đi này rồi.');
        }

        try {
            $review = Review::create([
                'trip_id' => $trip->id,
                'reviewer_id' => $user->id,
                'target_id' => $targetId,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
                'review_type' => $reviewType,
            ]);

            Notification::create([
                'user_id' => $targetId,
                'message' => "Bạn đã nhận được đánh giá {$data['rating']} sao cho chuyến đi #{$trip->id}.",
                'is_read' => '0',
            ]);

            return $review->load('reviewer');

        } catch (Exception $e) {
            throw $e;
        }
    }
}
