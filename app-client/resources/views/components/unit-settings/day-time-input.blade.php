@props(['day', 'label', 'isChecked', 'startTime', 'endTime', 'hasBreak' => false, 'breakStartTime' => null, 'breakEndTime' => null])

<div class="bg-gray-600/30 rounded-lg p-4 mb-4 transition-all duration-200 hover:bg-gray-600/40">
    <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
        {{-- Coluna do Dia --}}
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="checkbox" name="{{ $day }}" value="1" {{ $isChecked ? 'checked' : '' }}
                    class="form-checkbox w-5 h-5 text-indigo-500 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500 focus:ring-2 day-checkbox"
                    data-day="{{ $day }}"
                    onchange="if(window.toggleTimeInputs) window.toggleTimeInputs('{{ $day }}')">
                <div class="absolute inset-0 rounded pointer-events-none {{ $isChecked ? 'bg-indigo-500/20' : '' }} transition-colors duration-200"></div>
            </div>
            <label class="text-white font-medium cursor-pointer select-none">{{ $label }}</label>
        </div>

        {{-- Coluna dos Horários com Range Slider --}}
        <div id="{{ $day }}-time-inputs" class="day-time-inputs flex flex-col space-y-4 flex-1"
             style="display: {{ $isChecked ? 'flex' : 'none' }};">

            {{-- Range Slider Container --}}
            <div class="bg-gray-700/50 rounded-lg p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('unitSettings.working_hours') }}</label>

                    {{-- Range Slider --}}
                    <div class="relative">
                        <div class="range-slider-container" data-day="{{ $day }}" data-context="work">
                            <div class="range-slider-track bg-gray-600 h-2 rounded-full relative">
                                <div class="range-slider-progress bg-indigo-500 h-2 rounded-full absolute top-0 left-0"
                                     data-day="{{ $day }}" data-context="work"></div>
                            </div>

                            {{-- Start Handle --}}
                            <input type="range"
                                   name="{{ $day }}_start_range"
                                   class="range-slider-handle range-slider-handle-start absolute top-0 w-full h-2 opacity-0 cursor-pointer"
                                   data-day="{{ $day }}"
                                   data-context="work"
                                   data-handle="start"
                                   min="0"
                                   max="1440"
                                   value="{{ $startTime ? \Carbon\Carbon::parse('2000-01-01 ' . $startTime)->format('H') * 60 + \Carbon\Carbon::parse('2000-01-01 ' . $startTime)->format('i') : 540 }}"
                                   oninput="if(window.updateRangeSlider) window.updateRangeSlider('{{ $day }}', 'work', 'start', this.value)">

                            {{-- End Handle --}}
                            <input type="range"
                                   name="{{ $day }}_end_range"
                                   class="range-slider-handle range-slider-handle-end absolute top-0 w-full h-2 opacity-0 cursor-pointer"
                                   data-day="{{ $day }}"
                                   data-context="work"
                                   data-handle="end"
                                   min="0"
                                   max="1440"
                                   value="{{ $endTime ? \Carbon\Carbon::parse('2000-01-01 ' . $endTime)->format('H') * 60 + \Carbon\Carbon::parse('2000-01-01 ' . $endTime)->format('i') : 1020 }}"
                                   oninput="if(window.updateRangeSlider) window.updateRangeSlider('{{ $day }}', 'work', 'end', this.value)">

                            {{-- Visual Handles --}}
                            <div class="range-slider-thumb range-slider-thumb-start absolute top-1/2 transform -translate-y-1/2 w-4 h-4 bg-indigo-500 rounded-full border-2 border-white shadow-lg cursor-pointer"
                                 data-day="{{ $day }}"
                                 data-context="work"
                                 data-handle="start">
                                <div class="range-tooltip range-tooltip-start absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 transition-opacity duration-200 pointer-events-none">
                                    <span class="range-tooltip-time-start" data-day="{{ $day }}" data-context="work">09:00</span>
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                                </div>
                            </div>

                            <div class="range-slider-thumb range-slider-thumb-end absolute top-1/2 transform -translate-y-1/2 w-4 h-4 bg-indigo-500 rounded-full border-2 border-white shadow-lg cursor-pointer"
                                 data-day="{{ $day }}"
                                 data-context="work"
                                 data-handle="end">
                                <div class="range-tooltip range-tooltip-end absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 transition-opacity duration-200 pointer-events-none">
                                    <span class="range-tooltip-time-end" data-day="{{ $day }}" data-context="work">17:00</span>
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Time Display --}}
                        <div class="flex justify-between mt-2 text-sm text-gray-400">
                            <span class="range-time-display-start" data-day="{{ $day }}" data-context="work">09:00</span>
                            <span class="range-time-display-end" data-day="{{ $day }}" data-context="work">17:00</span>
                        </div>
                    </div>
                </div>

                {{-- Hidden Inputs for Form Submission --}}
                <input type="hidden" name="{{ $day }}_start" value="{{ $startTime ? substr($startTime, 0, 5) : '09:00' }}" data-day="{{ $day }}" data-context="work" data-type="start">
                <input type="hidden" name="{{ $day }}_end" value="{{ $endTime ? substr($endTime, 0, 5) : '17:00' }}" data-day="{{ $day }}" data-context="work" data-type="end">
            </div>

            {{-- Break Checkbox --}}
            <div class="flex items-center space-x-3">
                <input type="checkbox" name="{{ $day }}_has_break" value="1" {{ $hasBreak ? 'checked' : '' }}
                    class="form-checkbox w-5 h-5 text-indigo-500 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500 focus:ring-2 break-checkbox"
                    data-day="{{ $day }}"
                    onchange="if(window.toggleBreakInputs) window.toggleBreakInputs('{{ $day }}')">
                <label class="text-gray-300">{{ __('unitSettings.has_break') }}</label>
            </div>

            {{-- Break Range Slider Container --}}
            <div id="{{ $day }}-break-inputs" class="break-time-inputs bg-gray-700/50 rounded-lg p-4"
                 style="display: {{ $hasBreak ? 'block' : 'none' }};">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('unitSettings.break_time') }}</label>
                    <div class="relative">
                        <div class="range-slider-container" data-day="{{ $day }}" data-context="break">
                            <div class="range-slider-track bg-gray-600 h-2 rounded-full relative">
                                <div class="range-slider-progress bg-indigo-500 h-2 rounded-full absolute top-0 left-0"
                                     data-day="{{ $day }}" data-context="break"></div>
                            </div>

                            {{-- Start Handle (Break) --}}
                            <input type="range"
                                   name="{{ $day }}_break_start_range"
                                   class="range-slider-handle range-slider-handle-start absolute top-0 w-full h-2 opacity-0 cursor-pointer"
                                   data-day="{{ $day }}"
                                   data-context="break"
                                   data-handle="start"
                                   min="0"
                                   max="1440"
                                   value="{{ $breakStartTime ? \Carbon\Carbon::parse('2000-01-01 ' . $breakStartTime)->format('H') * 60 + \Carbon\Carbon::parse('2000-01-01 ' . $breakStartTime)->format('i') : 720 }}"
                                   oninput="if(window.updateRangeSlider) window.updateRangeSlider('{{ $day }}', 'break', 'start', this.value)">

                            {{-- End Handle (Break) --}}
                            <input type="range"
                                   name="{{ $day }}_break_end_range"
                                   class="range-slider-handle range-slider-handle-end absolute top-0 w-full h-2 opacity-0 cursor-pointer"
                                   data-day="{{ $day }}"
                                   data-context="break"
                                   data-handle="end"
                                   min="0"
                                   max="1440"
                                   value="{{ $breakEndTime ? \Carbon\Carbon::parse('2000-01-01 ' . $breakEndTime)->format('H') * 60 + \Carbon\Carbon::parse('2000-01-01 ' . $breakEndTime)->format('i') : 780 }}"
                                   oninput="if(window.updateRangeSlider) window.updateRangeSlider('{{ $day }}', 'break', 'end', this.value)">

                            {{-- Visual Handles (Break) --}}
                            <div class="range-slider-thumb range-slider-thumb-start absolute top-1/2 transform -translate-y-1/2 w-4 h-4 bg-indigo-500 rounded-full border-2 border-white shadow-lg cursor-pointer"
                                 data-day="{{ $day }}"
                                 data-context="break"
                                 data-handle="start">
                                <div class="range-tooltip range-tooltip-start absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 transition-opacity duration-200 pointer-events-none">
                                    <span class="range-tooltip-time-start" data-day="{{ $day }}" data-context="break">12:00</span>
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                                </div>
                            </div>

                            <div class="range-slider-thumb range-slider-thumb-end absolute top-1/2 transform -translate-y-1/2 w-4 h-4 bg-indigo-500 rounded-full border-2 border-white shadow-lg cursor-pointer"
                                 data-day="{{ $day }}"
                                 data-context="break"
                                 data-handle="end">
                                <div class="range-tooltip range-tooltip-end absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 transition-opacity duration-200 pointer-events-none">
                                    <span class="range-tooltip-time-end" data-day="{{ $day }}" data-context="break">13:00</span>
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Time Display (Break) --}}
                        <div class="flex justify-between mt-2 text-sm text-gray-400">
                            <span class="range-time-display-start" data-day="{{ $day }}" data-context="break">12:00</span>
                            <span class="range-time-display-end" data-day="{{ $day }}" data-context="break">13:00</span>
                        </div>
                    </div>

                    {{-- Hidden Inputs (Break) --}}
                    <input type="hidden" name="{{ $day }}_break_start" value="{{ $breakStartTime ? substr($breakStartTime, 0, 5) : '12:00' }}" data-day="{{ $day }}" data-context="break" data-type="start">
                    <input type="hidden" name="{{ $day }}_break_end" value="{{ $breakEndTime ? substr($breakEndTime, 0, 5) : '13:00' }}" data-day="{{ $day }}" data-context="break" data-type="end">
                </div>
            </div>
        </div>
    </div>
</div>

@once
<style>
    .range-slider-container {
        position: relative;
        padding: 15px 0;
    }

    .range-slider-track {
        position: relative;
        width: 100%;
        height: 6px;
        background: linear-gradient(to right, #4f46e5, #7c3aed);
        border-radius: 3px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .range-slider-progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: linear-gradient(to right, #3b82f6, #1d4ed8);
        border-radius: 3px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .range-slider-handle {
        pointer-events: none;
        position: absolute;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .range-slider-thumb {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        background: linear-gradient(135deg, #ffffff, #f3f4f6);
        border: 3px solid #3b82f6;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        cursor: grab;
        transition: all 0.2s ease;
        z-index: 10;
    }

    .range-slider-thumb:hover {
        transform: translateY(-50%) scale(1.15);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        border-color: #1d4ed8;
    }

    .range-slider-thumb:active {
        transform: translateY(-50%) scale(1.2);
        cursor: grabbing;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.5);
    }

    .range-slider-thumb::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 6px;
        height: 6px;
        background: #3b82f6;
        border-radius: 50%;
    }

    /* Time display styling */
    .range-time-display-start,
    .range-time-display-end {
        font-weight: 600;
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.875rem;
    }
</style>

<script>
    // Ensure functions are globally available
    window.toggleTimeInputs = function(day) {
        const checkbox = document.querySelector(`input[data-day="${day}"]`);
        const timeInputsContainer = document.getElementById(`${day}-time-inputs`);

        if (!checkbox || !timeInputsContainer) {
            return;
        }

        const parentDiv = checkbox.closest('.bg-gray-600\\/30');
        const statusIndicator = parentDiv?.querySelector('.w-3.h-3.rounded-full');

        if (checkbox.checked) {
            timeInputsContainer.style.display = 'flex';
            // Ensure break container follows the day visibility
            const breakContainer = document.getElementById(`${day}-break-inputs`);
            const breakCheckbox = document.querySelector(`input.break-checkbox[data-day="${day}"]`);
            if (breakContainer && breakCheckbox) {
                breakContainer.style.display = breakCheckbox.checked ? 'block' : 'none';
            }
            if (statusIndicator) {
                statusIndicator.classList.remove('bg-gray-500');
                statusIndicator.classList.add('bg-green-500');
            }
            if (parentDiv) {
                parentDiv.classList.add('bg-gray-600/40');
            }
        } else {
            timeInputsContainer.style.display = 'none';
            const breakContainer = document.getElementById(`${day}-break-inputs`);
            if (breakContainer) breakContainer.style.display = 'none';
            if (statusIndicator) {
                statusIndicator.classList.remove('bg-green-500');
                statusIndicator.classList.add('bg-gray-500');
            }
            if (parentDiv) {
                parentDiv.classList.remove('bg-gray-600/40');
            }
        }
    };

    window.toggleBreakInputs = function(day) {
        const breakCheckbox = document.querySelector(`input.break-checkbox[data-day="${day}"]`);
        const breakContainer = document.getElementById(`${day}-break-inputs`);
        if (breakCheckbox && breakContainer) {
            breakContainer.style.display = breakCheckbox.checked ? 'block' : 'none';
        }
    };
    window.updateRangeSlider = function(day, context, handle, value) {
        const startInput = document.querySelector(`input[data-day="${day}"][data-context="${context}"][data-handle="start"]`);
        const endInput = document.querySelector(`input[data-day="${day}"][data-context="${context}"][data-handle="end"]`);
        const startThumb = document.querySelector(`.range-slider-thumb-start[data-day="${day}"][data-context="${context}"]`);
        const endThumb = document.querySelector(`.range-slider-thumb-end[data-day="${day}"][data-context="${context}"]`);
        const progress = document.querySelector(`.range-slider-progress[data-day="${day}"][data-context="${context}"]`);
        const startDisplay = document.querySelector(`.range-time-display-start[data-day="${day}"][data-context="${context}"]`);
        const endDisplay = document.querySelector(`.range-time-display-end[data-day="${day}"][data-context="${context}"]`);
        const startHidden = document.querySelector(`input[data-day="${day}"][data-context="${context}"][data-type="start"]`);
        const endHidden = document.querySelector(`input[data-day="${day}"][data-context="${context}"][data-type="end"]`);
        const appointmentDurationInput = document.querySelector('#appointment_duration_minutes');

        if (!startInput || !endInput || !startThumb || !endThumb || !progress) {
            return;
        }

        const startValue = parseInt(startInput.value);
        const endValue = parseInt(endInput.value);
        const appointmentDuration = parseInt(appointmentDurationInput?.value) || 60; // Default 60 minutes

        // Snap to appointment duration steps
        const snappedValue = Math.round(parseInt(value) / appointmentDuration) * appointmentDuration;

        // Ensure values don't exceed 23:59 (1439 minutes)
        const maxMinutes = 1439;
        const clampedValue = Math.min(snappedValue, maxMinutes);

        // Ensure start doesn't exceed end and vice versa
        if (handle === 'start' && clampedValue >= endValue) {
            value = endValue - appointmentDuration; // Minimum gap of one appointment duration
            startInput.value = Math.max(0, value);
        } else if (handle === 'end' && clampedValue <= startValue) {
            value = startValue + appointmentDuration; // Minimum gap of one appointment duration
            endInput.value = Math.min(maxMinutes, value);
        } else {
            // Update the input with snapped value
            if (handle === 'start') {
                startInput.value = Math.max(0, clampedValue);
            } else {
                endInput.value = Math.min(maxMinutes, clampedValue);
            }
        }

        // Update visual positions
        const startPercent = (startInput.value / 1440) * 100;
        const endPercent = (endInput.value / 1440) * 100;

        startThumb.style.left = `${startPercent}%`;
        endThumb.style.left = `${endPercent}%`;
        progress.style.left = `${startPercent}%`;
        progress.style.width = `${endPercent - startPercent}%`;

        // Update time displays
        const startTime = minutesToTime(startInput.value);
        const endTime = minutesToTime(endInput.value);

        if (startDisplay) startDisplay.textContent = startTime;
        if (endDisplay) endDisplay.textContent = endTime;

        // Update hidden inputs
        if (startHidden) startHidden.value = startTime;
        if (endHidden) endHidden.value = endTime;
    };

    function minutesToTime(minutes) {
        // Handle 24h case - convert 24:00 to 23:59
        if (minutes >= 1440) {
            minutes = 1439; // 23:59
        }

        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
    }

    function timeToMinutes(time) {
        const [hours, minutes] = time.split(':').map(Number);
        return hours * 60 + minutes;
    }

    function initializeRangeSliders() {
        document.querySelectorAll('.range-slider-container').forEach(container => {
            const day = container.getAttribute('data-day');
            const context = container.getAttribute('data-context') || 'work';

            // Get the current values from the range inputs
            const startInput = container.querySelector(`input[data-handle="start"][data-context="${context}"]`);
            const endInput = container.querySelector(`input[data-handle="end"][data-context="${context}"]`);

            if (!startInput || !endInput) {
                return;
            }

            // Initialize with current values and snap to steps
            const appointmentDurationInput = document.querySelector('#appointment_duration_minutes');
            const appointmentDuration = parseInt(appointmentDurationInput?.value) || 60;

            // Snap start value to steps
            const startValue = parseInt(startInput.value);
            const snappedStartValue = Math.round(startValue / appointmentDuration) * appointmentDuration;
            startInput.value = snappedStartValue;

            // Snap end value to steps
            const endValue = parseInt(endInput.value);
            const snappedEndValue = Math.round(endValue / appointmentDuration) * appointmentDuration;
            endInput.value = snappedEndValue;

            updateRangeSlider(day, context, 'start', snappedStartValue);
            updateRangeSlider(day, context, 'end', snappedEndValue);

            // Update time displays immediately
            const startDisplay = container.parentElement.querySelector(`.range-time-display-start[data-day="${day}"][data-context="${context}"]`);
            const endDisplay = container.parentElement.querySelector(`.range-time-display-end[data-day="${day}"][data-context="${context}"]`);
            const startHidden = container.parentElement.querySelector(`input[data-day="${day}"][data-context="${context}"][data-type="start"]`);
            const endHidden = container.parentElement.querySelector(`input[data-day="${day}"][data-context="${context}"][data-type="end"]`);

            if (startDisplay && endDisplay && startHidden && endHidden) {
                startDisplay.textContent = minutesToTime(snappedStartValue);
                endDisplay.textContent = minutesToTime(snappedEndValue);
                startHidden.value = minutesToTime(snappedStartValue);
                endHidden.value = minutesToTime(snappedEndValue);
            }

            // Add drag functionality to thumbs
            const startThumb = container.querySelector('.range-slider-thumb-start[data-context="' + context + '"]');
            const endThumb = container.querySelector('.range-slider-thumb-end[data-context="' + context + '"]');
            const track = container.querySelector('.range-slider-track');

            if (startThumb && endThumb && track) {
                addDragListeners(startThumb, day, context, 'start', track);
                addDragListeners(endThumb, day, context, 'end', track);
                addTrackClickListeners(track, day, context);
            }
        });
    }

    function addDragListeners(thumb, day, context, handle, track) {
        let isDragging = false;
        const tooltip = thumb.querySelector(`.range-tooltip-${handle}`);
        const tooltipTime = thumb.querySelector(`.range-tooltip-time-${handle}`);

        thumb.addEventListener('mousedown', (e) => {
            isDragging = true;
            thumb.style.cursor = 'grabbing';
            if (tooltip) tooltip.style.opacity = '1';
            e.preventDefault();
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;

            const rect = track.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const percent = Math.max(0, Math.min(100, (x / rect.width) * 100));
            const minutes = Math.min(1439, Math.round((percent / 100) * 1440));

            const input = document.querySelector(`input[data-day="${day}"][data-context="${context}"][data-handle="${handle}"]`);
            updateRangeSlider(day, context, handle, minutes);

            // Update tooltip with snapped value
            if (input && tooltipTime) {
                const snappedMinutes = input.value;
                const time = minutesToTime(snappedMinutes);
                tooltipTime.textContent = time;
            }
        });

        document.addEventListener('mouseup', () => {
            if (isDragging) {
                isDragging = false;
                thumb.style.cursor = 'grab';
                if (tooltip) tooltip.style.opacity = '0';
            }
        });

        thumb.addEventListener('mouseenter', () => {
            if (!isDragging) {
                thumb.style.cursor = 'grab';
                if (tooltip) tooltip.style.opacity = '1';
            }
        });

        thumb.addEventListener('mouseleave', () => {
            if (!isDragging) {
                thumb.style.cursor = 'pointer';
                if (tooltip) tooltip.style.opacity = '0';
            }
        });
    }

    function addTrackClickListeners(track, day, context) {
        track.addEventListener('click', (e) => {
            const rect = track.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const percent = (x / rect.width) * 100;
            const minutes = Math.min(1439, Math.round((percent / 100) * 1440));

            const startInput = document.querySelector(`input[data-day="${day}"][data-context="${context}"][data-handle="start"]`);
            const endInput = document.querySelector(`input[data-day="${day}"][data-context="${context}"][data-handle="end"]`);

            if (!startInput || !endInput) return;

            const startValue = parseInt(startInput.value);
            const endValue = parseInt(endInput.value);

            // Determine which handle to move based on which is closer
            const startDistance = Math.abs(minutes - startValue);
            const endDistance = Math.abs(minutes - endValue);

            if (startDistance < endDistance) {
                updateRangeSlider(day, context, 'start', minutes);
            } else {
                updateRangeSlider(day, context, 'end', minutes);
            }
        });
    }

    function updateAllSlidersWithNewSteps() {
        document.querySelectorAll('.day-checkbox:checked').forEach(checkbox => {
            const day = checkbox.getAttribute('data-day');
            const startInput = document.querySelector(`input[data-day="${day}"][data-handle="start"]`);
            const endInput = document.querySelector(`input[data-day="${day}"][data-handle="end"]`);

            if (startInput && endInput) {
                // Re-snap both handles to new steps
                updateRangeSlider(day, 'start', startInput.value);
                updateRangeSlider(day, 'end', endInput.value);
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {

            // Initialize checkboxes
            document.querySelectorAll('.day-checkbox').forEach(checkbox => {
                const day = checkbox.getAttribute('data-day');
                toggleTimeInputs(day);
                toggleBreakInputs(day);
            });

            // Initialize range sliders
            setTimeout(initializeRangeSliders, 100);

            // Add event listener to appointment duration input
            const appointmentDurationInput = document.querySelector('#appointment_duration_minutes');
            if (appointmentDurationInput) {
                appointmentDurationInput.addEventListener('change', updateAllSlidersWithNewSteps);
                appointmentDurationInput.addEventListener('input', updateAllSlidersWithNewSteps);
            }
        });
    } else {
        // DOM is already loaded

        // Initialize checkboxes
        document.querySelectorAll('.day-checkbox').forEach(checkbox => {
            const day = checkbox.getAttribute('data-day');
            toggleTimeInputs(day);
            toggleBreakInputs(day);
        });

        // Initialize range sliders
        setTimeout(initializeRangeSliders, 100);

        // Add event listener to appointment duration input
        const appointmentDurationInput = document.querySelector('#appointment_duration_minutes');
        if (appointmentDurationInput) {
            appointmentDurationInput.addEventListener('change', updateAllSlidersWithNewSteps);
            appointmentDurationInput.addEventListener('input', updateAllSlidersWithNewSteps);
        }
    }
</script>
@endonce
