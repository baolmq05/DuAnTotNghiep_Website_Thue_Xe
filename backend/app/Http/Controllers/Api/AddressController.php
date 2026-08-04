<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Exception;

class AddressController extends Controller
{
    /**
     * API Hiển thị danh sách địa chỉ
     * GET /api/addresses
     */
    public function index(Request $request)
    {
        try {
            $user = auth('api')->user();

            $userId = $request->query('user_id');
            if ($userId) {
                $addresses = Address::where('user_id', $userId)->get();
            } else {
                $addresses = $user->addresses()->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách địa chỉ thành công',
                'data' => $addresses
            ]);
        } catch (Exception $e) {
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
    public function store(StoreAddressRequest $request)
    {
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
    public function update(UpdateAddressRequest $request, $id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy địa chỉ'
            ], 404);
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