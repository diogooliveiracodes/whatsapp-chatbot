<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\ChatSession;
use App\Http\Requests\StoreChatSessionRequest;
use App\Http\Requests\UpdateChatSessionRequest;
use App\Repositories\ChatSessionRepository;
use App\Repositories\MessageRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class ChatSessionController extends Controller
{
    protected ChatSessionRepository $chatSessionRepository;
    protected MessageRepository $messageRepository;

    public function __construct(ChatSessionRepository $chatSessionRepository, MessageRepository $messageRepository)
    {
        $this->chatSessionRepository = $chatSessionRepository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('chat_sessions.index', [
            'chatSessions' => $this->chatSessionRepository->getChatSessionList()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatSessionRequest $request)
    {

    }

    /**
     * @param StoreMessageRequest $request
     * @return Application|Redirector|RedirectResponse
     */
    public function storeMessage(StoreMessageRequest $request): Application|Redirector|RedirectResponse
    {
        try {
            $chatSession = $this->chatSessionRepository->findById($request->input('chat_session_id'));
            $this->messageRepository->store([
                'company_id' => $chatSession->company_id,
                'unit_id' => $chatSession->unit_id,
                'customer_id' => $request->input('customer_id') ?? null,
                'user_id' => $request->input('user_id') ?? null,
                'chat_session_id' => $chatSession->id,
                'content' => $request->input('content'),
                'type' => 'text'
            ]);

            return redirect(route('chatSessions.show', ['channel' => $chatSession->channel]));
        } catch (\Exception $e) {

            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $channel): View
    {
        $pusher = config('broadcasting.connections.pusher.key');
        $cluster = config('broadcasting.connections.pusher.options.cluster');
        $messages = $this->chatSessionRepository->getMessagesByChatSessionChannel($channel);
        $chatSession = $this->chatSessionRepository->findByChannel($channel);

        return view('chat_sessions.show', compact(['channel', 'pusher', 'cluster', 'messages', 'chatSession']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatSession $chatSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatSessionRequest $request, ChatSession $chatSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatSession $chatSession)
    {
        //
    }
}
