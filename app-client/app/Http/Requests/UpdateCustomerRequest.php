<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'active' => 'boolean',
            'name' => 'required|string|max:120',
            'phone' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'active.boolean' => __('customers.validation.active.boolean'),
            'name.required' => __('customers.validation.name.required'),
            'name.string' => __('customers.validation.name.string'),
            'name.max' => __('customers.validation.name.max'),
            'phone.string' => __('customers.validation.phone.string'),
            'phone.max' => __('customers.validation.phone.max'),
        ];
    }
}
