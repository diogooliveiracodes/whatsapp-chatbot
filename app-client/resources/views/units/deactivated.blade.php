<x-app-layout>
    <x-global.header>
        {{ __('units.deactivated_title') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <!-- Botão Voltar -->
                    <div class="mb-6">
                        <a href="{{ route('units.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('units.active_units') }}
                        </a>
                    </div>

                    <!-- Tabela para desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('units.name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('units.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($units as $unit)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $unit->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <x-actions.activate :route="route('units.activate', $unit)" :confirmMessage="__('units.confirm_activate')">
                                                {{ __('buttons.activate') }}
                                            </x-actions.activate>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards para mobile -->
                    <div class="md:hidden space-y-4">
                        @foreach ($units as $unit)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $unit->name }}</h3>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        {{ __('units.inactive') }}
                                    </span>
                                </div>

                                <div class="flex justify-end space-x-4">
                                    <x-actions.activate :route="route('units.activate', $unit)" :confirmMessage="__('units.confirm_activate')">
                                        {{ __('buttons.activate') }}
                                    </x-actions.activate>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Mensagem quando não há unidades desativadas -->
                    @if($units->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-lg font-medium">{{ __('units.no_deactivated_units_found') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
