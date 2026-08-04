<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|integer|min:20000',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Số tiền cần rút không được bỏ trống.',
            'amount.integer'  => 'Số tiền cần rút phải là số nguyên.',
            'amount.min'      => 'Số tiền rút tối thiểu là 20.000đ.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'errors'  => $validator->errors()
        ], 422));
    }
}
