<?php

namespace App\Http\Requests\Trip;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => 'required|integer|exists:cars,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'trip_type' => 'nullable|integer',
            'delivery_address' => 'nullable|string',
            'delivery_location' => 'nullable|string',
            'promo_code' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.required' => 'Vui lòng chọn xe muốn thuê.',
            'car_id.exists' => 'Không tìm thấy xe được chọn.',
            'start_at.required' => 'Vui lòng chọn thời gian bắt đầu thuê.',
            'start_at.date' => 'Thời gian bắt đầu không hợp lệ.',
            'end_at.required' => 'Vui lòng chọn thời gian kết thúc thuê.',
            'end_at.date' => 'Thời gian kết thúc không hợp lệ.',
            'end_at.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
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
