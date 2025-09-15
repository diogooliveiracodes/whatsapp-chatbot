<x-app-layout>
    <x-global.header>
        {{ __('schedules.edit_schedule') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <x-global.session-alerts />

                    <!-- Unit selector for owners -->
                    @if($showUnitSelector)
                        <div class="mb-6">
                            <label for="unit-selector" class="block text-sm font-medium text-gray-300 mb-2">
                                {{ __('schedules.unit_selection') }}
                            </label>
                            <select id="unit-selector"
                                    class="block w-full max-w-xs px-3 py-2 border border-gray-600 bg-gray-700 text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    onchange="changeUnit(this.value)">
                                @foreach($units as $unitOption)
                                    <option value="{{ $unitOption->id }}" {{ $selectedUnit->id == $unitOption->id ? 'selected' : '' }}>
                                        {{ $unitOption->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('schedules.update', $schedule->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Hidden field for unit_id -->
                        <input type="hidden" name="unit_id" value="{{ $selectedUnit->id }}" />

                        <div>
                            <label for="customer_id" class="block font-medium text-sm text-gray-300">
                                {{ __('customers.client') }}
                            </label>
                            <input type="hidden" name="customer_id" value="{{ old('customer_id', $schedule->customer_id) }}" />
                            <select id="customer_id" name="customer_id_display"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed @error('customer_id') border-red-500 @enderror"
                                disabled>
                                <option value="">{{ __('customers.select') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id', $schedule->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                        </div>

                        <div>
                            <label for="schedule_date" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.date') }}
                            </label>
                            <input id="schedule_date" type="date" name="schedule_date"
                                value="{{ old('schedule_date', \Carbon\Carbon::parse($schedule->schedule_date)->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 [color-scheme:light] dark:[color-scheme:dark] @error('schedule_date') border-red-500 @enderror"
                                required />
                            <x-input-error :messages="$errors->get('schedule_date')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.start_time') }}
                            </label>
                            <input id="start_time" type="hidden" name="start_time"
                                value="{{ old('start_time', \Carbon\Carbon::parse($schedule->schedule_date)->setTimeFromTimeString($schedule->start_time)->setTimezone(auth()->user()->unit->unitSettings->timezone ?? 'UTC')->format('H:i')) }}" />
                            <div id="times"
                                 class="mt-1 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2"
                                 aria-label="{{ __('schedule_link.time_selection_aria') }}">
                                <!-- Times will be rendered here -->
                            </div>
                            <p id="times-helper" class="text-sm text-gray-400 mt-2">
                                {{ __('schedule_link.choose_time') }}
                            </p>
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div>
                            <label for="unit_service_type_id" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.service_type') }}
                            </label>
                            <select id="unit_service_type_id" name="unit_service_type_id"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('unit_service_type_id') border-red-500 @enderror"
                                required>
                                <option value=""></option>
                                @foreach ($unitServiceTypes as $serviceType)
                                    <option value="{{ $serviceType->id }}"
                                        {{ old('unit_service_type_id', $schedule->unit_service_type_id) == $serviceType->id ? 'selected' : '' }}>
                                        {{ $serviceType->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('unit_service_type_id')" class="mt-2" />
                        </div>

                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.status') }}
                            </label>
                            <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('status') border-red-500 @enderror"
                                required>
                                <option value="pending"
                                    {{ old('status', $schedule->status) === App\Enum\ScheduleStatusEnum::PENDING->value ? 'selected' : '' }}>
                                    {{ __('schedules.statuses.pending') }}</option>
                                <option value="confirmed"
                                    {{ old('status', $schedule->status) === App\Enum\ScheduleStatusEnum::CONFIRMED->value ? 'selected' : '' }}>
                                    {{ __('schedules.statuses.confirmed') }}</option>
                                <option value="cancelled"
                                    {{ old('status', $schedule->status) === App\Enum\ScheduleStatusEnum::CANCELLED->value ? 'selected' : '' }}>
                                    {{ __('schedules.statuses.cancelled') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div>
                            <label for="notes" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.notes') }}
                            </label>
                            <textarea id="notes" name="notes"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('notes') border-red-500 @enderror"
                                rows="3">{{ old('notes', $schedule->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('schedules.weekly', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []) }}">
                                {{ __('schedules.back') }}
                            </x-cancel-link>

                            <!-- Update Button -->
                            <x-primary-button type="submit">
                                {{ __('actions.save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para mudar a unidade selecionada
        function changeUnit(unitId) {
            document.querySelector('input[name="unit_id"]').value = unitId;
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('unit_id', unitId);
            window.location.href = currentUrl.toString();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const scheduleDateInput = document.getElementById('schedule_date');
            const startTimeHidden = document.getElementById('start_time');
            const timesEl = document.getElementById('times');
            const unitIdHidden = document.querySelector('input[name="unit_id"]');

            function clearTimes(messageHtml = '') {
                timesEl.innerHTML = messageHtml || '';
            }

            function renderTimeButtons(times, preselect) {
                timesEl.innerHTML = '';
                (times || []).forEach(time => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'px-3 py-3 rounded-xl bg-gradient-to-br from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 text-white text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 border border-gray-600 hover:border-gray-500';
                    btn.textContent = time;
                    btn.setAttribute('aria-label', `Selecionar horário ${time}`);
                    const selectBtn = () => {
                        document.querySelectorAll('#times button').forEach(x => {
                            x.classList.remove('ring-2', 'ring-blue-500', 'scale-105', 'from-blue-600', 'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
                            x.classList.add('from-gray-700', 'to-gray-800', 'hover:from-gray-600', 'hover:to-gray-700');
                        });
                        btn.classList.add('ring-2', 'ring-blue-500', 'scale-105', 'from-blue-600', 'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
                        startTimeHidden.value = time;
                    };
                    btn.addEventListener('click', selectBtn);
                    timesEl.appendChild(btn);
                    if (preselect && time === preselect) {
                        selectBtn();
                    }
                });
            }

            function fetchTimesForDate(dateStr, preselectTime) {
                if (!dateStr) {
                    clearTimes('<div class="col-span-full text-gray-400 text-sm">Selecione uma data para ver horários.</div>');
                    return;
                }

                timesEl.innerHTML = '<div class="col-span-full flex justify-center py-6"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div></div>';

                const unitId = unitIdHidden ? unitIdHidden.value : '';
                const url = new URL(`{{ route('schedules.available-times') }}`, window.location.origin);
                url.searchParams.set('date', dateStr);
                if (unitId) url.searchParams.set('unit_id', unitId);

                fetch(url.toString())
                    .then(r => r.json())
                    .then(data => {
                        const times = (data && data.times) ? data.times : [];
                        if (times.length === 0) {
                            clearTimes('<div class="col-span-full text-center py-6 text-gray-400 text-sm">Nenhum horário disponível para esta data.</div>');
                            return;
                        }
                        renderTimeButtons(times, preselectTime);
                    })
                    .catch(() => {
                        clearTimes('<div class="col-span-full text-center py-6 text-red-400 text-sm">Erro ao carregar horários.</div>');
                    });
            }

            scheduleDateInput.addEventListener('change', function() {
                fetchTimesForDate(this.value);
            });

            // Initial load: fetch with current date and try to preselect current start time
            if (scheduleDateInput.value) {
                fetchTimesForDate(scheduleDateInput.value, startTimeHidden.value);
            } else {
                clearTimes('<div class="col-span-full text-gray-400 text-sm">Selecione uma data para ver horários.</div>');
            }
        });
    </script>
</x-app-layout>
