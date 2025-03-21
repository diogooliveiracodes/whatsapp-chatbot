<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ChatSessionValidator
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
            'customer_id' => 'required|integer|exists:customers,id',
            'user_id' => 'required|integer|exists:users,id',
            'closed_by' => 'nullable|integer|exists:users,id',
            'closed_at' => 'nullable|date',
            'active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
