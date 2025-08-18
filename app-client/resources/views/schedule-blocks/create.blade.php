<x-app-layout>
    <x-global.header>
        {{ __('schedule-blocks.create_block') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
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
                        <div id="time-fields" style="display: none;">
                            <input type="hidden" id="appointment_duration_minutes" value="{{ auth()->user()->unit->unitSettings->appointment_duration_minutes ?? 60 }}">
                            <x-time-range-slider
                                :startTime="old('start_time', $preSelectedStartTime)"
                                :endTime="old('end_time')"
                                name="schedule_block_time" />

                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
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

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link :href="route('schedule-blocks.index')">
                                {{ __('schedule-blocks.back') }}
                            </x-cancel-link>

                            <!-- Create Button -->
                            <x-primary-button type="submit">
                                {{ __('actions.save') }}
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

                function toggleTimeFields() {
                    if (blockTypeSelect.value === 'time_slot') {
                        timeFields.style.display = 'block';
                        // Make hidden inputs required and ensure they have default values
                        const startTimeHidden = document.querySelector('input[name="start_time"]');
                        const endTimeHidden = document.querySelector('input[name="end_time"]');

                        if (startTimeHidden) {
                            startTimeHidden.required = true;
                            // Ensure default value if empty
                            if (!startTimeHidden.value) {
                                startTimeHidden.value = '09:00';
                            }
                        }

                        if (endTimeHidden) {
                            endTimeHidden.required = true;
                            // Ensure default value if empty
                            if (!endTimeHidden.value) {
                                endTimeHidden.value = '17:00';
                            }
                        }

                        // Initialize the time range slider if it exists
                        if (typeof initializeTimeRangeSliders === 'function') {
                            setTimeout(() => {
                                initializeTimeRangeSliders();
                            }, 100);
                        } else {
                            // Fallback: manually update the slider display if the function doesn't exist
                            setTimeout(() => {
                                const startDisplay = document.querySelector('.time-range-display-start');
                                const endDisplay = document.querySelector('.time-range-display-end');
                                const startHidden = document.querySelector('input[name="start_time"]');
                                const endHidden = document.querySelector('input[name="end_time"]');

                                if (startDisplay && endDisplay && startHidden && endHidden) {
                                    startDisplay.textContent = startHidden.value;
                                    endDisplay.textContent = endHidden.value;
                                }
                            }, 100);
                        }
                    } else {
                        timeFields.style.display = 'none';
                        // Remove required from hidden inputs and clear values
                        const startTimeHidden = document.querySelector('input[name="start_time"]');
                        const endTimeHidden = document.querySelector('input[name="end_time"]');

                        if (startTimeHidden) {
                            startTimeHidden.required = false;
                            startTimeHidden.value = '';
                        }

                        if (endTimeHidden) {
                            endTimeHidden.required = false;
                            endTimeHidden.value = '';
                        }
                    }
                }

                blockTypeSelect.addEventListener('change', toggleTimeFields);

                // Initialize on page load
                toggleTimeFields();

                // Additional check to ensure values are set when time_slot is selected
                if (blockTypeSelect.value === 'time_slot') {
                    setTimeout(() => {
                        const startTimeHidden = document.querySelector('input[name="start_time"]');
                        const endTimeHidden = document.querySelector('input[name="end_time"]');

                        if (startTimeHidden && !startTimeHidden.value) {
                            startTimeHidden.value = '09:00';
                        }

                        if (endTimeHidden && !endTimeHidden.value) {
                            endTimeHidden.value = '17:00';
                        }
                    }, 200);
                }
            });
        </script>
    @endpush
</x-app-layout>
