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
            'phone' => 'nullable|string|max:20|regex:/^\(\d{2}\) \d{4,5}-\d{4}$/',
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
            'phone.regex' => __('customers.validation.phone.format'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean the phone number to only digits before validation
        if ($this->has('phone') && $this->phone) {
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
