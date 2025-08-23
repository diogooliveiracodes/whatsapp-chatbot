<x-app-layout>
    <x-global.header>
        {{ __('automated-messages.automated_messages') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex flex-col sm:flex-row gap-4 mb-4 justify-between items-start sm:items-center">
                        <div class="flex gap-2">
                            <x-global.create-button :route="route('automated-messages.create', (auth()->user()->isOwner() && isset($selectedUnit)) ? ['unit_id' => $selectedUnit->id] : [])" text="{{ __('automated-messages.create') }}" />
                        </div>

                        @if(isset($showUnitSelector) && $showUnitSelector)
                            <div class="w-full sm:w-auto">
                                <label for="unit-selector" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('automated-messages.unit_selection') }}
                                </label>
                                <select id="unit-selector"
                                        class="block w-full sm:min-w-[220px] px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                                        onchange="changeUnit(this.value)">
                                    @foreach($units as $unitOption)
                                        <option value="{{ $unitOption->id }}" {{ $selectedUnit->id == $unitOption->id ? 'selected' : '' }}>
                                            {{ $unitOption->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <!-- Tabela para desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('automated-messages.fields.name') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('automated-messages.fields.type') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('automated-messages.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($messages as $message)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $message->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $message->getTypeLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <x-actions.edit :route="route('automated-messages.edit', $message)" />
                                            <x-actions.delete
                                                :route="route('automated-messages.destroy', $message)"
                                                :confirmMessage="__('automated-messages.messages.confirm_delete')"
                                            />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            <div class="py-8">
                                                <div class="text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('automated-messages.no_messages') }}</h3>
                                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('automated-messages.no_messages_description') }}</p>
                                                    <div class="mt-6">
                                                        <a href="{{ route('automated-messages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            {{ __('automated-messages.create') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards para mobile -->
                    <div class="md:hidden space-y-4">
                        @forelse ($messages as $message)
                            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                                <div class="mb-2">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $message->name }}</h3>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $message->getTypeLabel() }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ Str::limit($message->content, 100) }}</p>
                                <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                                    <span>{{ $message->unit->name ?? 'N/A' }}</span>
                                    <span>{{ $message->user->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <x-actions.edit-mobile :route="route('automated-messages.edit', $message)" />
                                    <x-actions.delete-mobile
                                        :route="route('automated-messages.destroy', $message)"
                                        :confirmMessage="__('automated-messages.messages.confirm_delete')"
                                    />
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('automated-messages.no_messages') }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('automated-messages.no_messages_description') }}</p>
                                <div class="mt-6">
                                    <a href="{{ route('automated-messages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('automated-messages.create') }}
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($showUnitSelector) && $showUnitSelector)
        <script>
            function changeUnit(unitId) {
                window.location.href = '{{ route('automated-messages.index') }}?unit_id=' + unitId;
            }
        </script>
    @endif
</x-app-layout>
