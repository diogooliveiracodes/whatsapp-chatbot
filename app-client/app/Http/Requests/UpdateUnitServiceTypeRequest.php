<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitServiceTypeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'active' => ['nullable', 'boolean'],
            'price' => ['required', 'string', 'max:10'],
            'monday' => ['nullable', 'boolean'],
            'tuesday' => ['nullable', 'boolean'],
            'wednesday' => ['nullable', 'boolean'],
            'thursday' => ['nullable', 'boolean'],
            'friday' => ['nullable', 'boolean'],
            'saturday' => ['nullable', 'boolean'],
            'sunday' => ['nullable', 'boolean'],
            'image_name' => ['nullable', 'string', 'max:255'],
            'image_path' => ['nullable', 'string', 'max:2048'],
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
            'name.required' => 'O nome é obrigatório',
            'name.string' => 'O nome deve ser um texto',
            'name.max' => 'O nome não pode ter mais de 255 caracteres',
            'description.string' => 'A descrição deve ser um texto',
            'price.required' => 'O preço é obrigatório',
            'price.string' => 'O preço deve ser um texto',
            'price.max' => 'O preço não pode ter mais de 10 caracteres',
            'image_name.string' => __('validation.string', ['attribute' => 'image_name']),
            'image_name.max' => __('validation.max.string', ['attribute' => 'image_name', 'max' => 255]),
            'image_path.string' => __('validation.string', ['attribute' => 'image_path']),
            'image_path.max' => __('validation.max.string', ['attribute' => 'image_path', 'max' => 2048]),
        ];
    }
}
