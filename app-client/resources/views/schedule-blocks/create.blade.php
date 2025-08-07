<x-app-layout>
    <x-global.header>
        {{ __('schedule-blocks.create_block') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <form method="POST" action="{{ route('schedule-blocks.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Data do Bloqueio -->
                            <div>
                                <label for="block_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('schedule-blocks.block_date') }} *
                                </label>
                                <input type="date"
                                       id="block_date"
                                       name="block_date"
                                       value="{{ old('block_date', $preSelectedDate) }}"
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                       required>
                                @error('block_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo de Bloqueio -->
                            <div>
                                <label for="block_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('schedule-blocks.block_type') }} *
                                </label>
                                <select id="block_type"
                                        name="block_type"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        required>
                                    <option value="">{{ __('schedule-blocks.select_type') }}</option>
                                    @foreach ($blockTypes as $type)
                                        <option value="{{ $type->value }}" {{ old('block_type') == $type->value ? 'selected' : '' }}>
                                            {{ $type->getLabel() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('block_type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Campos de Horário (visíveis apenas para time_slot) -->
                        <div id="time-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('schedule-blocks.start_time') }} *
                                </label>
                                <input type="time"
                                       id="start_time"
                                       name="start_time"
                                       value="{{ old('start_time', $preSelectedStartTime) }}"
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @error('start_time')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('schedule-blocks.end_time') }} *
                                </label>
                                <input type="time"
                                       id="end_time"
                                       name="end_time"
                                       value="{{ old('end_time') }}"
                                       class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Motivo do Bloqueio -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('schedule-blocks.reason') }}
                            </label>
                            <textarea id="reason"
                                      name="reason"
                                      rows="3"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      placeholder="{{ __('schedule-blocks.reason_placeholder') }}">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botões -->
                                                <div class="flex justify-end space-x-3">
                            <x-cancel-link :href="route('schedule-blocks.index')">
                                {{ __('schedule-blocks.back') }}
                            </x-cancel-link>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('schedule-blocks.create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const blockTypeSelect = document.getElementById('block_type');
                const timeFields = document.getElementById('time-fields');
                const startTimeInput = document.getElementById('start_time');
                const endTimeInput = document.getElementById('end_time');

                function toggleTimeFields() {
                    if (blockTypeSelect.value === 'time_slot') {
                        timeFields.style.display = 'grid';
                        startTimeInput.required = true;
                        endTimeInput.required = true;
                    } else {
                        timeFields.style.display = 'none';
                        startTimeInput.required = false;
                        endTimeInput.required = false;
                        startTimeInput.value = '';
                        endTimeInput.value = '';
                    }
                }

                blockTypeSelect.addEventListener('change', toggleTimeFields);

                // Initialize on page load
                toggleTimeFields();
            });
        </script>
    @endpush
</x-app-layout>
