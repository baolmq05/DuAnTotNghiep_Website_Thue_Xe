<?php

namespace App\Http\Requests\Report;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReportRequest extends FormRequest
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
            'trip_id' => 'required|integer|exists:trips,id',
            'report_type' => 'required|integer|in:0,1,2,3',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'required|string|url',
        ];
    }

    public function messages(): array
    {
        return [
            'trip_id.required' => 'Vui lòng cung cấp mã chuyến đi.',
            'trip_id.exists' => 'Không tìm thấy chuyến đi tương ứng.',
            'report_type.required' => 'Vui lòng chọn loại khiếu nại.',
            'report_type.in' => 'Loại khiếu nại không hợp lệ.',
            'description.required' => 'Vui lòng nhập mô tả chi tiết sự việc.',
            'images.array' => 'Định dạng hình ảnh không hợp lệ.',
            'images.*.url' => 'Đường dẫn hình ảnh không hợp lệ.'
        ];
    }

    public function attributes(): array
    {
        return [
            'trip_id' => 'Chuyến đi',
            'report_type' => 'Loại khiếu nại',
            'description' => 'Mô tả',
            'images' => 'Hình ảnh'
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
