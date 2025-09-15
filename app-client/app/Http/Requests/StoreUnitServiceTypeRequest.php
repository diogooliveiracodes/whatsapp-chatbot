<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitServiceTypeRequest extends FormRequest
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
            'unit_id' => ['required', 'exists:units,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
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
            'unit_id.required' => 'A unidade é obrigatória',
            'unit_id.exists' => 'A unidade selecionada é inválida',
            'name.required' => 'O nome é obrigatório',
            'name.string' => 'O nome deve ser um texto',
            'name.max' => 'O nome não pode ter mais de 255 caracteres',
            'price.required' => 'O preço é obrigatório',
            'price.numeric' => 'O preço deve ser um número',
            'price.min' => 'O preço deve ser maior que 0',
            'description.string' => 'A descrição deve ser um texto',
            'image_name.string' => __('validation.string', ['attribute' => 'image_name']),
            'image_name.max' => __('validation.max.string', ['attribute' => 'image_name', 'max' => 255]),
            'image_path.string' => __('validation.string', ['attribute' => 'image_path']),
            'image_path.max' => __('validation.max.string', ['attribute' => 'image_path', 'max' => 2048]),
        ];
    }
}
