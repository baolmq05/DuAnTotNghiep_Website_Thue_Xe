<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddressController extends Controller
{
    /**
     * API Hiển thị danh sách địa chỉ
     * GET /api/addresses
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

            $addresses = $user->addresses()->get();
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách địa chỉ thành công',
                'data' => $addresses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lấy danh sách địa chỉ thất bại',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Thêm địa chỉ mới
     * POST /api/addresses
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ],[
            'address_name.required' => 'Địa chỉ không được để trống',
            'address_name.string' => 'Địa chỉ phải là chuỗi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu địa chỉ không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $address = Address::create([
            'address_name' => $request->address_name,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm địa chỉ thành công',
            'data' => $address
        ], 201);
    }

    /**
     * API Cập nhật địa chỉ
     * PUT /api/addresses/{id}
     */
    public function update(Request $request, $id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy địa chỉ'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'address_name' => 'required|string|max:255',
        ],[
            'address_name.required' => 'Địa chỉ không được để trống',
            'address_name.string' => 'Địa chỉ phải là chuỗi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu địa chỉ không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $address->update([
            'address_name' => $request->address_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật địa chỉ thành công',
            'data' => $address->fresh()
        ]);
    }

    /**
     * API Xóa địa chỉ
     * DELETE /api/addresses/{id}
     */
    public function destroy($id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy địa chỉ'
            ], 404);
        }

        $address->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa địa chỉ thành công'
        ]);
    }
}