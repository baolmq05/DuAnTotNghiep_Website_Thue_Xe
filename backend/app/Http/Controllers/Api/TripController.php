<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\ChatConversation;
use Illuminate\Http\Request;
use Exception;
use InvalidArgumentException;

use App\Http\Requests\Trip\StoreTripRequest;
use App\Http\Requests\Trip\StartTripRequest;
use App\Http\Requests\Trip\CompleteTripRequest;
use App\Http\Requests\Trip\StoreReviewRequest;

use App\Actions\Trip\CreateTripAction;
use App\Actions\Trip\ConfirmTripAction;
use App\Actions\Trip\RejectTripAction;
use App\Actions\Trip\StartTripAction;
use App\Actions\Trip\RequestReturnTripAction;
use App\Actions\Trip\CompleteTripAction;
use App\Actions\Trip\CancelTripByRenterAction;
use App\Actions\Trip\CancelTripByOwnerAction;
use App\Actions\Trip\StoreTripReviewAction;

class TripController extends Controller
{
    /**
     * Get list of trips for current user (Renter & Owner).
     * GET /api/trips
     */
    public function index()
    {
        $user = auth('api')->user();

        $this->autoUpdateExpiredTrips();

        // My booked trips (Renter)
        $myTrips = Trip::where('user_id', $user->id)
            ->with(['car.carLocation', 'car.images', 'car.carBrand', 'car.carType', 'car.owner', 'reviews.reviewer', 'extensions', 'latestExtension'])
            ->orderBy('created_at', 'desc')
            ->get();

        // My rented out cars (Owner)
        $ownerTrips = Trip::whereHas('car', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['car.carLocation', 'car.images', 'car.carBrand', 'car.carType', 'user', 'reviews.reviewer', 'extensions', 'latestExtension'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'booked' => $myTrips,
                'owner' => $ownerTrips,
                'my_trips' => $myTrips,
                'owner_trips' => $ownerTrips,
            ]
        ]);
    }

    /**
     * Create a new trip request.
     * POST /api/trips
     */
    public function store(StoreTripRequest $request, CreateTripAction $action)
    {
        $user = auth('api')->user();

        try {
            $trip = $action->execute($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Gửi yêu cầu thuê xe thành công!',
                'data' => $trip
            ], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thực hiện thuê xe: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trip details.
     * GET /api/trips/{id}
     */
    public function show($id)
    {
        $user = auth('api')->user();

        $this->autoUpdateExpiredTrips();

        $trip = Trip::with([
            'car.carLocation',
            'car.images',
            'car.carBrand',
            'car.carType',
            'car.owner',
            'user',
            'reviews.reviewer',
            'extensions',
            'latestExtension',
            'transactions',
            'pendingBalances',
            'images',
            'conversation',
            'reports.images'
        ])->find($id);

        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin chuyến đi.'
            ], 404);
        }

        if ($trip->user_id !== $user->id && $trip->car->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xem thông tin chuyến đi này.'
            ], 403);
        }

        // Ensure chat conversation exists for this trip
        if (!$trip->conversation) {
            ChatConversation::createForTrip($trip);
            $trip->load('conversation');
        }

        return response()->json([
            'success' => true,
            'data' => $trip
        ]);
    }

    /**
     * Owner confirms trip request (Pending -> WaitingPayment).
     * PUT /api/trips/{id}/confirm
     */
    public function confirm($id, ConfirmTripAction $action)
    {
        $user = auth('api')->user();

        try {
            $trip = $action->execute((int)$id, $user);

            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận yêu cầu thuê xe thành công, chờ khách hàng thanh toán!',
                'data' => $trip
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi xác nhận chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Owner rejects trip request (Pending -> OwnerCancel).
     * PUT /api/trips/{id}/reject
     */
    public function reject(Request $request, $id, RejectTripAction $action)
    {
        $user = auth('api')->user();

        try {
            $reason = $request->input('reason');
            $trip = $action->execute((int)$id, $user, $reason);

            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối yêu cầu thuê xe!',
                'data' => $trip
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi từ chối chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start the trip (Upload images & change status to Ongoing).
     * POST /api/trips/{id}/start
     */
    public function startTrip(StartTripRequest $request, $id, StartTripAction $action)
    {
        $user = auth('api')->user();

        try {
            $trip = $action->execute((int)$id, $user, $request->input('images', []));

            return response()->json([
                'success' => true,
                'message' => 'Bắt đầu chuyến đi thành công!',
                'data' => $trip
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi bắt đầu chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Renter requests to return the car (Early return).
     * POST /api/trips/{id}/return-request
     */
    public function requestReturn($id, RequestReturnTripAction $action)
    {
        $user = auth('api')->user();

        try {
            $trip = $action->execute((int)$id, $user);

            return response()->json([
                'success' => true,
                'message' => 'Gửi yêu cầu trả xe thành công!',
                'data' => $trip
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi gửi yêu cầu trả xe.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Owner confirms trip completion (Upload images & change status to Complete).
     * POST /api/trips/{id}/complete
     */
    public function completeTrip(CompleteTripRequest $request, $id, CompleteTripAction $action)
    {
        $user = auth('api')->user();

        try {
            $trip = $action->execute((int)$id, $user, $request->input('images', []));

            return response()->json([
                'success' => true,
                'message' => 'Hoàn thành chuyến đi thành công!',
                'data' => $trip
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi hoàn thành chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit a review for the trip.
     * POST /api/trips/{id}/reviews
     */
    public function storeReview(StoreReviewRequest $request, $id, StoreTripReviewAction $action)
    {
        $user = auth('api')->user();

        try {
            $review = $action->execute((int)$id, $user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Gửi đánh giá thành công!',
                'data' => $review
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi gửi đánh giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Renter cancels the trip.
     * POST /api/trips/{id}/cancel
     */
    public function cancelTrip($id, CancelTripByRenterAction $action)
    {
        $user = auth('api')->user();

        try {
            $result = $action->execute((int)$id, $user);

            return response()->json([
                'success' => true,
                'message' => 'Hủy chuyến đi thành công!',
                'data' => [
                    'trip' => $result['trip'],
                    'cancellation_summary' => $result['summary']
                ]
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi hủy chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Owner cancels the trip.
     * POST /api/trips/{id}/owner-cancel
     */
    public function cancelTripByOwner(Request $request, $id, CancelTripByOwnerAction $action)
    {
        $user = auth('api')->user();

        try {
            $reason = $request->input('reason');
            $result = $action->execute((int)$id, $user, $reason);

            return response()->json([
                'success' => true,
                'message' => 'Chủ xe hủy chuyến đi thành công! Đã hoàn trả tiền cho khách thuê.',
                'data' => [
                    'trip' => $result['trip'],
                    'cancellation_summary' => $result['summary']
                ]
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi hủy chuyến đi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function autoUpdateExpiredTrips()
    {
        Trip::where('status', TripStatus::Ongoing->value)
            ->where('end_at', '<', now('Asia/Ho_Chi_Minh')->toDateTimeString())
            ->update(['status' => TripStatus::WaitingReturn->value]);
    }
}
