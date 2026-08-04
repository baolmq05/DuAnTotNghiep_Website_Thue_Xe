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
        $userId = $this->user()?->id ?? auth('api')->id();

        return [
            'name' => 'required|string|max:255',
            'phone' => [
                'nullable',
                'string',
                'max:15',
                Rule::unique('users', 'phone')->ignore($userId),
            ],
            'gender' => 'nullable|integer|in:0,1,2',
            'DOB' => 'nullable|date',
            'avatar' => 'nullable|string|max:2048',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'DOB.date' => 'Ngày sinh không đúng định dạng ngày tháng.',
            'avatar.max' => 'Đường dẫn ảnh đại diện quá dài.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ.',
            'errors' => $validator->errors()
        ], 422));
    }
}
