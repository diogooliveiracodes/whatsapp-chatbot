@props(['startTime' => '', 'endTime' => '', 'name' => 'time_range'])

<div class="bg-gray-700/50 rounded-lg p-4">
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('schedule-blocks.time_range') }}</label>

        {{-- Range Slider --}}
        <div class="relative">
            <div class="time-range-slider-container" data-name="{{ $name }}">
                <div class="time-range-slider-track bg-gray-600 h-2 rounded-full relative">
                    <div class="time-range-slider-progress bg-indigo-500 h-2 rounded-full absolute top-0 left-0"
                         data-name="{{ $name }}"></div>
                </div>

                {{-- Start Handle --}}
                <input type="range"
                       name="{{ $name }}_start_range"
                       class="time-range-slider-handle time-range-slider-handle-start absolute top-0 w-full h-2 opacity-0 cursor-pointer"
                       data-name="{{ $name }}"
                       data-handle="start"
                       min="0"
                       max="1440"
                       value="{{ $startTime ? \Carbon\Carbon::parse('2000-01-01 ' . $startTime)->format('H') * 60 + \Carbon\Carbon::parse('2000-01-01 ' . $startTime)->format('i') : 540 }}"
                       oninput="updateTimeRangeSlider('{{ $name }}', 'start', this.value)">

                {{-- End Handle --}}
                <input type="range"
                       name="{{ $name }}_end_range"
                       class="time-range-slider-handle time-range-slider-handle-end absolute top-0 w-full h-2 opacity-0 cursor-pointer"
                       data-name="{{ $name }}"
                       data-handle="end"
                       min="0"
                       max="1440"
                       value="{{ $endTime ? \Carbon\Carbon::parse('2000-01-01 ' . $endTime)->format('H') * 60 + \Carbon\Carbon::parse('2000-01-01 ' . $endTime)->format('i') : 1020 }}"
                       oninput="updateTimeRangeSlider('{{ $name }}', 'end', this.value)">

                {{-- Visual Handles --}}
                <div class="time-range-slider-thumb time-range-slider-thumb-start absolute top-1/2 transform -translate-y-1/2 w-4 h-4 bg-indigo-500 rounded-full border-2 border-white shadow-lg cursor-pointer"
                     data-name="{{ $name }}"
                     data-handle="start">
                    <div class="time-range-tooltip time-range-tooltip-start absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 transition-opacity duration-200 pointer-events-none">
                        <span class="time-range-tooltip-time-start" data-name="{{ $name }}">09:00</span>
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                    </div>
                </div>

                <div class="time-range-slider-thumb time-range-slider-thumb-end absolute top-1/2 transform -translate-y-1/2 w-4 h-4 bg-indigo-500 rounded-full border-2 border-white shadow-lg cursor-pointer"
                     data-name="{{ $name }}"
                     data-handle="end">
                    <div class="time-range-tooltip time-range-tooltip-end absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 transition-opacity duration-200 pointer-events-none">
                        <span class="time-range-tooltip-time-end" data-name="{{ $name }}">17:00</span>
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                    </div>
                </div>
            </div>

            {{-- Time Display --}}
            <div class="flex justify-between mt-2 text-sm text-gray-400">
                <span class="time-range-display-start" data-name="{{ $name }}">09:00</span>
                <span class="time-range-display-end" data-name="{{ $name }}">17:00</span>
            </div>
        </div>
    </div>

    {{-- Hidden Inputs for Form Submission --}}
    <input type="hidden" name="start_time" value="{{ $startTime ? substr($startTime, 0, 5) : '09:00' }}" data-name="{{ $name }}" data-type="start">
    <input type="hidden" name="end_time" value="{{ $endTime ? substr($endTime, 0, 5) : '17:00' }}" data-name="{{ $name }}" data-type="end">
</div>

@once
<style>
    .time-range-slider-container {
        position: relative;
        padding: 15px 0;
    }

    .time-range-slider-track {
        position: relative;
        width: 100%;
        height: 6px;
        background: linear-gradient(to right, #4f46e5, #7c3aed);
        border-radius: 3px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .time-range-slider-progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: linear-gradient(to right, #3b82f6, #1d4ed8);
        border-radius: 3px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .time-range-slider-handle {
        pointer-events: none;
        position: absolute;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .time-range-slider-thumb {
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

    .time-range-slider-thumb:hover {
        transform: translateY(-50%) scale(1.15);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        border-color: #1d4ed8;
    }

    .time-range-slider-thumb:active {
        transform: translateY(-50%) scale(1.2);
        cursor: grabbing;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.5);
    }

    .time-range-slider-thumb::after {
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
    .time-range-display-start,
    .time-range-display-end {
        font-weight: 600;
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.875rem;
    }
</style>

<script>
    function updateTimeRangeSlider(name, handle, value) {
        const startInput = document.querySelector(`input[data-name="${name}"][data-handle="start"]`);
        const endInput = document.querySelector(`input[data-name="${name}"][data-handle="end"]`);
        const startThumb = document.querySelector(`.time-range-slider-thumb-start[data-name="${name}"]`);
        const endThumb = document.querySelector(`.time-range-slider-thumb-end[data-name="${name}"]`);
        const progress = document.querySelector(`.time-range-slider-progress[data-name="${name}"]`);
        const startDisplay = document.querySelector(`.time-range-display-start[data-name="${name}"]`);
        const endDisplay = document.querySelector(`.time-range-display-end[data-name="${name}"]`);
        const startHidden = document.querySelector(`input[data-name="${name}"][data-type="start"]`);
        const endHidden = document.querySelector(`input[data-name="${name}"][data-type="end"]`);
        const appointmentDurationInput = document.querySelector('#appointment_duration_minutes');

        const startValue = parseInt(startInput.value);
        const endValue = parseInt(endInput.value);
        const appointmentDuration = parseInt(appointmentDurationInput?.value) || 60; // Default 60 minutes

        // Snap to appointment duration steps
        const snappedValue = Math.round(parseInt(value) / appointmentDuration) * appointmentDuration;

        // Ensure start doesn't exceed end and vice versa
        if (handle === 'start' && snappedValue >= endValue) {
            value = endValue - appointmentDuration; // Minimum gap of one appointment duration
            startInput.value = value;
        } else if (handle === 'end' && snappedValue <= startValue) {
            value = startValue + appointmentDuration; // Minimum gap of one appointment duration
            endInput.value = value;
        } else {
            // Update the input with snapped value
            if (handle === 'start') {
                startInput.value = snappedValue;
            } else {
                endInput.value = snappedValue;
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

        startDisplay.textContent = startTime;
        endDisplay.textContent = endTime;

        // Update hidden inputs
        startHidden.value = startTime;
        endHidden.value = endTime;
    }

    function minutesToTime(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
    }

    function initializeTimeRangeSliders() {
        document.querySelectorAll('.time-range-slider-container').forEach(container => {
            const name = container.getAttribute('data-name');

            // Get the current values from the range inputs
            const startInput = container.querySelector('input[data-handle="start"]');
            const endInput = container.querySelector('input[data-handle="end"]');

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

            updateTimeRangeSlider(name, 'start', snappedStartValue);
            updateTimeRangeSlider(name, 'end', snappedEndValue);

            // Update time displays immediately
            const startDisplay = container.querySelector('.time-range-display-start');
            const endDisplay = container.querySelector('.time-range-display-end');
            const startHidden = container.querySelector('input[data-type="start"]');
            const endHidden = container.querySelector('input[data-type="end"]');

            if (startDisplay && endDisplay && startHidden && endHidden) {
                startDisplay.textContent = minutesToTime(snappedStartValue);
                endDisplay.textContent = minutesToTime(snappedEndValue);
                startHidden.value = minutesToTime(snappedStartValue);
                endHidden.value = minutesToTime(snappedEndValue);
            }

            // Add drag functionality to thumbs
            const startThumb = container.querySelector('.time-range-slider-thumb-start');
            const endThumb = container.querySelector('.time-range-slider-thumb-end');
            const track = container.querySelector('.time-range-slider-track');

            addTimeRangeDragListeners(startThumb, name, 'start', track);
            addTimeRangeDragListeners(endThumb, name, 'end', track);

            // Add click functionality to track
            addTimeRangeTrackClickListeners(track, name);
        });
    }

    function addTimeRangeDragListeners(thumb, name, handle, track) {
        let isDragging = false;
        const tooltip = thumb.querySelector(`.time-range-tooltip-${handle}`);
        const tooltipTime = thumb.querySelector(`.time-range-tooltip-time-${handle}`);

        thumb.addEventListener('mousedown', (e) => {
            isDragging = true;
            thumb.style.cursor = 'grabbing';
            tooltip.style.opacity = '1';
            e.preventDefault();
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;

            const rect = track.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const percent = Math.max(0, Math.min(100, (x / rect.width) * 100));
            const minutes = Math.round((percent / 100) * 1440);

            const input = document.querySelector(`input[data-name="${name}"][data-handle="${handle}"]`);
            updateTimeRangeSlider(name, handle, minutes);

            // Update tooltip with snapped value
            const snappedMinutes = input.value;
            const time = minutesToTime(snappedMinutes);
            tooltipTime.textContent = time;
        });

        document.addEventListener('mouseup', () => {
            if (isDragging) {
                isDragging = false;
                thumb.style.cursor = 'grab';
                tooltip.style.opacity = '0';
            }
        });

        thumb.addEventListener('mouseenter', () => {
            if (!isDragging) {
                thumb.style.cursor = 'grab';
                tooltip.style.opacity = '1';
            }
        });

        thumb.addEventListener('mouseleave', () => {
            if (!isDragging) {
                thumb.style.cursor = 'pointer';
                tooltip.style.opacity = '0';
            }
        });
    }

    function addTimeRangeTrackClickListeners(track, name) {
        track.addEventListener('click', (e) => {
            const rect = track.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const percent = (x / rect.width) * 100;
            const minutes = Math.round((percent / 100) * 1440);

            const startInput = document.querySelector(`input[data-name="${name}"][data-handle="start"]`);
            const endInput = document.querySelector(`input[data-name="${name}"][data-handle="end"]`);
            const startValue = parseInt(startInput.value);
            const endValue = parseInt(endInput.value);

            // Determine which handle to move based on which is closer
            const startDistance = Math.abs(minutes - startValue);
            const endDistance = Math.abs(minutes - endValue);

            if (startDistance < endDistance) {
                updateTimeRangeSlider(name, 'start', minutes);
            } else {
                updateTimeRangeSlider(name, 'end', minutes);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Initialize time range sliders
        initializeTimeRangeSliders();

        // Add event listener to appointment duration input
        const appointmentDurationInput = document.querySelector('#appointment_duration_minutes');
        if (appointmentDurationInput) {
            appointmentDurationInput.addEventListener('change', updateAllTimeRangeSlidersWithNewSteps);
            appointmentDurationInput.addEventListener('input', updateAllTimeRangeSlidersWithNewSteps);
        }
    });

    function updateAllTimeRangeSlidersWithNewSteps() {
        document.querySelectorAll('.time-range-slider-container').forEach(container => {
            const name = container.getAttribute('data-name');
            const startInput = container.querySelector('input[data-handle="start"]');
            const endInput = container.querySelector('input[data-handle="end"]');

            if (startInput && endInput) {
                // Re-snap both handles to new steps
                updateTimeRangeSlider(name, 'start', startInput.value);
                updateTimeRangeSlider(name, 'end', endInput.value);
            }
        });
    }
</script>
@endonce
