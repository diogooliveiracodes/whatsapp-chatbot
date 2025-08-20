<x-app-layout>
    <x-global.header>
        {{ __('units.title') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex gap-4 mb-4">
                        <x-global.create-button :route="route('units.create')" :text="__('units.create')" />

                        <x-buttons.deativated-button :route="route('units.deactivated')" :text="__('units.deactivated')" />
                    </div>

                    <!-- Tabela para desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        {{ __('units.name') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        {{ __('units.active') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        {{ __('units.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($units as $unit)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $unit->name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $unit->active ? __('units.yes') : __('units.no') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <x-actions.view :route="route('units.show', $unit->id)" />
                                                <x-actions.edit :route="route('units.edit', $unit->id)" />
                                                @if ($unit->unitSettings)
                                                    <x-actions.settings :route="route('unitSettings.show', $unit->unitSettings->id)" />
                                                @endif
                                                <x-actions.deactivate :route="route('units.deactivate', $unit->id)" :confirmMessage="__('units.confirm_deactivate')" />
                                            </div>
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
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $unit->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('units.active') }}:
                                            {{ $unit->active ? __('units.yes') : __('units.no') }}
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $unit->active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $unit->active ? __('units.active') : __('units.inactive') }}
                                    </span>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <x-actions.view-mobile :route="route('units.show', $unit->id)" />
                                    <x-actions.edit-mobile :route="route('units.edit', $unit->id)" />
                                    @if ($unit->unitSettings)
                                        <x-actions.settings-mobile :route="route('unitSettings.show', $unit->unitSettings->id)" />
                                    @endif
                                    <x-actions.deactivate-mobile :route="route('units.deactivate', $unit->id)" :confirmMessage="__('units.confirm_deactivate')" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Mensagem quando não há unidades -->
                    @if ($units->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="text-lg font-medium">{{ __('units.no_units_found') }}</p>
                                <p class="mt-2">{{ __('units.no_units_description') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
