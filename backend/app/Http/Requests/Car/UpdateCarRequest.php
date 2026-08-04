<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
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
        $carId = $this->route('id');

        return [
            'license_plate'          => ['required', 'string', 'max:12', Rule::unique('cars', 'license_plate')->ignore($carId)],
            'VIN'                    => ['required', 'string', 'max:17', Rule::unique('cars', 'VIN')->ignore($carId)],
            'engine_number'          => ['required', 'string', 'max:100', Rule::unique('cars', 'engine_number')->ignore($carId)],
            'car_brand_id'           => 'required|exists:car_brands,id',
            'car_type_id'            => 'required|exists:car_types,id',
            'seat_count'             => 'required|integer|min:2',
            'manufacture_year'       => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'fuel_type'              => 'required|string|max:255',
            'transmission'           => 'required|string|max:255',
            'fuel_consumption'       => 'required|numeric|min:0',
            'description'            => 'nullable|string',
            'rental_terms'           => 'nullable|string',

            // Location
            'location'               => 'nullable|string',
            'address'                => 'required|string',

            // Pricing & Discount
            'unit_price'             => 'required|numeric|min:0',
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
            'license_plate.required' => 'Biển số xe không được để trống.',
            'license_plate.unique'   => 'Biển số xe này đã được đăng ký trên hệ thống.',
            'VIN.required'           => 'Số khung không được để trống',
            'VIN.unique'             => 'Số khung này đã được đăng ký trên hệ thống',
            'engine_number.required' => 'Số máy không được để trống',
            'engine_number.unique'   => 'Số máy này đã được đăng ký trên hệ thống',
            'car_brand_id.required'  => 'Hãng xe không được để trống.',
            'car_brand_id.exists'    => 'Hãng xe không tồn tại.',
            'car_type_id.required'   => 'Mẫu xe không được để trống.',
            'car_type_id.exists'     => 'Mẫu xe không tồn tại.',
            'images.required'        => 'Bạn cần tải lên ít nhất 1 hình ảnh xe.',
            'images.min'             => 'Bạn cần tải lên ít nhất 1 hình ảnh xe.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422));
    }
}
