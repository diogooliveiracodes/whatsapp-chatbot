@extends('chat_sessions.index')

@section('chat-content')
    <div class="flex flex-col h-full">
        <!-- Chat header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                    {{ substr($chatSession->customer_name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $chatSession->customer_name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $chatSession->phone }}</p>
                </div>
            </div>
        </div>

        <!-- Chat messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
            @foreach($messages as $message)
                <div class="flex @if($message->customer_id) justify-start @else justify-end @endif">
                    <div class="max-w-[70%]">
                        <div class="flex @if($message->customer_id) justify-start @else justify-end @endif">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                {{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') }} - {{$message->customer_id ? $message->customer_name : $message->user_name}}
                            </p>
                        </div>
                        <div class="p-3 rounded-lg @if($message->customer_id) bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 @else bg-blue-500 text-white @endif">
                            <p class="text-sm">{{ $message->content }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message input -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <form action="{{ route('chatSessions.storeMessage') }}" method="POST" class="flex items-center space-x-2" id="formContainer">
                @csrf
                <input type="hidden" name="chat_session_id" value="{{ $messages->first()->chat_session_id }}">
                <input type="text" name="content" placeholder="Digite sua mensagem..." required
                       class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring focus:ring-blue-300">
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                    Enviar
                </button>
            </form>
        </div>
    </div>

    <script>
        // Scroll to bottom on load
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Pusher setup
        let pusherId = '{{$pusher}}';
        let cluster = '{{$cluster}}';
        let channelId = '{{$channel}}';

        Pusher.logToConsole = true;

        var pusher = new Pusher(pusherId, {
            cluster: cluster
        });

        var channel = pusher.subscribe(channelId);
        channel.bind('my-event', function (data) {
            window.location.reload();
        });
    </script>
@endsection
