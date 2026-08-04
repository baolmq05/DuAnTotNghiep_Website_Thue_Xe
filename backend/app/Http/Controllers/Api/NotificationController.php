<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\Notification\StoreNotificationRequest;
use App\Http\Requests\Notification\UpdateNotificationRequest;
use Exception;

class NotificationController extends Controller
{
    /**
     * Get list of notifications for current user.
     * GET /api/auth/notifications
     */
    public function index(Request $request)
    {
        try {
            $user = auth('api')->user();

            $userId = $request->query('user_id');
            if ($userId) {
                $notifications = Notification::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
            } else {
                $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách thông báo thành công',
                'data' => $notifications
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lấy danh sách thông báo thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new notification.
     * POST /api/auth/notifications
     */
    public function store(StoreNotificationRequest $request)
    {
        $notification = Notification::create([
            'message' => $request->message,
            'user_id' => $request->user_id,
            'is_read' => '0',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thông báo thành công',
            'data' => $notification
        ], 201);
    }

    /**
     * Update notification status (read/unread).
     * PUT /api/auth/notifications/{id}
     */
    public function update(UpdateNotificationRequest $request, $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông báo'
            ], 404);
        }

        $notification->update([
            'is_read' => $request->is_read,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông báo thành công',
            'data' => $notification->fresh()
        ]);
    }

    /**
     * Mark all notifications as read.
     * PUT /api/auth/notifications/read-all
     */
    public function readAll(Request $request)
    {
        try {
            $user = auth('api')->user();

            Notification::where('user_id', $user->id)
                ->where('is_read', '0')
                ->update(['is_read' => '1']);

            return response()->json([
                'success' => true,
                'message' => 'Đánh dấu tất cả thông báo là đã đọc thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đánh dấu tất cả thông báo là đã đọc thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a notification.
     * DELETE /api/auth/notifications/{id}
     */
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông báo'
            ], 404);
        }

        $notification->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa thông báo thành công'
        ]);
    }
}
