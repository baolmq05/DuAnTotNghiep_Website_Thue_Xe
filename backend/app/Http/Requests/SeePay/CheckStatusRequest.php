<?php

namespace App\Http\Requests\SeePay;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_type' => 'required|string|in:rental,deposit,penalty,extension',
            'id'           => 'required|integer',
            'amount'       => 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'payment_type.required' => 'Loại thanh toán không được để trống.',
            'payment_type.string'   => 'Loại thanh toán phải là chuỗi.',
            'payment_type.in'       => 'Loại thanh toán không hợp lệ.',
            'id.required'           => 'ID định danh bắt buộc phải có.',
            'id.integer'            => 'ID định danh phải là số nguyên.',
            'amount.required'       => 'Số tiền thanh toán bắt buộc phải nhập.',
            'amount.numeric'        => 'Số tiền thanh toán phải là dạng số.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu kiểm tra không hợp lệ.',
            'errors'  => $validator->errors()
        ], 422));
    }
}
