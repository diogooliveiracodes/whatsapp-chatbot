<x-app-layout>
    <x-global.header>
        {{ __('schedule-blocks.edit_block') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <x-global.session-alerts />

                    <form method="POST" action="{{ route('schedule-blocks.update', $scheduleBlock->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Data do Bloqueio -->
                            <div>
                                <label for="block_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('schedule-blocks.block_date') }} *
                                </label>
                                <input type="date"
                                       id="block_date"
                                       name="block_date"
                                       value="{{ old('block_date', $scheduleBlock->block_date->format('Y-m-d')) }}"
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
                                        <option value="{{ $type->value }}" {{ old('block_type', $scheduleBlock->block_type->value) == $type->value ? 'selected' : '' }}>
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
                                       value="{{ old('start_time', $scheduleBlock->start_time ? \Carbon\Carbon::parse($scheduleBlock->start_time)->format('H:i') : '') }}"
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
                                       value="{{ old('end_time', $scheduleBlock->end_time ? \Carbon\Carbon::parse($scheduleBlock->end_time)->format('H:i') : '') }}"
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
                                      placeholder="{{ __('schedule-blocks.reason_placeholder') }}">{{ old('reason', $scheduleBlock->reason) }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-between">
                            <x-cancel-link :href="route('schedule-blocks.index')">
                                {{ __('schedule-blocks.back') }}
                            </x-cancel-link>
                            <x-primary-button type="submit">
                                {{ __('schedule-blocks.update') }}
                            </x-primary-button>
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
                        // Don't clear values for full day blocks to preserve existing data
                        if (blockTypeSelect.value === 'full_day') {
                            startTimeInput.value = '';
                            endTimeInput.value = '';
                        }
                    }
                }

                blockTypeSelect.addEventListener('change', toggleTimeFields);

                // Initialize on page load
                toggleTimeFields();
            });
        </script>
    @endpush
</x-app-layout>
