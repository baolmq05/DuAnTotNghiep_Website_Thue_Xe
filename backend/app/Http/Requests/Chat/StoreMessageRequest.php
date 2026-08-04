<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMessageRequest extends FormRequest
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
        return [
            'conversation_id' => 'required|exists:chat_conversations,id',
            'type'            => 'required|in:text,image',
            'text'            => 'required_if:type,text|nullable|string',
            'image'           => 'required_if:type,image|nullable|image|max:10240', // Tối đa 10MB
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
            'conversation_id.required' => 'Hội thoại không được để trống.',
            'conversation_id.exists'   => 'Hội thoại không tồn tại.',
            'type.required'            => 'Loại tin nhắn không được để trống.',
            'type.in'                  => 'Loại tin nhắn không hợp lệ.',
            'text.required_if'         => 'Nội dung tin nhắn không được để trống.',
            'image.required_if'        => 'Hình ảnh không được để trống.',
            'image.image'              => 'Định dạng ảnh phải là jpeg, png hoặc jpg.',
            'image.max'                => 'Dung lượng hình ảnh tối đa là 10MB.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Dữ liệu tin nhắn không hợp lệ',
            'errors'  => $validator->errors()
        ], 422));
    }
}
