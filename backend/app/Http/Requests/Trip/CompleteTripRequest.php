<?php

namespace App\Http\Requests\Trip;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompleteTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => 'required|array|min:1',
            'images.*' => 'required|string|url',
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Bạn phải tải lên ít nhất 1 ảnh xe sau chuyến đi để hoàn thành.',
            'images.min' => 'Bạn phải tải lên ít nhất 1 ảnh xe sau chuyến đi để hoàn thành.',
            'images.*.url' => 'Đường dẫn hình ảnh không hợp lệ.',
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
