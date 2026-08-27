<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SubmitDrivingLicenseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth('api')->user();
        $licenseId = $user?->driving_license_id;

        return [
            'driving_license_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('driving_licenses', 'driving_license_number')->ignore($licenseId)
            ],
            'full_name' => 'required|string|max:255',
            'DOB' => 'required|date|before_or_equal:today',
            'image' => [
                $licenseId ? 'nullable' : 'required',
                function ($attribute, $value, $fail) {
                    if ($this->hasFile('image')) {
                        $file = $this->file('image');
                        $extension = strtolower($file->getClientOriginalExtension());
                        if (!in_array($extension, ['jpeg', 'png', 'jpg'])) {
                            $fail('Định dạng ảnh phải là jpeg, png hoặc jpg.');
                        }
                        if ($file->getSize() > 5 * 1024 * 1024) {
                            $fail('Kích thước ảnh tối đa là 5MB.');
                        }
                    } else {
                        if (is_string($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $fail('Đường dẫn ảnh bằng lái xe không hợp lệ.');
                        }
                    }
                }
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'driving_license_number.required' => 'Số GPLX không được để trống.',
            'driving_license_number.unique' => 'Số GPLX này đã tồn tại trên hệ thống.',
            'full_name.required' => 'Họ và tên không được để trống.',
            'DOB.required' => 'Ngày sinh không được để trống.',
            'DOB.date' => 'Ngày sinh không đúng định dạng ngày tháng.',
            'DOB.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hiện tại.',
            'image.required' => 'Ảnh bằng lái xe không được để trống.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()
        ], 422));
    }
}
