<x-app-layout>
    <x-global.header>
        {{ __('dashboard.title') }}
    </x-global.header>

    <div class="py-4 sm:py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 gap-6 lg:gap-8">

                <!-- Schedule Link Section -->
                <div class="w-full">
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-lg sm:text-xl font-semibold text-white truncate">{{ __('dashboard.schedule_link.title') }}</h2>
                                    <p class="text-gray-400 text-sm truncate">Compartilhe com seus clientes</p>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                            <div class="bg-gray-700/50 rounded-lg p-4">
                                <label for="schedule-link" class="block text-sm font-medium text-gray-300 mb-3">
                                    {{ __('dashboard.schedule_link.label') }}
                                </label>
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-0">
                                    <input
                                        type="text"
                                        id="schedule-link"
                                        value="{{ route('schedule-link.index', ['company' => auth()->user()->company->id]) }}"
                                        readonly
                                        class="flex-1 rounded-lg sm:rounded-l-lg sm:rounded-r-none border-gray-600 bg-gray-700 text-white shadow-sm focus:border-green-500 focus:ring-green-500 text-sm px-4 py-3"
                                    >
                                    <button
                                        type="button"
                                        onclick="copyScheduleLink()"
                                        class="inline-flex items-center justify-center px-4 sm:px-6 py-3 border border-gray-600 sm:border-l-0 rounded-lg sm:rounded-l-none sm:rounded-r-lg bg-gray-700 text-sm font-medium text-gray-300 hover:bg-gray-600 focus:z-10 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200 min-h-[44px]"
                                    >
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">{{ __('dashboard.schedule_link.copy_button') }}</span>
                                        <span class="sm:hidden">Copiar</span>
                                    </button>
                                </div>
                            </div>

                            <div class="bg-blue-900/20 border border-blue-800/50 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-medium text-blue-300 mb-1">Como usar</h4>
                                        <p class="text-sm text-blue-200">{{ __('dashboard.schedule_link.description') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    async function copyScheduleLink() {
        const linkInput = document.getElementById('schedule-link');
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        const textToCopy = linkInput.value;

        try {
            // Tenta usar a API moderna de clipboard primeiro
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(textToCopy);
            } else {
                // Fallback para navegadores mais antigos
                linkInput.select();
                linkInput.setSelectionRange(0, 99999); // Para dispositivos móveis
                document.execCommand('copy');
            }

            // Feedback visual de sucesso
            button.innerHTML = `
                <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="hidden sm:inline">${'{{ __("dashboard.schedule_link.copied_message") }}'}</span>
                <span class="sm:hidden">Copiado!</span>
            `;
            button.classList.add('text-green-600', 'dark:text-green-400');

            // Restaura o texto original após 2 segundos
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('text-green-600', 'dark:text-green-400');
            }, 2000);

        } catch (err) {
            console.error('Erro ao copiar: ', err);

            // Feedback visual de erro
            button.innerHTML = `
                <svg class="w-4 h-4 mr-2 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="hidden sm:inline">${'{{ __("dashboard.schedule_link.error_message") }}'}</span>
                <span class="sm:hidden">Erro!</span>
            `;
            button.classList.add('text-red-600', 'dark:text-red-400');

            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('text-red-600', 'dark:text-red-400');
            }, 2000);
        }

        // Remove a seleção
        linkInput.blur();
    }
    </script>
</x-app-layout>
