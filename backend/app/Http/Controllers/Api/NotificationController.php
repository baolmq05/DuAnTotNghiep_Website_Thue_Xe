<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationController extends Controller
{
    /**
     * API Hiển thị danh sách thông báo
     * GET /api/auth/notifications
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lấy danh sách thông báo thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Tạo thông báo mới
     * POST /api/auth/notifications
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ],[
            'message.required' => 'Nội dung thông báo không được để trống',
            'message.string' => 'Nội dung thông báo phải là chuỗi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu thông báo không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

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
     * API Cập nhật trạng thái thông báo (đọc/chưa đọc)
     * PUT /api/auth/notifications/{id}
     */
    public function update(Request $request, $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông báo'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'is_read' => 'required|in:0,1',
        ],[
            'is_read.required' => 'Trạng thái đọc không được để trống',
            'is_read.in' => 'Trạng thái đọc không hợp lệ',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
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
     * API Đánh dấu tất cả thông báo là đã đọc
     * PUT /api/auth/notifications/read-all
     */
    public function readAll(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng'
                ], 404);
            }

            Notification::where('user_id', $user->id)
                ->where('is_read', '0')
                ->update(['is_read' => '1']);

            return response()->json([
                'success' => true,
                'message' => 'Đánh dấu tất cả thông báo là đã đọc thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đánh dấu tất cả thông báo là đã đọc thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Xóa thông báo
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
