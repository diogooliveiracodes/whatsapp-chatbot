<x-app-layout>
    <x-global.header>
        {{ __('schedules.new_schedule') }}
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

                    <form method="POST" action="{{ route('schedules.store') }}" class="space-y-6">
                        @csrf

                        <!-- Hidden field for unit_id -->
                        <input type="hidden" name="unit_id" value="{{ $selectedUnit->id }}" />

                        <div>
                            <label for="customer_search" class="block font-medium text-sm text-gray-300">
                                {{ __('customers.client') }}
                            </label>
                            <div class="relative">
                                <input type="text" id="customer_search"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('customer_id') border-red-500 @enderror"
                                    placeholder="{{ __('customers.search_placeholder') }}" autocomplete="off" />
                                <input type="hidden" id="customer_id" name="customer_id"
                                    value="{{ old('customer_id') }}" required />
                                <div id="customer_results"
                                    class="absolute z-10 w-full mt-1 bg-gray-700 border border-gray-600 rounded-md shadow-lg hidden max-h-60 overflow-y-auto">
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                            <div id="customer_not_found_error" class="hidden mt-2">
                                <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                                    <li>{{ __('schedules.messages.customer_not_found') }}</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <label for="schedule_date" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.date') }}
                            </label>
                            <input id="schedule_date" type="date" name="schedule_date"
                                value="{{ request('schedule_date', old('schedule_date')) }}"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 [color-scheme:light] dark:[color-scheme:dark] @error('schedule_date') border-red-500 @enderror"
                                required />
                            <x-input-error :messages="$errors->get('schedule_date')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.start_time') }}
                            </label>
                            <input id="start_time" type="hidden" name="start_time" value="{{ request('start_time', old('start_time')) }}" />
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
                                        {{ old('unit_service_type_id') == $serviceType->id ? 'selected' : '' }}>
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
                                    {{ old('status', App\Enum\ScheduleStatusEnum::PENDING->value) === App\Enum\ScheduleStatusEnum::PENDING->value ? 'selected' : '' }}>
                                    {{ __('schedules.statuses.pending') }}</option>
                                <option value="confirmed"
                                    {{ old('status') === App\Enum\ScheduleStatusEnum::CONFIRMED->value ? 'selected' : '' }}>
                                    {{ __('schedules.statuses.confirmed') }}</option>
                                <option value="cancelled"
                                    {{ old('status') === App\Enum\ScheduleStatusEnum::CANCELLED->value ? 'selected' : '' }}>
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
                                rows="3">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('schedules.weekly', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []) }}">
                                {{ __('schedules.back') }}
                            </x-cancel-link>

                            <!-- Create Button -->
                            <x-primary-button type="submit" id="submit-button" disabled
                                class="disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-600 disabled:hover:bg-gray-600">
                                {{ __('actions.save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerSearch = document.getElementById('customer_search');
            const customerId = document.getElementById('customer_id');
            const customerResults = document.getElementById('customer_results');
            const submitButton = document.getElementById('submit-button');
            const scheduleDateInput = document.getElementById('schedule_date');
            const startTimeHidden = document.getElementById('start_time');
            const timesEl = document.getElementById('times');
            const unitIdHidden = document.querySelector('input[name="unit_id"]');

            // Dados dos clientes disponíveis na página
            const customers = @json($customers);



            // Função para verificar se o cliente foi selecionado corretamente
            function checkCustomerSelection() {
                const searchValue = customerSearch.value.trim();
                const customerIdValue = customerId.value;

                // Verificar se há um ID válido e se o nome corresponde
                if (customerIdValue) {
                    const validCustomer = customers.find(customer => customer.id == customerIdValue);
                    if (validCustomer && searchValue.toLowerCase() === validCustomer.name.toLowerCase()) {
                        submitButton.disabled = false;
                        return true;
                    }
                }

                submitButton.disabled = true;
                return false;
            }

            // Se há um valor antigo, mostrar o nome do cliente
            if (customerId.value) {
                const selectedCustomer = customers.find(c => c.id == customerId.value);
                if (selectedCustomer) {
                    customerSearch.value = selectedCustomer.name;
                    checkCustomerSelection(); // Verificar se o cliente antigo é válido
                }
            }

            // Função para filtrar clientes
            function filterCustomers(query) {
                if (query.length < 2) {
                    // Se não há texto suficiente, mostrar todos os clientes
                    displayResults(customers);
                    return;
                }

                const filtered = customers.filter(customer =>
                    customer.name.toLowerCase().includes(query.toLowerCase()) ||
                    (customer.phone && customer.phone.includes(query))
                );

                displayResults(filtered);
            }

            // Função para exibir resultados
            function displayResults(customers) {
                if (customers.length === 0) {
                    customerResults.classList.add('hidden');
                    return;
                }

                customerResults.innerHTML = '';
                customers.forEach(customer => {
                    const div = document.createElement('div');
                    div.className =
                        'px-4 py-2 hover:bg-gray-600 cursor-pointer text-gray-300 border-b border-gray-600 last:border-b-0';
                    div.textContent = customer.name;
                    div.addEventListener('click', () => {
                        customerSearch.value = customer.name;
                        customerId.value = customer.id;
                        customerResults.classList.add('hidden');
                        // Esconder mensagem de erro quando cliente é selecionado
                        document.getElementById('customer_not_found_error').classList.add('hidden');
                        validateCustomerName(); // Validar após seleção
                        checkCustomerSelection(); // Verificar se o botão deve ser habilitado
                    });
                    customerResults.appendChild(div);
                });

                customerResults.classList.remove('hidden');
            }

            // Event listener para input
            customerSearch.addEventListener('input', function() {
                const query = this.value.trim();
                filterCustomers(query);

                if (query.length < 2) {
                    customerId.value = '';
                }

                // Validar se o nome digitado existe na lista
                validateCustomerName();
                // Verificar se o botão deve ser habilitado
                checkCustomerSelection();
            });

            // Event listener para focus - mostrar todos os clientes quando clicar no input
            customerSearch.addEventListener('focus', function() {
                const query = this.value.trim();
                if (query.length < 2) {
                    // Se não há texto digitado, mostrar todos os clientes
                    displayResults(customers);
                } else {
                    // Se há texto, filtrar normalmente
                    filterCustomers(query);
                }
            });

            // Função para validar se o nome do cliente existe
            function validateCustomerName() {
                const searchValue = customerSearch.value.trim();
                const customerNotFoundError = document.getElementById('customer_not_found_error');
                const exists = customers.some(customer =>
                    customer.name.toLowerCase() === searchValue.toLowerCase()
                );

                if (searchValue && !exists) {
                    customerSearch.classList.add('border-red-500');
                    customerSearch.classList.remove('border-gray-600');
                    customerId.value = '';
                    customerNotFoundError.classList.remove('hidden');
                } else {
                    customerSearch.classList.remove('border-red-500');
                    customerSearch.classList.add('border-gray-600');
                    customerNotFoundError.classList.add('hidden');
                }

                // Verificar se o botão deve ser habilitado
                checkCustomerSelection();
            }

            // Validar antes do envio do formulário
            document.querySelector('form').addEventListener('submit', function(e) {
                // Se o botão está desabilitado, não permitir envio
                if (submitButton.disabled) {
                    e.preventDefault();
                    alert('Por favor, selecione um cliente válido antes de salvar.');
                    customerSearch.focus();
                    return false;
                }

                // Se chegou até aqui, o cliente foi validado corretamente
                // Permitir o envio do formulário
            });

            // Esconder resultados quando clicar fora
            document.addEventListener('click', function(e) {
                if (!customerSearch.contains(e.target) && !customerResults.contains(e.target)) {
                    customerResults.classList.add('hidden');
                }
            });

            // Navegação com teclado
            customerSearch.addEventListener('keydown', function(e) {
                const visibleResults = customerResults.querySelectorAll('div:not(.hidden)');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const firstResult = visibleResults[0];
                    if (firstResult) {
                        visibleResults.forEach(r => r.classList.remove('bg-indigo-600'));
                        firstResult.classList.add('bg-indigo-600');
                        firstResult.focus();
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const lastResult = visibleResults[visibleResults.length - 1];
                    if (lastResult) {
                        visibleResults.forEach(r => r.classList.remove('bg-indigo-600'));
                        lastResult.classList.add('bg-indigo-600');
                        lastResult.focus();
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    const selectedResult = customerResults.querySelector('.bg-indigo-600');
                    if (selectedResult) {
                        selectedResult.click();
                    }
                } else if (e.key === 'Escape') {
                    customerResults.classList.add('hidden');
                }
            });

            // --- Times fetching similar to public link ---
            function clearTimes(messageHtml = '') {
                timesEl.innerHTML = messageHtml || '';
                startTimeHidden.value = '';
                updateSubmitDisabled();
            }

            function renderTimeButtons(times) {
                timesEl.innerHTML = '';
                (times || []).forEach(time => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'px-3 py-3 rounded-xl bg-gradient-to-br from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 text-white text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 border border-gray-600 hover:border-gray-500';
                    btn.textContent = time;
                    btn.setAttribute('aria-label', `Selecionar horário ${time}`);
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('#times button').forEach(x => {
                            x.classList.remove('ring-2', 'ring-blue-500', 'scale-105', 'from-blue-600', 'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
                            x.classList.add('from-gray-700', 'to-gray-800', 'hover:from-gray-600', 'hover:to-gray-700');
                        });
                        btn.classList.add('ring-2', 'ring-blue-500', 'scale-105', 'from-blue-600', 'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
                        startTimeHidden.value = time;
                        updateSubmitDisabled();
                        setTimeout(() => submitButton.scrollIntoView({ behavior: 'smooth', block: 'center' }), 200);
                    });
                    timesEl.appendChild(btn);
                });
            }

            function fetchTimesForDate(dateStr) {
                if (!dateStr) {
                    clearTimes('<div class="col-span-full text-gray-400 text-sm">Selecione uma data para ver horários.</div>');
                    return;
                }

                // Loading
                timesEl.innerHTML = '<div class="col-span-full flex justify-center py-6"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div></div>';
                startTimeHidden.value = '';

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
                        renderTimeButtons(times);
                    })
                    .catch(() => {
                        clearTimes('<div class="col-span-full text-center py-6 text-red-400 text-sm">Erro ao carregar horários.</div>');
                    });
            }

            function updateSubmitDisabled() {
                // Keep existing customer validation, and also require start_time
                const disabledByCustomer = submitButton.disabled;
                const hasStart = !!startTimeHidden.value;
                submitButton.disabled = disabledByCustomer || !hasStart;
            }

            scheduleDateInput.addEventListener('change', function() {
                fetchTimesForDate(this.value);
            });

            // If page opened with a preselected date, fetch immediately
            if (scheduleDateInput.value) {
                fetchTimesForDate(scheduleDateInput.value);
            } else {
                clearTimes('<div class="col-span-full text-gray-400 text-sm">Selecione uma data para ver horários.</div>');
            }
        });

        // Função para mudar a unidade selecionada
        function changeUnit(unitId) {
            // Atualizar o campo hidden
            document.querySelector('input[name="unit_id"]').value = unitId;

            // Redirecionar para a mesma página com o novo unit_id
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('unit_id', unitId);
            window.location.href = currentUrl.toString();
        }
    </script>
</x-app-layout>
