<x-app-layout>
    <x-global.header>
        {{ __('customers.inactive') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <!-- Botão voltar e descrição -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <a href="{{ route('customers.index') }}"
                               class="inline-flex items-center px-3 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 dark:focus:bg-gray-600 active:bg-gray-800 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                {{ __('customers.back') }}
                            </a>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('customers.inactive_description') }}
                        </p>
                    </div>

                    <!-- Filtro por período -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <div class="flex-1">
                            <label for="days-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('customers.filter_by_period') }}
                            </label>
                            <select
                                id="days-filter"
                                name="days"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                onchange="filterByDays(this.value)"
                            >
                                @foreach($periodOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $days == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tabela para desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.phone') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.last_schedule') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.days_without_schedule') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($customers as $customer)
                                @php
                                    $lastSchedule = $customer->schedules->first();
                                    $lastScheduleDate = $lastSchedule ? $lastSchedule->schedule_date->format('d/m/Y') : null;
                                    $daysSinceLastSchedule = $lastSchedule ? now()->diffInDays($lastSchedule->schedule_date) : null;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ \App\Helpers\PhoneHelper::format($customer->phone) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $lastScheduleDate ?? __('customers.no_schedule') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        @if($daysSinceLastSchedule !== null)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ $daysSinceLastSchedule }} {{ __('customers.days') }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        <x-automated_messages.customer-message-button
                                            :customer="$customer"
                                            :unit="auth()->user()->unit"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards para mobile -->
                    <div class="md:hidden space-y-4">
                        @foreach($customers as $customer)
                            @php
                                $lastSchedule = $customer->schedules->first();
                                $lastScheduleDate = $lastSchedule ? $lastSchedule->schedule_date->format('d/m/Y') : null;
                                $daysSinceLastSchedule = $lastSchedule ? now()->diffInDays($lastSchedule->schedule_date) : null;
                            @endphp
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $customer->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ \App\Helpers\PhoneHelper::format($customer->phone) }}</p>
                                    </div>
                                    @if($daysSinceLastSchedule !== null)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            {{ $daysSinceLastSchedule }} {{ __('customers.days') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    <p>{{ __('customers.last_schedule') }}: {{ $lastScheduleDate ?? __('customers.no_schedule') }}</p>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <x-automated_messages.customer-message-button-mobile
                                        :customer="$customer"
                                        :unit="auth()->user()->unit"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Mensagem quando não há clientes -->
                    @if($customers->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-lg font-medium">{{ __('customers.no_inactive_customers') }}</p>
                                <p class="mt-2">{{ __('customers.no_inactive_customers_description') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-automated_messages.customer-message-modal />
    <x-scroll-to-top />

    <script>
        function filterByDays(days) {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('days', days);
            window.location.href = currentUrl.toString();
        }
    </script>
</x-app-layout>
