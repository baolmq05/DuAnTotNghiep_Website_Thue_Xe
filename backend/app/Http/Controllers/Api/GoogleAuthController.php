<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client;
use App\Models\User;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoogleAuthController extends Controller
{
    public function loginWithGoogle(Request $request)
    {
        // lấy token gửi từ Nuxt lên
        $idToken = $request->input('token');
        if (!$idToken) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy Token từ Google!'
            ], 400);
        }
        try {
            // sử dụng thư viện Google vừa cài để xác thực Token
            $client = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $payload = $client->verifyIdToken($idToken);
            if ($payload) {
                $email = $payload['email'];
                $name = $payload['name'];
                // kiểm tra xem Email này đã có trong DB chưa, chưa có thì tự tạo mới
                // đã thêm 'status' và 'role_id' dự phòng lỗi NOT NULL của database
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'password' => bcrypt(Str::random(16)), 
                        'email_verified_at' => now(),
                        'status' => 1,                      
                        'role_id' => 2,                     
                    ]
                );
                // tạo token theo chuẩn JWT-AUTH đồng bộ với Model User của bạn
                $token = JWTAuth::fromUser($user);
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập Google thành công!',
                    'access_token' => $token,// trả về token lưu vaofo cookie
                    'user' => $user
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Xác thực tài khoản Google thất bại hoặc Token hết hạn!'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}
