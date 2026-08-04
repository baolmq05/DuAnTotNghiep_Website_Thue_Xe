<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FilterCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'startDate'  => 'nullable|date_format:Y-m-d H:i:s',
            'endDate'    => 'nullable|date_format:Y-m-d H:i:s|after:startDate',
            'address'    => 'nullable|string|max:255',
            'brand_id'   => 'nullable|integer|exists:car_brands,id',
            'type_id'    => 'nullable|integer|exists:car_types,id',
            'seat_count' => 'nullable|integer|min:2',
            'min_price'  => 'nullable|numeric|min:0',
            'max_price'  => 'nullable|numeric|gte:min_price',
        ];
    }

    public function messages(): array
    {
        return [
            'startDate.date_format' => 'Thời gian bắt đầu không đúng định dạng Y-m-d H:i:s.',
            'endDate.date_format'   => 'Thời gian kết thúc không đúng định dạng Y-m-d H:i:s.',
            'endDate.after'         => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'max_price.gte'          => 'Giá tối đa phải lớn hơn hoặc bằng giá tối thiểu.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu tìm kiếm không hợp lệ',
            'errors'  => $validator->errors()
        ], 422));
    }
}
