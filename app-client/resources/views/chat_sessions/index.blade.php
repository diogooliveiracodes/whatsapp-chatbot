<x-app-layout>
    <x-global.header>
        {{ __('Conversas') }}
    </x-global.header>

    <div class="flex h-[calc(100vh-4rem)]">
        <!-- Sidebar with conversation list -->
        <div class="w-80 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Conversas Ativas</h2>

                <!-- Search input -->
                <form action="{{ route('chatSessions.index') }}" method="GET" class="mb-4">
                    <div class="relative">
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Buscar por nome ou telefone..."
                               class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring focus:ring-blue-300">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @if($search)
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <a href="{{ route('chatSessions.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </form>

                <div class="space-y-2">
                    @foreach($chatSessions as $chatSession)
                        <a href="{{ route('chatSessions.show', $chatSession->channel) }}"
                           class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ request()->routeIs('chatSessions.show', $chatSession->channel) ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                        {{ substr($chatSession->customer->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $chatSession->customer->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ $chatSession->customer->phone }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $chatSession->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main chat content area -->
        <div class="flex-1 bg-gray-50 dark:bg-gray-900">
            @if(request()->routeIs('chatSessions.show', '*'))
                @yield('chat-content')
            @else
                <div class="h-full flex items-center justify-center">
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Selecione uma conversa</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Escolha uma conversa da lista para começar a interagir</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
