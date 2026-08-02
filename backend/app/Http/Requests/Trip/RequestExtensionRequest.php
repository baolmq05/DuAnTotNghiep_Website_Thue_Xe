<?php

namespace App\Http\Requests\Trip;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestExtensionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'end_date' => 'nullable|date',
            'extended_days' => 'nullable|integer|min:1',
            'extension_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.date' => 'Ngày gia hạn không hợp lệ.',
            'extended_days.integer' => 'Số ngày gia hạn phải là số nguyên.',
            'extended_days.min' => 'Số ngày gia hạn phải tối thiểu 1 ngày.',
            'extension_amount.numeric' => 'Số tiền gia hạn không hợp lệ.',
            'extension_amount.min' => 'Số tiền gia hạn không được âm.',
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
