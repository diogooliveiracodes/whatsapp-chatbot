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
            'type' => 'required|in:1,2', // 1 - Individual, 2 - Company
            'name' => 'required|string|max:120',
            'document_number' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'zip_code' => 'nullable|string|max:10',
            'state' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'prospect_origin' => 'nullable|string|max:255',
        ];
    }
}
