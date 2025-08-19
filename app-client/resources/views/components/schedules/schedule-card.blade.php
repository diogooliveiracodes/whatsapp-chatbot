@props(['schedule'])

@php
    // Obter o fuso horário da unidade do usuário logado
    $userTimezone = auth()->user()->unit->unitSettings->timezone ?? 'UTC';

    // Parse robusto do término no fuso da unidade (aceita 'Y-m-dTH:i:s' com/sem offset)
    $scheduleEndDateTime = \Carbon\Carbon::parse($schedule['end'], $userTimezone);

    // Agora atual no mesmo fuso
    $currentTimeInUserTimezone = now()->setTimezone($userTimezone);

    // Um agendamento só "passou" quando o horário de término já foi ultrapassado
    $isPastSchedule = $currentTimeInUserTimezone->gt($scheduleEndDateTime);
@endphp

<div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
    <div class="p-2">
        <div class="mb-1 flex justify-between space-x-1">
            <span
                class="px-1.5 py-0.5 text-xs font-semibold rounded-full
                @if ($schedule['status'] === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                @elseif($schedule['status'] === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                @elseif($schedule['status'] === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                {{ ucfirst(__('schedules.statuses.' . $schedule['status'])) }}
            </span>
            @if (!$isPastSchedule)
                <div class="flex space-x-2">
                    <a href="{{ route('schedules.edit', array_merge([$schedule['id']], auth()->user()->isOwner() && isset($schedule['unit']['id']) ? ['unit_id' => $schedule['unit']['id']] : [])) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('schedules.destroy', $schedule['id']) }}" method="POST" class="inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            @endif
        </div>
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                {{ $schedule['customer']['name'] }}
            </h3>
        </div>

        <!-- Date and Time -->
        <div class="mb-1 flex items-center space-x-2">
            <svg class="w-3 h-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="text-xs text-gray-600 dark:text-gray-300">
                {{ \Carbon\Carbon::parse($schedule['schedule_date'])->format('d/m/Y') }} às {{ $schedule['start_time'] }}
            </span>
        </div>

        <p class="text-xs text-gray-600 dark:text-gray-300 truncate">
            {{ $schedule['unit_service_type']['name'] }}
        </p>
        @if($schedule['notes'])
            <p class="text-xs text-gray-700 dark:text-gray-400 truncate">
                {{ $schedule['notes'] }}
            </p>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        // Função para inicializar os formulários de exclusão
        function initializeDeleteForms() {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                // Remove event listeners existentes para evitar duplicação
                form.removeEventListener('submit', handleDeleteSubmit);
                form.addEventListener('submit', handleDeleteSubmit);
            });
        }

        // Função para lidar com o submit do formulário de exclusão
        function handleDeleteSubmit(e) {
            e.preventDefault();
            e.stopPropagation();

            if (confirm('{{ __('schedules.messages.confirm_delete') }}')) {
                // Adiciona um indicador visual de carregamento
                const button = e.target.querySelector('button[type="submit"]');
                if (button) {
                    button.disabled = true;
                    button.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                }

                // Submete o formulário
                this.submit();
            }
        }

        // Inicializa quando o DOM estiver pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeDeleteForms);
        } else {
            initializeDeleteForms();
        }

        // Re-inicializa após mudanças dinâmicas no DOM (se necessário)
        document.addEventListener('DOMContentLoaded', function() {
            // Observa mudanças no DOM para re-inicializar se necessário
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        initializeDeleteForms();
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    </script>
@endpush
