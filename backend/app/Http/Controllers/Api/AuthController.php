<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|max:16',
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.max' => 'Mật khẩu tối đa là 16 ký tự.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:16',
            'confirm_password' => 'required|same:password',
        ], [
            'name.required' => 'Họ và tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email này đã được sử dụng',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu tối đa là 16 ký tự',
            'confirm_password.required' => 'Vui lòng xác nhận lại mật khẩu',
            'confirm_password.same' => 'Mật khẩu xác nhận không đúng',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role_id' => 2, // Mặc định là Khách hàng (User)
                'status' => 1,  // Hoạt động
            ]);

            // Tự động đăng nhập sau khi đăng ký thành công
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

        } catch (\Exception $e) {
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
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15|unique:users,phone,' . $user->id,
            'gender' => 'nullable|integer|in:0,1,2',
            'DOB' => 'nullable|date_format:Y-m-d',
            'avatar' => 'nullable|string|max:2048',
        ], [
            'name.required' => 'Họ và tên không được để trống.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'DOB.date_format' => 'Ngày sinh không đúng định dạng YYYY-MM-DD.',
            'avatar.max' => 'Đường dẫn ảnh đại diện quá dài.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only('name', 'phone', 'gender', 'DOB', 'avatar'));

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
    public function changePassword(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|max:16|different:current_password',
            'new_password_confirmation' => 'required|string|same:new_password',
        ], [
            'current_password.required' => 'Mật khẩu hiện tại không được để trống.',
            'new_password.required' => 'Mật khẩu mới không được để trống.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.max' => 'Mật khẩu mới tối đa là 16 ký tự.',
            'new_password.different' => 'Mật khẩu mới phải khác mật khẩu hiện tại.',
            'new_password_confirmation.required' => 'Xác nhận mật khẩu mới không được để trống.',
            'new_password_confirmation.same' => 'Mật khẩu xác nhận không khớp.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Kiểm tra mật khẩu hiện tại
        if (!\Illuminate\Support\Facades\Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không chính xác.'
            ], 400);
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => bcrypt($request->input('new_password'))
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đổi mật khẩu thành công.'
        ]);
    }

    /**
     * Submit or update the driving license for verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitDrivingLicense(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'driving_license_number' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('driving_licenses', 'driving_license_number')->ignore($user->driving_license_id)
            ],
            'full_name' => 'required|string|max:255',
            'DOB' => 'required|date_format:Y-m-d',
            'image' => [
                $user->driving_license_id ? 'nullable' : 'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->hasFile('image')) {
                        $file = $request->file('image');
                        $extension = strtolower($file->getClientOriginalExtension());
                        if (!in_array($extension, ['jpeg', 'png', 'jpg'])) {
                            $fail('Định dạng ảnh phải là jpeg, png hoặc jpg.');
                        }
                        if ($file->getSize() > 5 * 1024 * 1024) {
                            $fail('Kích thước ảnh tối đa là 5MB.');
                        }
                    } else {
                        if (is_string($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $fail('Đường dẫn ảnh bằng lái xe không hợp lệ.');
                        }
                    }
                }
            ],
        ], [
            'driving_license_number.required' => 'Số GPLX không được để trống.',
            'driving_license_number.unique' => 'Số GPLX này đã tồn tại trên hệ thống.',
            'full_name.required' => 'Họ và tên không được để trống.',
            'DOB.required' => 'Ngày sinh không được để trống.',
            'DOB.date_format' => 'Ngày sinh không đúng định dạng YYYY-MM-DD.',
            'image.required' => 'Ảnh bằng lái xe không được để trống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $drivingLicenseData = [
                'driving_license_number' => $request->input('driving_license_number'),
                'full_name' => $request->input('full_name'),
                'DOB' => $request->input('DOB'),
                'status' => 0, // Chờ duyệt
            ];

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $path = $imageFile->storeAs('licenses', $filename, 'public');
                $imageUrl = asset('storage/' . $path);
                $drivingLicenseData['image'] = $imageUrl;
            } elseif ($request->filled('image')) {
                $drivingLicenseData['image'] = $request->input('image');
            }

            if ($user->driving_license_id) {
                // Update existing
                $drivingLicense = \App\Models\DrivingLicense::findOrFail($user->driving_license_id);
                $drivingLicense->update($drivingLicenseData);
            } else {
                // Create new
                $drivingLicense = \App\Models\DrivingLicense::create($drivingLicenseData);
                $user->update([
                    'driving_license_id' => $drivingLicense->id
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            // Load the updated relationship
            $user->load('drivingLicense');

            return response()->json([
                'success' => true,
                'message' => 'Gửi yêu cầu duyệt bằng lái xe thành công.',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi gửi duyệt bằng lái.',
                'error' => $e->getMessage()
            ], 500);
        }
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
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->input('email');
        $otp = mt_rand(100000, 999999);

        // Save OTP to password_reset_tokens table
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => \Illuminate\Support\Facades\Hash::make($otp),
                'created_at' => now()
            ]
        );

        // Send OTP email
        try {
            \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\ResetPasswordOtp($otp));
        } catch (\Exception $e) {
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
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string|size:6',
            'password' => 'required|string|min:6|max:16',
            'confirm_password' => 'required|same:password',
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
            'token.required' => 'Mã OTP không được để trống.',
            'token.size' => 'Mã OTP phải gồm 6 chữ số.',
            'password.required' => 'Mật khẩu mới không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.max' => 'Mật khẩu tối đa là 16 ký tự.',
            'confirm_password.required' => 'Vui lòng xác nhận lại mật khẩu mới.',
            'confirm_password.same' => 'Mật khẩu xác nhận không đúng.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->input('email');
        $otp = $request->input('token');

        // Check if OTP exists in database
        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn.'
            ], 400);
        }

        // Check expiration (15 minutes)
        if (now()->diffInMinutes(\Carbon\Carbon::parse($record->created_at)) > 15) {
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $email)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP đã hết hạn.'
            ], 400);
        }

        // Verify OTP hash
        if (!\Illuminate\Support\Facades\Hash::check($otp, $record->token)) {
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
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu của bạn đã được thay đổi thành công. Vui lòng đăng nhập bằng mật khẩu mới.'
        ]);
    }
}
