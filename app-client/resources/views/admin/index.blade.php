<x-admin-layout>
    <x-global.header>
        Dashboard Administrativo
    </x-global.header>

    <div class="py-4 sm:py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Period Filter -->
            <div class="mb-6">
                <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-lg sm:text-xl font-semibold text-white truncate">Filtro de Período</h2>
                                <p class="text-gray-400 text-sm truncate">Selecione o período para visualizar os relatórios</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <form method="GET" action="{{ route('admin.index') }}" class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <label for="period" class="block text-sm font-medium text-gray-300 mb-2">
                                    Período
                                </label>
                                <select
                                    name="period"
                                    id="period"
                                    onchange="this.form.submit()"
                                    class="w-full rounded-lg border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3"
                                >
                                    <option value="1_month" {{ $metrics['period'] === '1_month' ? 'selected' : '' }}>Último mês</option>
                                    <option value="6_months" {{ $metrics['period'] === '6_months' ? 'selected' : '' }}>Últimos 6 meses</option>
                                    <option value="1_year" {{ $metrics['period'] === '1_year' ? 'selected' : '' }}>Último ano</option>
                                    <option value="all_time" {{ $metrics['period'] === 'all_time' ? 'selected' : '' }}>Desde o início</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 gap-6 lg:gap-8">
                <!-- Companies Metrics -->
                <div class="w-full">
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-lg sm:text-xl font-semibold text-white truncate">Empresas</h2>
                                    <p class="text-gray-400 text-sm truncate">Relatórios de empresas cadastradas</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Total de Empresas</div>
                                    <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['companies']['total'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Empresas Ativas</div>
                                    <div class="text-2xl font-semibold text-green-400 mt-1">{{ $metrics['kpis']['companies']['active'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Empresas Inativas</div>
                                    <div class="text-2xl font-semibold text-red-400 mt-1">{{ $metrics['kpis']['companies']['inactive'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Novas no Período</div>
                                    <div class="text-2xl font-semibold text-blue-400 mt-1">{{ $metrics['kpis']['companies']['new_in_period'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Metrics -->
                <div class="w-full">
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-lg sm:text-xl font-semibold text-white truncate">Usuários</h2>
                                    <p class="text-gray-400 text-sm truncate">Relatórios de usuários do sistema</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Total de Usuários</div>
                                    <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['users']['total'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Usuários Ativos</div>
                                    <div class="text-2xl font-semibold text-green-400 mt-1">{{ $metrics['kpis']['users']['active'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Usuários Inativos</div>
                                    <div class="text-2xl font-semibold text-red-400 mt-1">{{ $metrics['kpis']['users']['inactive'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Novos no Período</div>
                                    <div class="text-2xl font-semibold text-blue-400 mt-1">{{ $metrics['kpis']['users']['new_in_period'] }}</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Administradores</div>
                                    <div class="text-xl font-semibold text-purple-400 mt-1">{{ $metrics['kpis']['users']['by_role']['admins'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Proprietários</div>
                                    <div class="text-xl font-semibold text-yellow-400 mt-1">{{ $metrics['kpis']['users']['by_role']['owners'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Funcionários</div>
                                    <div class="text-xl font-semibold text-blue-400 mt-1">{{ $metrics['kpis']['users']['by_role']['employees'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedules Metrics -->
                <div class="w-full">
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 19h14" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-lg sm:text-xl font-semibold text-white truncate">Agendamentos</h2>
                                    <p class="text-gray-400 text-sm truncate">Relatórios de agendamentos</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Total de Agendamentos</div>
                                    <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['schedules']['total'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Agendamentos Ativos</div>
                                    <div class="text-2xl font-semibold text-green-400 mt-1">{{ $metrics['kpis']['schedules']['active'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Agendamentos Inativos</div>
                                    <div class="text-2xl font-semibold text-red-400 mt-1">{{ $metrics['kpis']['schedules']['inactive'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Novos no Período</div>
                                    <div class="text-2xl font-semibold text-blue-400 mt-1">{{ $metrics['kpis']['schedules']['new_in_period'] }}</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Pendentes</div>
                                    <div class="text-xl font-semibold text-yellow-400 mt-1">{{ $metrics['kpis']['schedules']['by_status']['pending'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Confirmados</div>
                                    <div class="text-xl font-semibold text-green-400 mt-1">{{ $metrics['kpis']['schedules']['by_status']['confirmed'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Cancelados</div>
                                    <div class="text-xl font-semibold text-red-400 mt-1">{{ $metrics['kpis']['schedules']['by_status']['cancelled'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Concluídos</div>
                                    <div class="text-xl font-semibold text-blue-400 mt-1">{{ $metrics['kpis']['schedules']['by_status']['completed'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Metrics -->
                <div class="w-full">
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-yellow-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-lg sm:text-xl font-semibold text-white truncate">Pagamentos</h2>
                                    <p class="text-gray-400 text-sm truncate">Relatórios de transações de pagamento</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Total de Pagamentos</div>
                                    <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['payments']['total'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Novos no Período</div>
                                    <div class="text-2xl font-semibold text-blue-400 mt-1">{{ $metrics['kpis']['payments']['new_in_period'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Valor Total</div>
                                    <div class="text-2xl font-semibold text-white mt-1">
                                        <span id="payment-total-amount"></span>
                                    </div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Valor Pago</div>
                                    <div class="text-2xl font-semibold text-green-400 mt-1">
                                        <span id="payment-paid-amount"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Pendentes</div>
                                    <div class="text-xl font-semibold text-yellow-400 mt-1">{{ $metrics['kpis']['payments']['by_status']['pending'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Pagos</div>
                                    <div class="text-xl font-semibold text-green-400 mt-1">{{ $metrics['kpis']['payments']['by_status']['paid'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Rejeitados</div>
                                    <div class="text-xl font-semibold text-red-400 mt-1">{{ $metrics['kpis']['payments']['by_status']['rejected'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Expirados</div>
                                    <div class="text-xl font-semibold text-orange-400 mt-1">{{ $metrics['kpis']['payments']['by_status']['expired'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Vencidos</div>
                                    <div class="text-xl font-semibold text-red-500 mt-1">{{ $metrics['kpis']['payments']['by_status']['overdue'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscriptions Metrics -->
                <div class="w-full">
                    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 sm:px-6 py-4 border-b border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-lg sm:text-xl font-semibold text-white truncate">Assinaturas</h2>
                                    <p class="text-gray-400 text-sm truncate">Relatórios de planos de assinatura</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Total de Assinaturas</div>
                                    <div class="text-2xl font-semibold text-white mt-1">{{ $metrics['kpis']['subscriptions']['total'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Novas no Período</div>
                                    <div class="text-2xl font-semibold text-blue-400 mt-1">{{ $metrics['kpis']['subscriptions']['new_in_period'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Receita Total</div>
                                    <div class="text-2xl font-semibold text-green-400 mt-1">
                                        <span id="subscription-revenue"></span>
                                    </div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Assinaturas Ativas</div>
                                    <div class="text-2xl font-semibold text-green-400 mt-1">{{ $metrics['kpis']['subscriptions']['by_status']['active'] }}</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Ativas</div>
                                    <div class="text-xl font-semibold text-green-400 mt-1">{{ $metrics['kpis']['subscriptions']['by_status']['active'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Inativas</div>
                                    <div class="text-xl font-semibold text-red-400 mt-1">{{ $metrics['kpis']['subscriptions']['by_status']['inactive'] }}</div>
                                </div>
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="text-gray-400 text-xs">Canceladas</div>
                                    <div class="text-xl font-semibold text-orange-400 mt-1">{{ $metrics['kpis']['subscriptions']['by_status']['cancelled'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
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

                // Update currency values
                document.getElementById('payment-total-amount').textContent = formatCurrency(metrics.kpis.payments.amounts.total);
                document.getElementById('payment-paid-amount').textContent = formatCurrency(metrics.kpis.payments.amounts.paid);
                document.getElementById('subscription-revenue').textContent = formatCurrency(metrics.kpis.subscriptions.revenue);
            })();
        </script>
    @endpush
</x-admin-layout>
