@props(['day', 'label', 'isChecked', 'startTime', 'endTime'])

<div class="flex items-start space-x-6 mb-4">
    {{-- Coluna do Dia --}}
    <div class="w-40">
        <label class="inline-flex items-center">
            <input type="checkbox" name="{{ $day }}" value="1" {{ $isChecked ? 'checked' : '' }}
                class="form-checkbox text-indigo-500 day-checkbox" data-day="{{ $day }}"
                onchange="toggleTimeInputs('{{ $day }}')">
            <span class="ml-2 dark:text-gray-300">{{ $label }}</span>
        </label>
    </div>

    {{-- Coluna dos Horários (inputs lado a lado) --}}
    <div id="{{ $day }}-time-inputs" class="day-time-inputs flex space-x-4"
         style="display: {{ $isChecked ? 'flex' : 'none' }};">
        <input type="time" name="{{ $day }}_start"
               value="{{ substr($startTime, 0, 5) }}"
               class="input-style w-32"
               {{ $isChecked ? 'required' : '' }}
               data-time="{{ $day }}">

        <input type="time" name="{{ $day }}_end"
               value="{{ substr($endTime, 0, 5) }}"
               class="input-style w-32"
               {{ $isChecked ? 'required' : '' }}
               data-time="{{ $day }}">
    </div>
</div>

@once
    <script>
        function toggleTimeInputs(day) {
            const checkbox = document.querySelector(`input[data-day="${day}"]`);
            const timeInputsContainer = document.getElementById(`${day}-time-inputs`);
            const timeInputs = timeInputsContainer.querySelectorAll(`input[data-time="${day}"]`);

            if (checkbox.checked) {
                timeInputsContainer.style.display = 'flex';
                timeInputs.forEach(input => input.setAttribute('required', 'required'));
            } else {
                timeInputsContainer.style.display = 'none';
                timeInputs.forEach(input => input.removeAttribute('required'));
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
