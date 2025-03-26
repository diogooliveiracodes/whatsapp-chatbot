<x-app-layout>
      <x-header>
        {{ __('Conversa') }}
    </x-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mx-auto p-4 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-md">
                <div class="mb-6 font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                    {{$chatSession->customer_name}}
                </div>
                <div class="space-y-4 overflow-y-auto">
                    @foreach($messages as $message)
                        <div class="flex @if($message->customer_id) justify-start @else justify-end @endif">
                            <div>
                                <div class="flex @if($message->customer_id) justify-start @else justify-end @endif">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                        {{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') }} - {{$message->customer_id ? $message->customer_name : $message->user_name}}
                                    </p>
                                </div>
                                <div class="p-3 rounded-lg text-white bg-gray-700">
                                    <p class="text-sm">{{ $message->content }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Formulário de envio -->
                <form action="{{ route('chatSessions.storeMessage') }}" method="POST" class="mt-6" id="formContainer">
                    @csrf
                    <div class="flex items-center space-x-2">
                        <input type="hidden" name="chat_session_id" value="{{ $messages->first()->chat_session_id }}">
                        <input type="text" name="content" placeholder="Digite sua mensagem..." required
                               class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring focus:ring-blue-300">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                            Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });

        let pusherId = '{{$pusher}}';
        let cluster = '{{$cluster}}';
        let channelId = '{{$channel}}';

        Pusher.logToConsole = true;

        var pusher = new Pusher(pusherId, {
            cluster: cluster
        });

        var channel = pusher.subscribe(channelId);
        channel.bind('my-event', function (data) {
            // Apenas recarrega a página para ver as novas mensagens
            window.location.reload();
        });
    </script>
</x-app-layout>
