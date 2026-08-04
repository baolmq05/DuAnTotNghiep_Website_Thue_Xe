<?php

namespace App\Http\Requests\ZaloPay;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_type' => 'required|string|in:rental,deposit,penalty,extension',
            'amount'       => 'required|numeric|min:1000',
            'trip_id'      => 'required_if:payment_type,rental,penalty,extension|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'payment_type.required' => 'Loại thanh toán không được để trống.',
            'payment_type.string'   => 'Loại thanh toán phải là chuỗi.',
            'payment_type.in'       => 'Loại thanh toán không hợp lệ.',
            'amount.required'       => 'Số tiền không được để trống.',
            'amount.numeric'        => 'Số tiền phải là số.',
            'amount.min'            => 'Số tiền tối thiểu phải là 1,000đ.',
            'trip_id.required_if'   => 'Mã chuyến đi bắt buộc phải nhập cho loại thanh toán này.',
            'trip_id.integer'       => 'Mã chuyến đi phải là số nguyên.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu yêu cầu không hợp lệ.',
            'errors'  => $validator->errors()
        ], 422));
    }
}
