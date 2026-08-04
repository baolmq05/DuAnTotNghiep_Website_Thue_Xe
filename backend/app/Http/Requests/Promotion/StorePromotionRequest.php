<?php

namespace App\Http\Requests\Promotion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'           => 'required|unique:promotions,code',
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'discount_type'  => 'required|in:0,1',
            'discount_value' => 'required|numeric|min:0',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'           => 'Mã khuyến mãi không được để trống.',
            'code.unique'             => 'Mã khuyến mãi này đã tồn tại trên hệ thống.',
            'name.required'           => 'Tên khuyến mãi không được để trống.',
            'name.string'             => 'Tên khuyến mãi phải là chuỗi ký tự.',
            'name.max'                => 'Tên khuyến mãi không được vượt quá 255 ký tự.',
            'description.required'    => 'Mô tả khuyến mãi không được để trống.',
            'description.string'      => 'Mô tả khuyến mãi phải là chuỗi ký tự.',
            'discount_type.required'  => 'Loại giảm giá không được để trống.',
            'discount_type.in'        => 'Loại giảm giá không hợp lệ (chỉ được là 0 hoặc 1).',
            'discount_value.required' => 'Giá trị giảm giá không được để trống.',
            'discount_value.numeric'  => 'Giá trị giảm giá phải là dạng số.',
            'discount_value.min'      => 'Giá trị giảm giá không được nhỏ hơn 0.',
            'start_date.required'     => 'Ngày bắt đầu không được để trống.',
            'start_date.date'         => 'Ngày bắt đầu không đúng định dạng ngày.',
            'end_date.required'       => 'Ngày kết thúc không được để trống.',
            'end_date.date'           => 'Ngày kết thúc không đúng định dạng ngày.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu khuyến mãi không hợp lệ.',
            'errors'  => $validator->errors()
        ], 422));
    }
}
