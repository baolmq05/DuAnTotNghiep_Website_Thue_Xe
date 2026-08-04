<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordOtp;
use Exception;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin đăng nhập không chính xác.'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role_id' => 2, // Default (User)
                'status' => 1,  // Default (Active)
            ]);

            // Auto login after successful registration
            $token = auth('api')->login($user);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký tài khoản thành công.',
                'user' => $user,
                'token_info' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 60
                ]
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra trong quá trình đăng ký.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated user's profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth('api')->user();

        $data = $request->only('name', 'phone', 'gender', 'DOB', 'avatar', 'bank_name', 'bank_account_number');
        if (!empty($data['DOB'])) {
            $data['DOB'] = Carbon::parse($data['DOB'])->format('Y-m-d');
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin tài khoản thành công.',
            'user' => $user
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Change the authenticated user's password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth('api')->user();

        // Check current pass
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không chính xác.'
            ], 400);
        }

        // Update new password
        $user->update([
            'password' => bcrypt($request->input('new_password'))
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đổi mật khẩu thành công.'
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }

    /**
     * Send OTP for password reset.
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->input('email');
        $otp = mt_rand(100000, 999999);

        // Save OTP to password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => now()
            ]
        );

        // Send OTP email
        try {
            Mail::to($email)->send(new ResetPasswordOtp($otp));
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi email OTP. Vui lòng thử lại sau.',
                'error_detail' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mã OTP khôi phục mật khẩu đã được gửi đến email của bạn.'
        ]);
    }

    /**
     * Reset password using OTP.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $email = $request->input('email');
        $otp = $request->input('token');

        // Check if OTP exists in database
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn.'
            ], 400);
        }

        // Check expiration (15 minutes)
        if (now()->diffInMinutes(Carbon::parse($record->created_at), true) > 15) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP đã hết hạn.'
            ], 400);
        }

        // Verify OTP hash
        if (!Hash::check($otp, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP không chính xác.'
            ], 400);
        }

        // Update password
        $user = User::where('email', $email)->first();
        $user->update([
            'password' => bcrypt($request->input('password'))
        ]);

        // Delete token entry
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu của bạn đã được thay đổi thành công. Vui lòng đăng nhập bằng mật khẩu mới.'
        ]);
    }
}
