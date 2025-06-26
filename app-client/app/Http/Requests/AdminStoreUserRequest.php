<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming admin users are authorized to create users
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_role_id' => 'required|exists:user_roles,id',
            'create_new_company' => 'boolean',
            'create_new_unit' => 'boolean',
            'company_name' => 'nullable|string|max:255',
            'company_document_number' => 'nullable|string|max:20',
            'company_document_type' => 'nullable|integer|between:1,2',
            'unit_name' => 'nullable|string|max:100',
            'company_id' => 'nullable|exists:companies,id',
            'unit_id' => 'nullable|exists:units,id',
            'active' => 'boolean',
        ];
    }
}
