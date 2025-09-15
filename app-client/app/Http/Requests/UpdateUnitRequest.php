<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateUnitRequest extends FormRequest
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
                Rule::unique('units')->where(function ($query) {
                    return $query->where('company_id', Auth::user()->company_id);
                })->ignore($this->route('unit'))
            ],
            'active' => ['nullable', 'boolean'],
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
            'name.required' => __('validation.required', ['attribute' => __('units.attributes.name')]),
            'name.string' => __('validation.string', ['attribute' => __('units.attributes.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('units.attributes.name'), 'max' => 255]),
            'active.boolean' => __('validation.boolean', ['attribute' => __('units.attributes.active')]),
            'image_name.string' => __('validation.string', ['attribute' => __('units.attributes.image_name')]),
            'image_name.max' => __('validation.max.string', ['attribute' => __('units.attributes.image_name'), 'max' => 255]),
            'image_path.string' => __('validation.string', ['attribute' => __('units.attributes.image_path')]),
            'image_path.max' => __('validation.max.string', ['attribute' => __('units.attributes.image_path'), 'max' => 2048]),
        ];
    }

}
