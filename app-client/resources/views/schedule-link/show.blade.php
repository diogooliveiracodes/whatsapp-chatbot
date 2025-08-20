<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <x-global.session-alerts />
        <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-4 sm:py-8 px-3 sm:px-6 lg:px-8">
            <div class="w-full max-w-4xl mx-auto">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">
                        {{ __('schedule_link.title', ['unit' => $unit->name]) }}</h1>
                    <p class="text-gray-400">Escolha uma data e horário disponível para seu agendamento</p>
                </div>

                <!-- Main Form Card -->
                <div
                    class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden">
                    <div class="p-4 sm:p-6 lg:p-8">
                        <form method="POST"
                            action="{{ route('schedule-link.store', ['company' => $company, 'unit' => $unit->id]) }}"
                            class="space-y-8" id="booking-form">
                            @csrf

                            <!-- Step 1: Personal Information -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div
                                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        1</div>
                                    <h2 class="text-xl font-semibold text-white">Informações Pessoais</h2>
                                </div>

                                                                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                    <div class="space-y-2">
                                        <label for="name" class="block text-gray-300 text-sm font-medium">
                                            {{ __('schedule_link.name') }} <span class="text-red-400">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            required placeholder="Digite seu nome completo"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700/50 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                    </div>
                                    <div class="space-y-2">
                                        <label for="phone" class="block text-gray-300 text-sm font-medium">
                                            {{ __('schedule_link.phone') }} <span class="text-red-400">*</span>
                                        </label>
                                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                            required placeholder="(11) 99999-9999"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700/50 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                                    </div>
                                </div>
                            </div>

                                                         <!-- Step 2: Service Selection -->
                             <div class="space-y-4">
                                 <div class="flex items-center space-x-3 mb-4">
                                     <div
                                         class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                         2</div>
                                     <h2 class="text-xl font-semibold text-white">Tipo de Serviço</h2>
                                 </div>

                                 <div class="space-y-4">
                                     <label class="block text-gray-300 text-sm font-medium">
                                         {{ __('schedules.service_type') }} <span class="text-red-400">*</span>
                                     </label>
                                     <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" role="radiogroup" aria-label="Seleção de tipo de serviço">
                                         @foreach($serviceTypes as $type)
                                             <div class="relative">
                                                 <input
                                                     type="radio"
                                                     id="service_{{ $type->id }}"
                                                     name="unit_service_type_id"
                                                     value="{{ $type->id }}"
                                                     @checked(old('unit_service_type_id') == $type->id)
                                                     class="sr-only"
                                                     required
                                                 >
                                                 <label
                                                     for="service_{{ $type->id }}"
                                                     class="block w-full h-24 p-4 rounded-xl border-2 border-gray-600 bg-gradient-to-br from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 cursor-pointer transition-all duration-300 shadow-lg hover:shadow-xl focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-800"
                                                 >
                                                     <div class="flex items-center space-x-3">
                                                         <div class="w-5 h-5 rounded-full border-2 border-gray-400 flex items-center justify-center transition-all duration-200">
                                                             <div class="w-2.5 h-2.5 rounded-full bg-blue-500 opacity-0 transition-all duration-200"></div>
                                                         </div>
                                                         <div class="flex-1">
                                                             <div class="text-white font-medium">{{ $type->name }}</div>
                                                             @if($type->description)
                                                                 <div class="text-gray-400 text-sm mt-1">{{ $type->description }}</div>
                                                             @endif
                                                         </div>
                                                     </div>
                                                 </label>
                                             </div>
                                         @endforeach
                                     </div>
                                     <x-input-error :messages="$errors->get('unit_service_type_id')" class="mt-1" />
                                 </div>
                             </div>

                            <!-- Step 3: Date and Time Selection -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div
                                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        3</div>
                                    <h2 class="text-xl font-semibold text-white">Data e Horário</h2>
                                </div>

                                <!-- Date Selection -->
                                <div class="space-y-4">
                                    <label class="block text-gray-300 text-sm font-medium">
                                        {{ __('schedule_link.choose_day') }} <span class="text-red-400">*</span>
                                    </label>
                                                                         <div class="relative">
                                         <div class="flex gap-2 sm:gap-3 overflow-x-auto p-2 sm:p-4 scrollbar-hide" id="calendar"
                                             role="group" aria-label="Seleção de data">
                                            <!-- Days will be rendered here -->
                                        </div>
                                        <div class="flex items-center justify-center mt-3 text-gray-400 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                            <span>Deslize para ver mais datas</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="schedule_date" id="schedule_date"
                                        value="{{ old('schedule_date') }}">
                                    <x-input-error :messages="$errors->get('schedule_date')" class="mt-1" />
                                </div>

                                <!-- Time Selection -->
                                <div class="space-y-4">
                                    <label class="block text-gray-300 text-sm font-medium">
                                        {{ __('schedule_link.choose_time') }} <span class="text-red-400">*</span>
                                    </label>
                                                                         <div id="times" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-3"
                                         role="group" aria-label="Seleção de horário">
                                        <!-- Times will be rendered here -->
                                    </div>
                                    <input type="hidden" name="start_time" id="start_time"
                                        value="{{ old('start_time') }}">
                                    <x-input-error :messages="$errors->get('start_time')" class="mt-1" />
                                </div>
                            </div>

                            <!-- Step 4: Additional Information -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div
                                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        4</div>
                                    <h2 class="text-xl font-semibold text-white">Informações Adicionais</h2>
                                </div>

                                <div class="space-y-2">
                                    <label for="notes" class="block text-gray-300 text-sm font-medium">
                                        {{ __('schedules.notes') }}
                                    </label>
                                    <textarea id="notes" name="notes" rows="3" placeholder="Alguma observação ou informação adicional..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700/50 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none">{{ old('notes') }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                                </div>
                            </div>

                            <!-- Error Messages -->
                            @if ($errors->has('general'))
                                <div class="bg-red-900/20 border border-red-700 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-red-400 text-sm">{{ $errors->first('general') }}</span>
                                    </div>
                                </div>
                            @endif

                                                         <!-- Submit Button -->
                             <div class="flex justify-center pt-4 sm:pt-6">
                                 <button type="submit" id="submit-button" disabled
                                     class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 disabled:from-gray-600 disabled:to-gray-700 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105 disabled:transform-none focus:ring-4 focus:ring-blue-500/50 focus:outline-none">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ __('actions.save') }}
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    const month = @json($month);
    const availableDays = @json($availableDays);
    const unitId = @json($unit->id);
    const companyId = @json($company);

    function renderCalendar(monthStr) {
        const [year, month] = monthStr.split('-').map(Number);
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';

        // Only render available days
        availableDays.forEach((dateStr, index) => {
            const date = new Date(dateStr);
            const dayOfWeek = date.getDay();
            const day = date.getDate();
            const monthName = date.toLocaleDateString('pt-BR', {
                month: 'short'
            }).toUpperCase();

            const isToday = dateStr === new Date().toISOString().split('T')[0];

            const card = document.createElement('button');
            card.type = 'button';
                         card.className =
                 'flex flex-col items-center justify-center min-w-[80px] sm:min-w-[90px] h-20 sm:h-24 rounded-xl border-2 transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800';
            card.setAttribute('aria-label', `Selecionar data ${day} de ${monthName}`);
            card.setAttribute('data-date', dateStr);

            if (isToday) {
                card.classList.add('bg-gradient-to-br', 'from-yellow-400', 'to-yellow-500', 'border-yellow-400',
                    'text-black', 'shadow-yellow-500/25');
            } else {
                card.classList.add('bg-gradient-to-br', 'from-gray-700', 'to-gray-800', 'border-gray-600',
                    'text-white', 'hover:from-gray-600', 'hover:to-gray-700', 'hover:border-gray-500');
            }

                         card.innerHTML = `
                     <div class="text-xs font-semibold opacity-80">${getDayName(dayOfWeek)}</div>
                     <div class="text-lg sm:text-xl font-bold leading-tight">${isToday ? 'HOJE' : day}</div>
                     <div class="text-xs opacity-80">${monthName}</div>
                 `;

            card.addEventListener('click', () => onSelectDate(dateStr, card));
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    onSelectDate(dateStr, card);
                }
            });

            calendar.appendChild(card);
        });

        // Auto-select first available day
        if (availableDays.length > 0) {
            const firstDayCard = calendar.querySelector('button');
            if (firstDayCard) {
                onSelectDate(availableDays[0], firstDayCard);
            }
        }
    }

    function getDayName(dayOfWeek) {
        const days = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB'];
        return days[dayOfWeek];
    }

    function onSelectDate(dateStr, btn) {
        // Remove selection from all day cards
        document.querySelectorAll('#calendar button').forEach(b => {
            b.classList.remove('ring-2', 'ring-blue-400', 'scale-105');
            if (b.classList.contains('from-yellow-400')) {
                b.classList.add('bg-gradient-to-br', 'from-yellow-400', 'to-yellow-500', 'border-yellow-400',
                    'text-black', 'shadow-yellow-500/25');
            } else {
                b.classList.add('bg-gradient-to-br', 'from-gray-700', 'to-gray-800', 'border-gray-600',
                    'text-white');
            }
        });

        // Add selection to clicked card
        btn.classList.add('ring-2', 'ring-blue-400', 'scale-105');
        document.getElementById('schedule_date').value = dateStr;

        // Show loading state
        const timesEl = document.getElementById('times');
        timesEl.innerHTML =
            '<div class="col-span-full flex justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div></div>';

        fetchTimes(dateStr);
    }

    function fetchTimes(dateStr) {
        const timesEl = document.getElementById('times');

        fetch(`${window.location.origin}/${companyId}/schedule-link/${unitId}/available-times?date=${dateStr}`)
            .then(r => r.json())
            .then(data => {
                timesEl.innerHTML = '';

                if (!data.times || data.times.length === 0) {
                    timesEl.innerHTML = `
                            <div class="col-span-full text-center py-8">
                                <div class="text-gray-400 text-sm">
                                    <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Nenhum horário disponível para esta data
                                </div>
                            </div>
                        `;
                    return;
                }

                (data.times || []).forEach(time => {
                    const b = document.createElement('button');
                    b.type = 'button';
                                         b.className =
                         'px-3 sm:px-4 py-3 sm:py-4 rounded-xl bg-gradient-to-br from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 text-white text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 border border-gray-600 hover:border-gray-500';
                    b.textContent = time;
                    b.setAttribute('aria-label', `Selecionar horário ${time}`);

                    b.addEventListener('click', () => {
                        document.querySelectorAll('#times button').forEach(x => {
                            x.classList.remove('ring-2', 'ring-blue-500', 'scale-105',
                                'from-blue-600', 'to-blue-700', 'hover:from-blue-700',
                                'hover:to-blue-800');
                            x.classList.add('from-gray-700', 'to-gray-800',
                                'hover:from-gray-600', 'hover:to-gray-700');
                        });
                        b.classList.add('ring-2', 'ring-blue-500', 'scale-105', 'from-blue-600',
                            'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
                        document.getElementById('start_time').value = time;
                        updateSubmitEnabled();
                    });

                    b.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            b.click();
                        }
                    });

                    timesEl.appendChild(b);
                });
                updateSubmitEnabled();
            })
            .catch(() => {
                timesEl.innerHTML = `
                        <div class="col-span-full text-center py-8">
                            <div class="text-red-400 text-sm">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Erro ao carregar horários
                            </div>
                        </div>
                    `;
                updateSubmitEnabled();
            });
    }

         function updateSubmitEnabled() {
         const name = document.querySelector('input[name="name"]').value.trim();
         const phone = document.querySelector('input[name="phone"]').value.trim();
         const service = document.querySelector('input[name="unit_service_type_id"]:checked')?.value || '';
         const date = document.getElementById('schedule_date').value;
         const time = document.getElementById('start_time').value;
         const can = name && phone && service && date && time;

        const submitBtn = document.getElementById('submit-button');
        submitBtn.disabled = !can;

        // Update button text based on state
        const btnText = submitBtn.querySelector('span');
        if (can) {
            btnText.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('actions.save') }}
                `;
        } else {
            btnText.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Preencha todos os campos obrigatórios
                `;
        }
    }

    // Add smooth scrolling for calendar
    function addSmoothScrolling() {
        const calendar = document.getElementById('calendar');
        let isDown = false;
        let startX;
        let scrollLeft;

        calendar.addEventListener('mousedown', (e) => {
            isDown = true;
            calendar.style.cursor = 'grabbing';
            startX = e.pageX - calendar.offsetLeft;
            scrollLeft = calendar.scrollLeft;
        });

        calendar.addEventListener('mouseleave', () => {
            isDown = false;
            calendar.style.cursor = 'grab';
        });

        calendar.addEventListener('mouseup', () => {
            isDown = false;
            calendar.style.cursor = 'grab';
        });

        calendar.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - calendar.offsetLeft;
            const walk = (x - startX) * 2;
            calendar.scrollLeft = scrollLeft - walk;
        });
    }

         // Initialize
     document.addEventListener('DOMContentLoaded', () => {
         document.addEventListener('input', updateSubmitEnabled);
         renderCalendar(month);
         addSmoothScrolling();
         updateSubmitEnabled();

                  // Add radio button functionality for service types
         document.querySelectorAll('input[name="unit_service_type_id"]').forEach(radio => {
             radio.addEventListener('change', function() {
                 // Remove selected state from all labels
                 document.querySelectorAll('label[for^="service_"]').forEach(label => {
                     label.classList.remove('border-blue-500', 'from-blue-600', 'to-blue-700');
                     label.classList.add('border-gray-600', 'from-gray-700', 'to-gray-800');
                     const radioCircle = label.querySelector('.w-5.h-5');
                     const radioDot = label.querySelector('.w-2\\.5.h-2\\.5');
                     radioCircle.classList.remove('border-blue-500');
                     radioCircle.classList.add('border-gray-400');
                     radioDot.classList.remove('opacity-100');
                     radioDot.classList.add('opacity-0');
                 });

                 // Add selected state to current label
                 if (this.checked) {
                     const label = document.querySelector(`label[for="${this.id}"]`);
                     label.classList.remove('border-gray-600', 'from-gray-700', 'to-gray-800');
                     label.classList.add('border-blue-500', 'from-blue-600', 'to-blue-700');
                     const radioCircle = label.querySelector('.w-5.h-5');
                     const radioDot = label.querySelector('.w-2\\.5.h-2\\.5');
                     radioCircle.classList.remove('border-gray-400');
                     radioCircle.classList.add('border-blue-500');
                     radioDot.classList.remove('opacity-0');
                     radioDot.classList.add('opacity-100');
                 }

                 // Update submit button state
                 updateSubmitEnabled();
             });
         });

         // Trigger change event for pre-selected radio button
         const preSelectedRadio = document.querySelector('input[name="unit_service_type_id"]:checked');
         if (preSelectedRadio) {
             preSelectedRadio.dispatchEvent(new Event('change'));
         }
     });
</script>

</html>
