<?php

namespace App\Http\Requests;

use App\Enum\UserRoleEnum;
use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)],
            'unit_id' => ['required', 'exists:units,id', function ($attribute, $value, $fail) {
                $unit = Unit::find($value);
                if (!$unit || $unit->company_id !== \Illuminate\Support\Facades\Auth::user()->company_id) {
                    $fail('The selected unit is invalid.');
                }
            }],
            'user_role_id' => ['required', Rule::in([UserRoleEnum::OWNER, UserRoleEnum::EMPLOYEE])],
            'active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => __('user.validation.name.required'),
            'name.string' => __('user.validation.name.string'),
            'name.max' => __('user.validation.name.max'),
            'email.required' => __('user.validation.email.required'),
            'email.string' => __('user.validation.email.string'),
            'email.email' => __('user.validation.email.email'),
            'email.max' => __('user.validation.email.max'),
            'email.unique' => __('user.validation.email.unique'),
            'unit_id.required' => __('user.validation.unit_id.required'),
            'unit_id.exists' => __('user.validation.unit_id.exists'),
            'user_role_id.required' => __('user.validation.user_role_id.required'),
            'user_role_id.in' => __('user.validation.user_role_id.in'),
            'active.boolean' => __('user.validation.active.boolean'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => __('user.attributes.name'),
            'email' => __('user.attributes.email'),
            'unit_id' => __('user.attributes.unit_id'),
            'user_role_id' => __('user.attributes.user_role_id'),
            'active' => __('user.attributes.active'),
        ];
    }
}
