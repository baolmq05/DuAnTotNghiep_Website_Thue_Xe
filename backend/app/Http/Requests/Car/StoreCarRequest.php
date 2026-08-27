<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if (is_string($this->input('images'))) {
            $decoded = json_decode($this->input('images'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['images' => $decoded]);
            }
        }

        if (is_string($this->input('features'))) {
            $decoded = json_decode($this->input('features'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['features' => $decoded]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'license_plate'          => 'required|string|max:12|unique:cars,license_plate',
            'VIN'                    => 'required|string|max:17|unique:cars,VIN',
            'engine_number'          => 'required|string|max:100|unique:cars,engine_number',
            'car_brand_id'           => 'required|exists:car_brands,id',
            'car_type_id'            => 'required|exists:car_types,id',
            'seat_count'             => 'required|integer|min:2',
            'manufacture_year'       => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'fuel_type'              => 'required|string|max:255',
            'transmission'           => 'required|string|max:255',
            'fuel_consumption'       => 'required|numeric|gt:0',
            'description'            => 'nullable|string',
            'rental_terms'           => 'nullable|string',

            // Location
            'location'               => 'nullable|string',
            'address'                => 'required|string',

            // Pricing & Discount
            'unit_price'             => 'required|numeric|gt:0',
            'discount_value'         => 'nullable|numeric|min:0',

            // Delivery options
            'delivery_enabled'       => 'required|in:0,1',
            'delivery_max_distance'  => 'nullable|numeric|min:0',
            'delivery_fee'           => 'nullable|numeric|min:0',
            'delivery_free_distance' => 'nullable|numeric|min:0',

            // Usage limit
            'km_limit_enabled'       => 'required|in:0,1',
            'km_limit_val'           => 'nullable|numeric|min:0',
            'over_fee_val'           => 'nullable|numeric|min:0',

            // Features
            'features'               => 'nullable|array',
            'features.*'             => 'integer|exists:features,id',

            // Images
            'images'                 => 'required|array|min:1',
            'images.*'               => 'required|string|url',
            'thumbnail_index'        => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'license_plate.required'    => 'Biển số xe không được để trống.',
            'license_plate.max'         => 'Biển số xe không được vượt quá 12 ký tự.',
            'license_plate.unique'      => 'Biển số xe này đã được đăng ký trên hệ thống.',
            'VIN.required'              => 'Số khung (VIN) không được để trống.',
            'VIN.max'                   => 'Số khung (VIN) không được vượt quá 17 ký tự.',
            'VIN.unique'                => 'Số khung (VIN) này đã được đăng ký trên hệ thống.',
            'engine_number.required'    => 'Số máy không được để trống.',
            'engine_number.max'         => 'Số máy không được vượt quá 100 ký tự.',
            'engine_number.unique'      => 'Số máy này đã được đăng ký trên hệ thống.',
            'car_brand_id.required'     => 'Vui lòng chọn hãng xe.',
            'car_brand_id.exists'       => 'Hãng xe đã chọn không tồn tại.',
            'car_type_id.required'      => 'Vui lòng chọn mẫu xe.',
            'car_type_id.exists'        => 'Mẫu xe đã chọn không tồn tại.',
            'seat_count.required'       => 'Vui lòng chọn số chỗ ngồi.',
            'seat_count.min'            => 'Số chỗ ngồi tối thiểu là 2.',
            'manufacture_year.required' => 'Vui lòng chọn năm sản xuất.',
            'fuel_consumption.required' => 'Mức tiêu thụ nhiên liệu không được để trống.',
            'fuel_consumption.numeric'  => 'Mức tiêu thụ nhiên liệu phải là số.',
            'fuel_consumption.gt'       => 'Mức tiêu thụ nhiên liệu phải lớn hơn 0 (L/100km).',
            'fuel_consumption.min'      => 'Mức tiêu thụ nhiên liệu phải lớn hơn 0 (L/100km).',
            'unit_price.required'       => 'Đơn giá thuê xe không được để trống.',
            'unit_price.gt'             => 'Đơn giá thuê xe phải lớn hơn 0.',
            'address.required'          => 'Vui lòng nhập địa chỉ xe.',
            'images.required'           => 'Bạn cần tải lên ít nhất 1 hình ảnh xe.',
            'images.min'                => 'Bạn cần tải lên ít nhất 1 hình ảnh xe.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $firstError = $errors->first();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $firstError ?: 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.',
            'errors'  => $errors
        ], 422));
    }
}
