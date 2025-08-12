<x-app-layout>
    <x-global.header>
        {{ __('pages.customers') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <!-- Botões e busca - Layout responsivo -->
                    <div class="flex-shrink-0">
                        <x-global.create-button
                            :route="route('customers.create')"
                            :text="__('customers.create')"
                        />
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <form action="{{ route('customers.index') }}" method="GET" class="flex-1">
                            <div class="flex flex-col sm:flex-row gap-2">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="{{ __('customers.search_placeholder') }}"
                                    class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                >
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                >
                                    {{ __('customers.search') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela para desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.phone') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.active') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.created_at') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('customers.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($customers as $customer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer->phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $customer->active ? __('customers.yes') : __('customers.no') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        <x-actions.view :route="route('customers.show', $customer->id)" />
                                        <x-actions.edit :route="route('customers.edit', $customer->id)" />
                                        <x-actions.delete
                                            :route="route('customers.destroy', $customer->id)"
                                            :confirmMessage="__('customers.confirm_delete')"
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
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $customer->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $customer->phone }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $customer->active ? __('customers.active') : __('customers.inactive') }}
                                    </span>
                                </div>

                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    <p>{{ __('customers.created_at') }}: {{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y H:i') }}</p>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <x-actions.view-mobile :route="route('customers.show', $customer->id)" />
                                    <x-actions.edit-mobile :route="route('customers.edit', $customer->id)" />
                                    <x-actions.delete-mobile
                                        :route="route('customers.destroy', $customer->id)"
                                        :confirmMessage="__('customers.confirm_delete')"
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-lg font-medium">{{ __('customers.no_customers_found') }}</p>
                                <p class="mt-2">{{ __('customers.no_customers_description') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
