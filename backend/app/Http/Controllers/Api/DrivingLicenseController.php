<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SubmitDrivingLicenseRequest;
use App\Models\DrivingLicense;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class DrivingLicenseController extends Controller
{
    /**
     * Gửi hoặc cập nhật thông tin Bằng lái xe để xác thực
     * POST /api/auth/profile/driving-license
     */
    public function store(SubmitDrivingLicenseRequest $request)
    {
        $user = auth('api')->user();

        try {
            DB::beginTransaction();

            // 1. Upload ảnh bằng lái xe qua CloudinaryService
            $imageUrl = CloudinaryService::upload(
                $request->file('image') ?? $request->input('image'),
                'licenses'
            );

            // 2. Chuẩn bị dữ liệu bằng lái
            $drivingLicenseData = [
                'driving_license_number' => $request->input('driving_license_number'),
                'full_name'              => $request->input('full_name'),
                'DOB'                    => Carbon::parse($request->input('DOB'))->format('Y-m-d'),
                'status'                 => 0, // 0: Chờ duyệt
            ];
            if ($imageUrl) {
                $drivingLicenseData['image'] = $imageUrl;
            }

            // 3. Tạo mới hoặc cập nhật thông tin bằng lái
            if ($user->driving_license_id) {
                $drivingLicense = DrivingLicense::findOrFail($user->driving_license_id);
                $drivingLicense->update($drivingLicenseData);
            } else {
                $drivingLicense = DrivingLicense::create($drivingLicenseData);
                $user->update(['driving_license_id' => $drivingLicense->id]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gửi yêu cầu duyệt bằng lái xe thành công.',
                'user'    => $user->fresh('drivingLicense')
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi gửi duyệt bằng lái.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
