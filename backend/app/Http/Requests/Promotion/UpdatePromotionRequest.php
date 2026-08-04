<?php

namespace App\Http\Requests\Promotion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdatePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'code'           => ['required', Rule::unique('promotions', 'code')->ignore($id)],
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'discount_type'  => 'required|in:0,1',
            'discount_value' => 'required|numeric|min:0',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'usage_limit'    => 'required|integer',
            'per_user_limit' => 'required|integer',
            'status'         => 'required|in:0,1',
            'user_id'        => 'nullable',
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
            'usage_limit.required'    => 'Giới hạn lượt sử dụng không được để trống.',
            'usage_limit.integer'     => 'Giới hạn lượt sử dụng phải là số nguyên.',
            'per_user_limit.required' => 'Giới hạn sử dụng mỗi người không được để trống.',
            'per_user_limit.integer'  => 'Giới hạn sử dụng mỗi người phải là số nguyên.',
            'status.required'         => 'Trạng thái khuyến mãi không được để trống.',
            'status.in'               => 'Trạng thái không hợp lệ (chỉ được là 0 hoặc 1).',
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
