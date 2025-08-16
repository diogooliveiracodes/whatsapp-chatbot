@props(['day', 'label', 'isChecked', 'startTime', 'endTime'])

<div class="bg-gray-600/30 rounded-lg p-4 mb-4 transition-all duration-200 hover:bg-gray-600/40">
    <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
        {{-- Coluna do Dia --}}
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="checkbox" name="{{ $day }}" value="1" {{ $isChecked ? 'checked' : '' }}
                    class="form-checkbox w-5 h-5 text-indigo-500 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500 focus:ring-2 day-checkbox"
                    data-day="{{ $day }}"
                    onchange="toggleTimeInputs('{{ $day }}')">
                <div class="absolute inset-0 rounded pointer-events-none {{ $isChecked ? 'bg-indigo-500/20' : '' }} transition-colors duration-200"></div>
            </div>
            <label class="text-white font-medium cursor-pointer select-none">{{ $label }}</label>
        </div>

        {{-- Coluna dos Horários --}}
        <div id="{{ $day }}-time-inputs" class="day-time-inputs flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 flex-1"
             style="display: {{ $isChecked ? 'flex' : 'none' }};">

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-1">{{ __('unitSettings.start_time') }}</label>
                <input type="time" name="{{ $day }}_start"
                       value="{{ $startTime ? substr($startTime, 0, 5) : '' }}"
                       class="input-style w-full"
                       {{ $isChecked ? 'required' : '' }}
                       data-time="{{ $day }}">
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-1">{{ __('unitSettings.end_time') }}</label>
                <input type="time" name="{{ $day }}_end"
                       value="{{ $endTime ? substr($endTime, 0, 5) : '' }}"
                       class="input-style w-full"
                       {{ $isChecked ? 'required' : '' }}
                       data-time="{{ $day }}">
            </div>
        </div>

        {{-- Status Indicator --}}
        <div class="flex items-center justify-center sm:justify-end">
            <div class="w-3 h-3 rounded-full {{ $isChecked ? 'bg-green-500' : 'bg-gray-500' }} transition-colors duration-200"></div>
        </div>
    </div>
</div>

@once
    <script>
        function toggleTimeInputs(day) {
            const checkbox = document.querySelector(`input[data-day="${day}"]`);
            const timeInputsContainer = document.getElementById(`${day}-time-inputs`);
            const timeInputs = timeInputsContainer.querySelectorAll(`input[data-time="${day}"]`);
            const parentDiv = checkbox.closest('.bg-gray-600\\/30');
            const statusIndicator = parentDiv.querySelector('.w-3.h-3.rounded-full');

            if (checkbox.checked) {
                timeInputsContainer.style.display = 'flex';
                timeInputs.forEach(input => input.setAttribute('required', 'required'));
                statusIndicator.classList.remove('bg-gray-500');
                statusIndicator.classList.add('bg-green-500');
                parentDiv.classList.add('bg-gray-600/40');
            } else {
                timeInputsContainer.style.display = 'none';
                timeInputs.forEach(input => input.removeAttribute('required'));
                statusIndicator.classList.remove('bg-green-500');
                statusIndicator.classList.add('bg-gray-500');
                parentDiv.classList.remove('bg-gray-600/40');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.day-checkbox').forEach(checkbox => {
                const day = checkbox.getAttribute('data-day');
                toggleTimeInputs(day);
            });
        });
    </script>
@endonce
