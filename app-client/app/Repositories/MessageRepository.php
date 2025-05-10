<?php

namespace App\Repositories;

use App\Models\Message;
use App\Validators\MessageValidator;

class MessageRepository
{
    protected Message $model;

    public function __construct(Message $model)
    {
        $this->model = $model;
    }

    public function store(array $data): Message
    {
        return $this->model->create([
            'company_id' => $data['company_id'],
            'unit_id' => $data['unit_id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'chat_session_id' => $data['chat_session_id'],
            'active' => true,
            'content' => $data['content'],
            'type' => $data['type'],
        ]);
    }
}
