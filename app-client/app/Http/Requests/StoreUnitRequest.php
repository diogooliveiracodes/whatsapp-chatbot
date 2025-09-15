<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming the user is authorized to create units
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('units')->where(function ($query) {
                    return $query->where('company_id', Auth::user()->company_id);
                })
            ],
            'active' => ['boolean'],
            'image_name' => ['nullable', 'string', 'max:255'],
            'image_path' => ['nullable', 'string', 'max:2048'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('units.attributes.name'),
            'active' => __('units.attributes.active'),
            'image_name' => __('units.attributes.image_name'),
            'image_path' => __('units.attributes.image_path'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('units.validation.name.required'),
            'name.string' => __('units.validation.name.string'),
            'name.max' => __('units.validation.name.max'),
            'active.boolean' => __('units.validation.active.boolean'),
            'image_name.string' => __('validation.string', ['attribute' => __('units.attributes.image_name')]),
            'image_name.max' => __('validation.max.string', ['attribute' => __('units.attributes.image_name'), 'max' => 255]),
            'image_path.string' => __('validation.string', ['attribute' => __('units.attributes.image_path')]),
            'image_path.max' => __('validation.max.string', ['attribute' => __('units.attributes.image_path'), 'max' => 2048]),
        ];
    }
}
