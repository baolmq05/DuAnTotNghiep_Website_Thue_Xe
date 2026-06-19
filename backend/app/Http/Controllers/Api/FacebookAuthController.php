<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class FacebookAuthController extends Controller
{
    public function loginWithFacebook(Request $request)
    {
        $accessToken = $request->input('accessToken');
        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy Access Token từ Facebook!'
            ], 400);
        }

        try {
            $response = Http::get("https://graph.facebook.com/v24.0/me", [
                'fields' => 'id,name,email,picture',
                'access_token' => $accessToken
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Xác thực tài khoản Facebook thất bại!'
                ], 401);
            }

            $fbUser = $response->json();
            $fbId = $fbUser['id'] ?? null;
            $name = $fbUser['name'] ?? null;
            $email = $fbUser['email'] ?? null;

            if (!$fbId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không lấy được ID người dùng từ Facebook!'
                ], 400);
            }

            $user = User::where('provider_id', $fbId)->first();

            if (!$user) {
                if ($email) {
                    $user = User::where('email', $email)->first();
                }

                if ($user) {
                    $user->update(['provider_id' => $fbId]);
                } else {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'provider_id' => $fbId,
                        'password' => bcrypt(Str::random(16)),
                        'status' => 1,
                        'role_id' => 2, 
                        'avatar' => $fbUser['picture']['data']['url'] ?? null
                    ]);
                }
            }

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập Facebook thành công!',
                'access_token' => $token,
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}
