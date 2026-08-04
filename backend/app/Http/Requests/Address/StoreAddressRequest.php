<?php

namespace App\Http\Requests\Address;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreAddressRequest extends FormRequest
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
        return [
            'address_name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'address_name.required' => 'Địa chỉ không được để trống',
            'address_name.string' => 'Địa chỉ phải là chuỗi',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return response()->json([
            'success' => false,
            'message' => 'Dữ liệu địa chỉ không hợp lệ',
            'errors' => $validator->errors()
        ], 422);
    }
}
