@props(['company'])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            {{ __('dashboard.schedule_link.title') }}
        </h3>

        <div class="space-y-4">
            <div>
                <label for="schedule-link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('dashboard.schedule_link.label') }}
                </label>
                <div class="flex">
                    <input
                        type="text"
                        id="schedule-link"
                        value="{{ route('schedule-link.index', ['company' => $company->id]) }}"
                        readonly
                        class="flex-1 rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                    <button
                        type="button"
                        onclick="copyScheduleLink()"
                        class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 dark:border-gray-600 rounded-r-md bg-gray-50 dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('dashboard.schedule_link.copy_button') }}
                    </button>
                </div>
            </div>

            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>{{ __('dashboard.schedule_link.description') }}</p>
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
            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            ${'{{ __("dashboard.schedule_link.copied_message") }}'}
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
            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            ${'{{ __("dashboard.schedule_link.error_message") }}'}
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
