<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Nội dung thông báo không được để trống.',
            'message.string'   => 'Nội dung thông báo phải là chuỗi.',
            'user_id.required' => 'ID người dùng không được để trống.',
            'user_id.exists'   => 'Người dùng không tồn tại.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu thông báo không hợp lệ',
            'errors'  => $validator->errors()
        ], 422));
    }
}
