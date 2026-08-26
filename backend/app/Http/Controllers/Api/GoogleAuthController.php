<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client;
use App\Models\User;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class GoogleAuthController extends Controller
{
    public function loginWithGoogle(Request $request)
    {
        // Get token from Nuxt / Flutter
        $idToken = $request->input('token') ?? $request->input('id_token');
        if (!$idToken) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy Token từ Google!'
            ], 400);
        }
        try {
            $payload = null;

            // Một JWT (ID Token) hợp lệ bắt buộc phải có đúng 3 phân đoạn phân tách bằng dấu chấm (.)
            $isJwt = (count(explode('.', $idToken)) == 3);

            // Bổ sung local bypass xác thực token cho môi trường phát triển (local)
            if (
                app()->environment('local') && (
                    !$isJwt ||
                    str_starts_with($idToken, 'web_fallback_token_')
                )
            ) {
                $email = $request->input('email');
                $name = $request->input('name') ?? 'Google Local Test';
                $avatar = $request->input('avatar') ?? $request->input('picture');
                if ($email) {
                    $payload = [
                        'email' => $email,
                        'name' => $name,
                        'picture' => $avatar,
                    ];
                }
            }

            // Nếu không thuộc diện bypass ở local, xác thực token chuẩn với Google
            if (!$payload) {
                $client = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
                $payload = $client->verifyIdToken($idToken);
            }

            if ($payload) {
                $email = $payload['email'];
                $name = $payload['name'] ?? 'User';
                $picture = $payload['picture'] ?? $request->input('avatar') ?? $request->input('picture') ?? null;

                // Kiểm tra xem Email này đã có trong DB chưa
                $user = User::where('email', $email)->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => bcrypt(Str::random(16)),
                        'email_verified_at' => now(),
                        'avatar' => $picture,
                        'status' => 1,
                        'role_id' => 2,
                    ]);
                } else {
                    // Cập nhật avatar nếu tài khoản chưa có avatar hoặc đang dùng link google
                    $needsSave = false;
                    if (!empty($picture) && (empty($user->avatar) || str_contains($user->avatar, 'googleusercontent.com'))) {
                        $user->avatar = $picture;
                        $needsSave = true;
                    }
                    if (empty($user->name) && !empty($name)) {
                        $user->name = $name;
                        $needsSave = true;
                    }
                    if ($needsSave) {
                        $user->save();
                    }
                }

                // Tạo token theo chuẩn JWT-AUTH
                $token = JWTAuth::fromUser($user);
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập Google thành công!',
                    'access_token' => $token,
                    'user' => $user->fresh(['drivingLicense', 'wallet', 'role'])
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Xác thực tài khoản Google thất bại hoặc Token hết hạn!'
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}
