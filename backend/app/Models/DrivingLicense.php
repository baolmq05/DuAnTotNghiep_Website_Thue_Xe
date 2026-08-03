<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DrivingLicense extends Model
{
    //
    protected $fillable = ['full_name', 'image', 'driving_license_number', 'DOB', 'status'];

    protected static function booted()
    {
        static::updated(function ($drivingLicense) {
            if ($drivingLicense->wasChanged('status')) {
                $user = User::where('driving_license_id', $drivingLicense->id)->first();
                if ($user) {
                    $status = (int) $drivingLicense->status;
                    $message = '';
                    if ($status === 1) {
                        $message = 'Giấy phép lái xe của bạn đã được duyệt thành công. Giờ đây bạn đã có thể tiến hành thuê xe!';
                    } elseif ($status === 2) {
                        $message = 'Giấy phép lái xe của bạn đã bị từ chối do thông tin chưa hợp lệ. Vui lòng cập nhật lại hình ảnh hoặc thông tin GPLX chính xác.';
                    }

                    if ($message) {
                        Notification::create([
                            'user_id' => $user->id,
                            'message' => $message,
                            'is_read' => '0',
                        ]);
                    }
                }
            }
        });
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'driving_license_id');
    }
}
