<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agenda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal de Criação/Edição -->
    <div id="scheduleModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">
                    Novo Agendamento
                </h2>

                <form id="scheduleForm" class="space-y-4" method="POST">
                    @csrf
                    <input type="hidden" id="schedule_id" name="schedule_id">
                    <input type="hidden" id="customer_id" name="customer_id">

                    <div>
                        <label for="service_type" class="block font-medium text-sm text-gray-700">Tipo de
                            Serviço</label>
                        <input type="text" id="service_type" name="service_type"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <label for="schedule_date" class="block font-medium text-sm text-gray-700">Data</label>
                        <input type="date" id="schedule_date" name="schedule_date"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block font-medium text-sm text-gray-700">Início</label>
                            <input type="time" id="start_time" name="start_time"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label for="end_time" class="block font-medium text-sm text-gray-700">Fim</label>
                            <input type="time" id="end_time" name="end_time"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block font-medium text-sm text-gray-700">Observações</label>
                        <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
        <style>
            #calendar {
                height: 800px;
            }

            .fc-event {
                cursor: pointer;
            }
        </style>
    @endpush

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded');
                const calendarEl = document.getElementById('calendar');
                console.log('Calendar element:', calendarEl);

                const schedulesData = @json($schedules);
                console.log('Raw schedules data:', schedulesData);

                // Process the events data
                const events = schedulesData.map(schedule => {
                    console.log('Processing schedule:', schedule);
                    return {
                        id: schedule.id,
                        title: schedule.title,
                        start: schedule.start,
                        end: schedule.end,
                        extendedProps: {
                            status: schedule.status,
                            service_type: schedule.service_type,
                            notes: schedule.notes,
                            customer: schedule.customer,
                            user: schedule.user
                        },
                        backgroundColor: schedule.status === 'confirmed' ? '#10B981' : schedule.status ===
                            'pending' ? '#F59E0B' : schedule.status === 'cancelled' ? '#EF4444' : '#6B7280',
                        borderColor: schedule.status === 'confirmed' ? '#059669' : schedule.status ===
                            'pending' ? '#D97706' : schedule.status === 'cancelled' ? '#DC2626' : '#4B5563'
                    };
                });

                console.log('Processed events:', events);

                if (!calendarEl) {
                    console.error('Calendar element not found');
                    return;
                }

                // Get company settings from the server
                const companySettings = @json($unit->company->companySettings);
                console.log('Company settings:', companySettings);

                const workingDays = [];
                for (let i = companySettings.working_day_start; i <= companySettings.working_day_end; i++) {
                    workingDays.push(i);
                }
                console.log('Working days:', workingDays);

                try {
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'timeGridWeek',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        locale: 'pt-br',
                        events: events,
                        editable: true,
                        selectable: true,
                        selectMirror: true,
                        dayMaxEvents: true,
                        weekends: true,
                        eventClick: handleEventClick,
                        select: handleDateSelect,
                        height: 'auto',
                        // Configure business hours
                        businessHours: {
                            daysOfWeek: workingDays,
                            startTime: companySettings.working_hour_start,
                            endTime: companySettings.working_hour_end,
                        },
                        // Disable selection outside business hours
                        selectConstraint: 'businessHours',
                        // Disable event dragging outside business hours
                        eventConstraint: 'businessHours',
                        // Hide non-working days
                        hiddenDays: [0, 1, 2, 3, 4, 5, 6].filter(day => !workingDays.includes(day + 1)),
                        // Set min and max time to match business hours
                        slotMinTime: companySettings.working_hour_start,
                        slotMaxTime: companySettings.working_hour_end,
                        eventDidMount: function(info) {
                            console.log('Event mounted:', info.event);
                        },
                        eventContent: function(arg) {
                            console.log('Event content:', arg);
                            return {
                                html: `
                                <div class="fc-content">
                                    <div class="fc-title">${arg.event.title}</div>
                                    <div class="fc-description">${arg.event.extendedProps.service_type}</div>
                                </div>
                            `
                            };
                        }
                    });
                    console.log('Calendar created');
                    calendar.render();
                    console.log('Calendar rendered');
                } catch (error) {
                    console.error('Error creating calendar:', error);
                }
            });

            // Manipuladores de eventos
            function handleEventClick(info) {
                const event = info.event;
                document.getElementById('modalTitle').textContent = 'Editar Agendamento';
                document.getElementById('schedule_id').value = event.id;
                document.getElementById('customer_id').value = event.extendedProps.customer.id;
                document.getElementById('service_type').value = event.extendedProps.service_type;

                // Parse the start date and time
                const startDate = new Date(event.start);
                document.getElementById('schedule_date').value = startDate.toISOString().split('T')[0];
                document.getElementById('start_time').value = startDate.toTimeString().slice(0, 5);

                // Parse the end time
                const endDate = new Date(event.end);
                document.getElementById('end_time').value = endDate.toTimeString().slice(0, 5);

                document.getElementById('notes').value = event.extendedProps.notes || '';
                openModal();
            }

            function handleDateSelect(info) {
                // Check if the selected time is within business hours
                const companySettings = @json($unit->company->companySettings);
                const startTime = info.start.toTimeString().slice(0, 8);
                const endTime = info.end.toTimeString().slice(0, 8);

                if (startTime < companySettings.working_hour_start || endTime > companySettings.working_hour_end) {
                    alert('O agendamento deve estar dentro do horário de funcionamento.');
                    return;
                }

                document.getElementById('modalTitle').textContent = 'Novo Agendamento';
                document.getElementById('schedule_id').value = '';
                document.getElementById('customer_id').value = '';
                document.getElementById('service_type').value = '';

                // Set the date and times
                document.getElementById('schedule_date').value = info.startStr.split('T')[0];
                document.getElementById('start_time').value = info.start.toTimeString().slice(0, 5);
                document.getElementById('end_time').value = info.end.toTimeString().slice(0, 5);

                document.getElementById('notes').value = '';
                openModal();
            }

            // Manipulador do formulário
            document.getElementById('scheduleForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const scheduleId = formData.get('schedule_id');

                const scheduleDate = formData.get('schedule_date');
                const startTime = formData.get('start_time');
                const endTime = formData.get('end_time');

                if (!scheduleDate || !startTime || !endTime) {
                    alert('Por favor, preencha todos os campos obrigatórios.');
                    return;
                }

                const formattedData = new FormData();
                formattedData.append('_token', formData.get('_token'));

                if (scheduleId) {
                    formattedData.append('schedule_id', scheduleId);
                    formattedData.append('_method', 'PUT'); // Laravel fake PUT
                }

                formattedData.append('customer_id', formData.get('customer_id') || '');
                formattedData.append('service_type', formData.get('service_type') || '');
                formattedData.append('notes', formData.get('notes') || '');
                formattedData.append('schedule_date', scheduleDate);
                formattedData.append('start_time', startTime);
                formattedData.append('end_time', endTime);
                formattedData.append('status', 'pending');

                const url = scheduleId ? `/schedules/${scheduleId}` : '/schedules';

                fetch(url, {
                        method: 'POST', // sempre POST
                        body: formattedData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                let message = data.message || 'Erro ao processar a requisição';
                                if (data.errors) {
                                    message += '\n' + Object.values(data.errors).flat().join('\n');
                                }
                                throw new Error(message);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message || 'Erro ao processar o agendamento');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message || 'Erro ao processar o agendamento');
                    });
            });


            function openModal() {
                document.getElementById('scheduleModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('scheduleModal').classList.add('hidden');
                document.getElementById('scheduleForm').reset();
            }
        </script>
    @endpush
</x-app-layout>
