<?php

namespace App\Http\Controllers\Api;

use App\Enum\TripStatus;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;
use Exception;

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
     * API Danh sách chuyến đi của người dùng (Renter & Owner)
     * GET /api/trips
     */
    public function index()
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        $this->autoUpdateExpiredTrips();

        // Chuyến đi của tôi (Renter)
        $myTrips = Trip::where('user_id', $user->id)
            ->with(['car.carLocation', 'car.images', 'car.carBrand', 'car.carType', 'car.owner', 'reviews.reviewer', 'extensions', 'latestExtension'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Xe cho thuê của tôi (Owner)
        $ownerTrips = Trip::whereHas('car', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['car.carLocation', 'car.images', 'car.carBrand', 'car.carType', 'user', 'reviews.reviewer', 'extensions', 'latestExtension'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'my_trips' => $myTrips,
                'owner_trips' => $ownerTrips,
            ]
        ]);
    }

    /**
     * API Tạo chuyến đi mới (Yêu cầu thuê xe)
     * POST /api/trips
     */
    public function store(StoreTripRequest $request, CreateTripAction $action)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        try {
            $trip = $action->execute($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Gửi yêu cầu thuê xe thành công!',
                'data' => $trip
            ], 201);
        } catch (\InvalidArgumentException $e) {
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
     * API Xem chi tiết chuyến đi
     * GET /api/trips/{id}
     */
    public function show($id)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

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
            'images'
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

        return response()->json([
            'success' => true,
            'data' => $trip
        ]);
    }

    /**
     * API Chủ xe xác nhận yêu cầu thuê xe (chuyển status từ 0 - Pending sang 1 - WaitingPayment)
     * PUT /api/trips/{id}/confirm
     */
    public function confirm($id, ConfirmTripAction $action)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        try {
            $trip = $action->execute((int)$id, $user);

            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận yêu cầu thuê xe thành công, chờ khách hàng thanh toán!',
                'data' => $trip
            ]);
        } catch (\InvalidArgumentException $e) {
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
     * API Chủ xe từ chối yêu cầu thuê xe (chuyển status sang 6 - OwnerCancel)
     * PUT /api/trips/{id}/reject
     */
    public function reject(Request $request, $id, RejectTripAction $action)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        try {
            $reason = $request->input('reason');
            $trip = $action->execute((int)$id, $user, $reason);

            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối yêu cầu thuê xe!',
                'data' => $trip
            ]);
        } catch (\InvalidArgumentException $e) {
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
     * API Bắt đầu chuyến đi (upload ảnh trước chuyến đi & đổi trạng thái sang 3 - Ongoing)
     * POST /api/trips/{id}/start
     */
    public function startTrip(StartTripRequest $request, $id, StartTripAction $action)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        try {
            $trip = $action->execute((int)$id, $user, $request->input('images', []));

            return response()->json([
                'success' => true,
                'message' => 'Bắt đầu chuyến đi thành công!',
                'data' => $trip
            ]);
        } catch (\InvalidArgumentException $e) {
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
     * API Khách thuê bấm trả xe (Trả xe sớm)
     * POST /api/trips/{id}/return-request
     */
    public function requestReturn($id, RequestReturnTripAction $action)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        try {
            $trip = $action->execute((int)$id, $user);

            return response()->json([
                'success' => true,
                'message' => 'Gửi yêu cầu trả xe thành công!',
                'data' => $trip
            ]);
        } catch (\InvalidArgumentException $e) {
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
     * API Chủ xe xác nhận hoàn thành chuyến xe (upload ảnh sau chuyến đi & đổi trạng thái sang 4 - Complete)
     * POST /api/trips/{id}/complete
     */
    public function completeTrip(CompleteTripRequest $request, $id, CompleteTripAction $action)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        try {
            $trip = $action->execute((int)$id, $user, $request->input('images', []));

            return response()->json([
                'success' => true,
                'message' => 'Hoàn thành chuyến đi thành công!',
                'data' => $trip
            ]);
        } catch (\InvalidArgumentException $e) {
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
     * API Gửi đánh giá cho chuyến đi
     * POST /api/trips/{id}/reviews
     */
    public function storeReview(StoreReviewRequest $request, $id, StoreTripReviewAction $action)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

        try {
            $review = $action->execute((int)$id, $user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Gửi đánh giá thành công!',
                'data' => $review
            ]);
        } catch (\InvalidArgumentException $e) {
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
     * API Khách thuê (Renter) tự hủy chuyến đi
     * POST /api/trips/{id}/cancel
     */
    public function cancelTrip($id, CancelTripByRenterAction $action)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

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
        } catch (\InvalidArgumentException $e) {
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
     * API Chủ xe (Owner) hủy chuyến đi
     * POST /api/trips/{id}/owner-cancel
     */
    public function cancelTripByOwner(Request $request, $id, CancelTripByOwnerAction $action)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.'
            ], 401);
        }

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
        } catch (\InvalidArgumentException $e) {
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
