<?php

namespace App\Http\Requests\Promotion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckPromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'         => 'required|string',
            'start_at'     => 'required|date_format:Y-m-d H:i:s',
            'end_at'       => 'required|date_format:Y-m-d H:i:s|after:start_at',
            'car_id'       => 'required|exists:cars,id',
            'delivery_fee' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'         => 'Mã giảm giá không được để trống.',
            'code.string'           => 'Mã giảm giá phải là chuỗi ký tự.',
            'start_at.required'     => 'Thời gian bắt đầu chuyến đi không được để trống.',
            'start_at.date_format'  => 'Thời gian bắt đầu không đúng định dạng Y-m-d H:i:s.',
            'end_at.required'       => 'Thời gian kết thúc chuyến đi không được để trống.',
            'end_at.date_format'    => 'Thời gian kết thúc không đúng định dạng Y-m-d H:i:s.',
            'end_at.after'          => 'Thời gian kết thúc phải diễn ra sau thời gian bắt đầu.',
            'car_id.required'       => 'Mã xe không được để trống.',
            'car_id.exists'         => 'Xe được chọn không tồn tại trên hệ thống.',
            'delivery_fee.numeric'  => 'Phí giao xe phải là số.',
            'delivery_fee.min'      => 'Phí giao xe không được nhỏ hơn 0.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ.',
            'errors'  => $validator->errors()
        ], 422));
    }
}
