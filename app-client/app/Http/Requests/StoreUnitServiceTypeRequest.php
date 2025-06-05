<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('unit_service_types')->where(function ($query) {
                    return $query->where('company_id', Auth::user()->company_id);
                })->ignore($this->unitServiceType)
            ],
            'description' => ['nullable', 'string', 'max:255'],
            'active' => ['boolean'],
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
            'name' => __('unit-service-types.name'),
            'description' => __('unit-service-types.description'),
            'active' => __('unit-service-types.active'),
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
            'name.required' => __('unit-service-types.validation.name.required'),
            'name.string' => __('unit-service-types.validation.name.string'),
            'name.max' => __('unit-service-types.validation.name.max'),
            'name.unique' => __('unit-service-types.validation.name.unique'),
            'description.string' => __('unit-service-types.validation.description.string'),
            'description.max' => __('unit-service-types.validation.description.max'),
            'active.boolean' => __('unit-service-types.validation.active.boolean'),
        ];
    }
}
