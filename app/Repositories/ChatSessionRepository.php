<?php

namespace App\Repositories;

use App\Models\ChatSession;

class ChatSessionRepository
{
    protected ChatSession $model;

    public function __construct(
        ChatSession $model
    ) {
        $this->model = $model;
    }

    public function store(array $data): ChatSession
    {
        return $this->model->create([
            'company_id' => $data['company_id'],
            'unit_id' => $data['unit_id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'active' => true,
        ]);
    }

    public function findActiveChatSession(array $data): ChatSession|null
    {
        return $this->model->where('active', true)
            ->where('customer_id', $data['customer_id'])
            ->where('user_id', $data['user_id'])
            ->first();
    }
}
