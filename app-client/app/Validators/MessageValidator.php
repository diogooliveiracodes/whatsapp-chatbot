<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MessageValidator
{
    /**
     * @param array $data
     * @return bool|array
     * @throws ValidationException
     */
    public static function validate(array $data): bool|array
    {
        $validator = Validator::make($data, [
            'company_id' => 'required|integer|exists:companies,id',
            'unit_id' => 'nullable|integer|exists:units,id',
            'customer_id' => 'required_without:user_id|nullable|integer|exists:customers,id',
            'user_id' => 'required_without:customer_id|nullable|integer|exists:users,id',
            'chat_session_id' => 'required|integer|exists:chat_sessions,id',
            'active' => 'nullable|boolean',
            'content' => 'required|string',
            'type' => 'nullable|string',
        ])->after(function ($validator) use ($data) {
            if (empty($data['customer_id']) === empty($data['user_id'])) {
                $validator->errors()->add('customer_id', 'Fill in only one: customer_id or user_id');
                $validator->errors()->add('user_id', 'Fill in only one: customer_id or user_id');
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
