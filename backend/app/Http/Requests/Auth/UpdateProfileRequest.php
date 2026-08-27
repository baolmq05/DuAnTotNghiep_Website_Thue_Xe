<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth('api')->id() ?? $this->user('api')?->id ?? $this->user()?->id;

        return [
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'starts_with:0',
                'digits:10',
                Rule::unique('users', 'phone')->ignore($userId),
            ],
            'gender' => 'nullable|integer|in:0,1,2',
            'DOB' => 'nullable|date|before_or_equal:today',
            'avatar' => 'nullable|string|max:2048',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.starts_with' => 'Số điện thoại phải bắt đầu bằng số 0.',
            'phone.digits' => 'Số điện thoại phải có đúng 10 số.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'DOB.date' => 'Ngày sinh không đúng định dạng ngày tháng.',
            'DOB.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hiện tại.',
            'avatar.max' => 'Đường dẫn ảnh đại diện quá dài.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first() ?? 'Dữ liệu không hợp lệ.',
            'errors' => $validator->errors()
        ], 422));
    }
}
