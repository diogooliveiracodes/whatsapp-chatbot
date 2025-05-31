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

                <form id="scheduleForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="schedule_id" name="schedule_id">

                    <div>
                        <label for="customer_id" class="block font-medium text-sm text-gray-700">Cliente</label>
                        <select id="customer_id" name="customer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Selecione um cliente</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="service_type" class="block font-medium text-sm text-gray-700">Tipo de Serviço</label>
                        <input type="text" id="service_type" name="service_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block font-medium text-sm text-gray-700">Início</label>
                            <input type="datetime-local" id="start_time" name="start_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label for="end_time" class="block font-medium text-sm text-gray-700">Fim</label>
                            <input type="datetime-local" id="end_time" name="end_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block font-medium text-sm text-gray-700">Observações</label>
                        <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancelar
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
            console.log('Schedules data:', @json($schedules));

            if (!calendarEl) {
                console.error('Calendar element not found');
                return;
            }

            try {
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    locale: 'pt-br',
                    events: @json($schedules),
                    editable: true,
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: true,
                    weekends: true,
                    eventClick: handleEventClick,
                    select: handleDateSelect,
                    height: 'auto'
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
            document.getElementById('start_time').value = event.start.toISOString().slice(0, 16);
            document.getElementById('end_time').value = event.end.toISOString().slice(0, 16);
            document.getElementById('notes').value = event.extendedProps.notes || '';
            openModal();
        }

        function handleDateSelect(info) {
            document.getElementById('modalTitle').textContent = 'Novo Agendamento';
            document.getElementById('schedule_id').value = '';
            document.getElementById('customer_id').value = '';
            document.getElementById('service_type').value = '';
            document.getElementById('start_time').value = info.startStr.slice(0, 16);
            document.getElementById('end_time').value = info.endStr.slice(0, 16);
            document.getElementById('notes').value = '';
            openModal();
        }

        // Manipulador do formulário
        document.getElementById('scheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const scheduleId = formData.get('schedule_id');

            if (scheduleId) {
                // Atualizar agendamento existente
                fetch(`/schedules/${scheduleId}`, {
                    method: 'PUT',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            } else {
                // Criar novo agendamento
                fetch('/schedules', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            }
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
