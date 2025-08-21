<x-app-layout>
    <x-global.header>
        {{ __('dashboard.title') }}
    </x-global.header>

    <div class="py-4 sm:py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 gap-6 lg:gap-8">

                @if(isset($metrics))
                    <!-- Owner Insights Header -->
                    <div class="w-full">
                        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h2 class="text-lg sm:text-xl font-semibold text-white truncate">{{ __('dashboard.owner.insights.title') }}</h2>
                                        <p class="text-gray-400 text-sm truncate">{{ __('dashboard.owner.insights.description') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 sm:p-6">
                                <!-- Owner KPI Cards -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <!-- Schedules Today -->
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <div class="text-gray-400 text-xs">{{ __('dashboard.owner.kpis.schedules_today') }}</div>
                                        <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['schedules']['day'] }}</div>
                                    </div>
                                    <!-- Schedules Month -->
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <div class="text-gray-400 text-xs">{{ __('dashboard.owner.kpis.schedules_month') }}</div>
                                        <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['schedules']['month'] }}</div>
                                    </div>
                                    <!-- Schedules Year -->
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <div class="text-gray-400 text-xs">{{ __('dashboard.owner.kpis.schedules_year') }}</div>
                                        <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['schedules']['year'] }}</div>
                                    </div>
                                    <!-- Payments Received -->
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <div class="text-gray-400 text-xs">{{ __('dashboard.owner.kpis.payments_received') }}</div>
                                        <div class="text-2xl font-semibold text-white mt-1">
                                            <span id="kpi-payments-received"></span>
                                        </div>
                                    </div>
                                    <!-- Cancellations Today -->
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <div class="text-gray-400 text-xs">{{ __('dashboard.owner.kpis.cancellations_today') }}</div>
                                        <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['cancellations']['day'] }}</div>
                                    </div>
                                    <!-- Cancellations Month -->
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <div class="text-gray-400 text-xs">{{ __('dashboard.owner.kpis.cancellations_month') }}</div>
                                        <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['cancellations']['month'] }}</div>
                                    </div>
                                    <!-- Cancellations Year -->
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <div class="text-gray-400 text-xs">{{ __('dashboard.owner.kpis.cancellations_year') }}</div>
                                        <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['cancellations']['year'] }}</div>
                                    </div>
                                    <!-- Payments Receivable -->
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <div class="text-gray-400 text-xs">{{ __('dashboard.owner.kpis.payments_receivable') }}</div>
                                        <div class="text-2xl font-semibold text-white mt-1">
                                            <span id="kpi-payments-receivable"></span>
                                        </div>
                                    </div>
                                    <!-- Pending Schedules -->
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <div class="text-gray-400 text-xs">{{ __('dashboard.owner.kpis.schedules_pending') }}</div>
                                        <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['schedules']['pending'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Owner Charts -->
                    <div class="w-full">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                            <!-- Schedules by Month -->
                            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h2 class="text-lg sm:text-xl font-semibold text-white truncate">{{ __('dashboard.owner.charts.schedules_by_month') }}</h2>
                                            <p class="text-gray-400 text-sm truncate">{{ __('dashboard.owner.insights.description') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 sm:p-6">
                                    <canvas id="chartSchedulesByMonth" height="140"></canvas>
                                </div>
                            </div>
                            <!-- Payments by Month -->
                            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-4.418 0-8 1.79-8 4s3.582 4 8 4 8-1.79 8-4-3.582-4-8-4z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h2 class="text-lg sm:text-xl font-semibold text-white truncate">{{ __('dashboard.owner.charts.payments_by_month') }}</h2>
                                            <p class="text-gray-400 text-sm truncate">{{ __('dashboard.owner.insights.description') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 sm:p-6">
                                    <canvas id="chartPaymentsByMonth" height="140"></canvas>
                                </div>
                            </div>
                            <!-- Schedules by Weekday (last 30 days) -->
                            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 19h14" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h2 class="text-lg sm:text-xl font-semibold text-white truncate">{{ __('dashboard.owner.charts.schedules_by_weekday_30d') }}</h2>
                                            <p class="text-gray-400 text-sm truncate">{{ __('dashboard.owner.insights.description') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 sm:p-6">
                                    <canvas id="chartSchedulesByWeekday30" height="140"></canvas>
                                </div>
                            </div>
                            <!-- Cancellations by Month -->
                            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h2 class="text-lg sm:text-xl font-semibold text-white truncate">{{ __('dashboard.owner.charts.cancellations_by_month') }}</h2>
                                            <p class="text-gray-400 text-sm truncate">{{ __('dashboard.owner.insights.description') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 sm:p-6">
                                    <canvas id="chartCancellationsByMonth" height="140"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

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
                                        <h4 class="text-sm font-medium text-white mb-1">Como usar</h4>
                                        <p class="text-sm text-gray-300">{{ __('dashboard.schedule_link.description') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if(isset($metrics))
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                (function() {
                    const metrics = @json($metrics);

                    const formatCurrency = (value) => {
                        const locale = '{{ app()->getLocale() }}';
                        const currency = (locale === 'pt_BR') ? 'BRL' : 'USD';
                        try {
                            return new Intl.NumberFormat(locale.replace('_','-'), { style: 'currency', currency }).format(value || 0);
                        } catch (e) {
                            return (currency === 'BRL') ? `R$ ${Number(value || 0).toFixed(2)}` : `$ ${Number(value || 0).toFixed(2)}`;
                        }
                    };

                    // Update KPI currency values
                    document.getElementById('kpi-payments-received').textContent = formatCurrency(metrics.kpis.payments.received_total);
                    document.getElementById('kpi-payments-receivable').textContent = formatCurrency(metrics.kpis.payments.receivable_total);

                    const baseOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { ticks: { color: '#9CA3AF' }, grid: { color: 'rgba(55,65,81,0.3)' } },
                            y: { ticks: { color: '#9CA3AF' }, grid: { color: 'rgba(55,65,81,0.3)' } }
                        },
                        plugins: {
                            legend: { labels: { color: '#E5E7EB' } }
                        }
                    };

                    // Schedules by Month
                    new Chart(document.getElementById('chartSchedulesByMonth'), {
                        type: 'line',
                        data: {
                            labels: metrics.charts.schedules_by_month.labels,
                            datasets: [{
                                label: 'Agendamentos',
                                data: metrics.charts.schedules_by_month.data,
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: baseOptions
                    });

                    // Payments by Month
                    new Chart(document.getElementById('chartPaymentsByMonth'), {
                        type: 'bar',
                        data: {
                            labels: metrics.charts.payments_by_month.labels,
                            datasets: [
                                {
                                    label: '{{ __('dashboard.owner.kpis.payments_received') }}',
                                    data: metrics.charts.payments_by_month.received,
                                    backgroundColor: 'rgba(59, 130, 246, 0.6)'
                                },
                                {
                                    label: '{{ __('dashboard.owner.kpis.payments_receivable') }}',
                                    data: metrics.charts.payments_by_month.receivable,
                                    backgroundColor: 'rgba(234, 179, 8, 0.6)'
                                }
                            ]
                        },
                        options: {
                            ...baseOptions,
                            plugins: {
                                legend: { labels: { color: '#E5E7EB' } },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            const label = ctx.dataset.label || '';
                                            const v = ctx.parsed.y;
                                            return `${label}: ${formatCurrency(v)}`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                ...baseOptions.scales,
                                y: {
                                    ...baseOptions.scales.y,
                                    ticks: {
                                        color: '#9CA3AF',
                                        callback: function(value) { return formatCurrency(value); }
                                    }
                                }
                            }
                        }
                    });

                    // Schedules by Weekday (last 30 days) Monday..Sunday
                    const weekdayLabels = (function() {
                        const locale = '{{ app()->getLocale() }}'.replace('_','-');
                        const base = new Date(2023,0,2); // Monday
                        const fmt = new Intl.DateTimeFormat(locale, { weekday: 'short' });
                        const arr = [];
                        for (let i=0;i<7;i++){ const d = new Date(base); d.setDate(base.getDate()+i); arr.push(fmt.format(d)); }
                        return arr;
                    })();

                    new Chart(document.getElementById('chartSchedulesByWeekday30'), {
                        type: 'bar',
                        data: {
                            labels: weekdayLabels,
                            datasets: [{
                                label: 'Agendamentos',
                                data: metrics.charts.schedules_by_weekday_30d.data,
                                backgroundColor: 'rgba(99, 102, 241, 0.6)'
                            }]
                        },
                        options: baseOptions
                    });

                    // Cancellations by Month
                    new Chart(document.getElementById('chartCancellationsByMonth'), {
                        type: 'bar',
                        data: {
                            labels: metrics.charts.cancellations_by_month.labels,
                            datasets: [{
                                label: 'Cancelamentos',
                                data: metrics.charts.cancellations_by_month.data,
                                backgroundColor: 'rgba(239, 68, 68, 0.6)'
                            }]
                        },
                        options: baseOptions
                    });
                })();
            </script>
        @endpush
    @endif

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
