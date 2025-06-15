<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('customers.name')]),
            'phone.required' => __('validation.required', ['attribute' => __('customers.phone')]),
            'name.string' => __('validation.string', ['attribute' => __('customers.name')]),
            'phone.string' => __('validation.string', ['attribute' => __('customers.phone')]),
            'active.boolean' => __('validation.boolean', ['attribute' => __('customers.active')]),
            'name.max' => __('validation.max.string', ['attribute' => __('customers.name'), 'max' => 255]),
            'phone.max' => __('validation.max.string', ['attribute' => __('customers.phone'), 'max' => 255]),
        ];
    }
}
