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
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:20|regex:/^\(\d{2}\) \d{4,5}-\d{4}$/',
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
            'name.max' => __('validation.max.string', ['attribute' => __('customers.name'), 'max' => 120]),
            'phone.max' => __('validation.max.string', ['attribute' => __('customers.phone'), 'max' => 20]),
            'phone.regex' => __('customers.validation.phone.format'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean the phone number to only digits before validation
        if ($this->has('phone')) {
            $phone = preg_replace('/\D/', '', $this->phone);

            // Limit to 11 digits (Brazilian phone format)
            if (strlen($phone) > 11) {
                $phone = substr($phone, 0, 11);
            }

            // Format as Brazilian phone number
            if (strlen($phone) >= 10) {
                $ddd = substr($phone, 0, 2);
                $number = substr($phone, 2);

                if (strlen($number) == 8) {
                    $formatted = "($ddd) " . substr($number, 0, 4) . "-" . substr($number, 4);
                } else {
                    $formatted = "($ddd) " . substr($number, 0, 5) . "-" . substr($number, 5);
                }

                $this->merge(['phone' => $formatted]);
            }
        }
    }
}
