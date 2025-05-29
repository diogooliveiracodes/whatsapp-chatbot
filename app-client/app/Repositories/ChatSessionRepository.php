<?php

namespace App\Repositories;

use App\Models\ChatSession;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatSessionRepository
{
    protected ChatSession $model;

    public function __construct(
        ChatSession $model
    )
    {
        $this->model = $model;
    }

    public function findById(int $id): ?ChatSession
    {
        return $this->model->where('id', $id)->first();
    }

    public function findByChannel(string $channel): ?ChatSession
    {
        return $this->model
            ->join('customers', 'customers.id', '=', 'chat_sessions.customer_id')
            ->where('chat_sessions.channel', $channel)
            ->select([
                'chat_sessions.id',
                'chat_sessions.channel',
                'customers.name as customer_name',
            ])
            ->first();
    }

    public function store(array $data): ChatSession
    {
        return $this->model->create([
            'company_id' => $data['company_id'],
            'unit_id' => $data['unit_id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'active' => true,
            'channel' => Str::uuid()
        ]);
    }

    public function findActiveChatSession(array $data): ChatSession|null
    {
        return $this->model->where('active', true)
            ->where('customer_id', $data['customer_id'])
            ->where('user_id', $data['user_id'])
            ->first();
    }

    /**
     * Retrieve active chat sessions filtered by the authenticated user's ID
     * @return object
     */
    public function getChatSessionList(?string $search = null): object
    {
        $query = $this->model
            ->join('customers', 'customers.id', '=', 'chat_sessions.customer_id')
            ->where('chat_sessions.active', true)
            ->where('chat_sessions.user_id', Auth::user()->id)
            ->orderByDesc('chat_sessions.created_at')
            ->select([
                'chat_sessions.id',
                'chat_sessions.active',
                'chat_sessions.customer_id',
                'chat_sessions.unit_id',
                'chat_sessions.channel',
                'chat_sessions.created_at',
                'chat_sessions.updated_at',
                'customers.name',
                'customers.phone',
            ]);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customers.name', 'like', "%{$search}%")
                  ->orWhere('customers.phone', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    public function getMessagesByChatSessionChannel(string $channel): Collection
    {
        return $this->model
            ->join('messages', 'messages.chat_session_id', '=', 'chat_sessions.id')
            ->join('customers', 'customers.id', '=', 'chat_sessions.customer_id')
            ->join('users', 'users.id', '=', 'chat_sessions.user_id')
            ->where('chat_sessions.channel', '=', $channel)
            ->select([
                'chat_sessions.id as chat_session_id',
                'chat_sessions.active',
                'chat_sessions.customer_id',
                'chat_sessions.unit_id',
                'chat_sessions.channel',
                'chat_sessions.created_at',
                'chat_sessions.user_id',
                'messages.*',
                'customers.name as customer_name',
                'customers.phone',
                'users.name as user_name',
            ])
            ->get();
    }
}
