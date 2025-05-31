@props(['schedules', 'companySettings'])

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div id="calendar" class="h-[800px] [&_.fc-theme-standard]:dark:border-gray-700 [&_.fc-theme-standard_td]:dark:border-gray-700 [&_.fc-theme-standard_th]:dark:border-gray-700 [&_.fc-theme-standard_td]:dark:bg-gray-800 [&_.fc-theme-standard_th]:dark:bg-gray-800 [&_.fc-col-header-cell]:dark:bg-gray-800 [&_.fc-daygrid-day]:dark:bg-gray-800 [&_.fc-timegrid-slot]:dark:bg-gray-800 [&_.fc-timegrid-slot-label]:dark:text-gray-400 [&_.fc-timegrid-axis]:dark:text-gray-400 [&_.fc-timegrid-axis-cushion]:dark:text-gray-400 [&_.fc-col-header-cell-cushion]:dark:text-gray-400 [&_.fc-daygrid-day-number]:dark:text-gray-400 [&_.fc-button]:dark:bg-gray-700 [&_.fc-button]:dark:text-gray-300 [&_.fc-button]:dark:border-gray-600 [&_.fc-button:hover]:dark:bg-gray-600 [&_.fc-button-active]:dark:bg-indigo-600 [&_.fc-button-active]:dark:border-indigo-600 [&_.fc-button-active:hover]:dark:bg-indigo-700 [&_.fc-toolbar-title]:dark:text-gray-100 [&_.fc-event]:dark:border-gray-600 [&_.fc-event-title]:dark:text-gray-100 [&_.fc-event-time]:dark:text-gray-300"></div>
        </div>
    </div>
</div>

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endpush

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const schedulesData = @json($schedules);
            const companySettings = @json($companySettings);

            const events = schedulesData.map(schedule => ({
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
                backgroundColor: schedule.status === 'confirmed' ? '#10B981' :
                               schedule.status === 'pending' ? '#F59E0B' :
                               schedule.status === 'cancelled' ? '#EF4444' : '#6B7280',
                borderColor: schedule.status === 'confirmed' ? '#059669' :
                           schedule.status === 'pending' ? '#D97706' :
                           schedule.status === 'cancelled' ? '#DC2626' : '#4B5563'
            }));

            const workingDays = [];
            for (let i = companySettings.working_day_start; i <= companySettings.working_day_end; i++) {
                workingDays.push(i);
            }

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
                businessHours: {
                    daysOfWeek: workingDays,
                    startTime: companySettings.working_hour_start,
                    endTime: companySettings.working_hour_end,
                },
                selectConstraint: 'businessHours',
                eventConstraint: 'businessHours',
                hiddenDays: [0, 1, 2, 3, 4, 5, 6].filter(day => !workingDays.includes(day + 1)),
                slotMinTime: companySettings.working_hour_start,
                slotMaxTime: companySettings.working_hour_end,
                eventContent: function(arg) {
                    return {
                        html: `
                            <div class="fc-content">
                                <div class="fc-title font-medium text-gray-900 dark:text-gray-100">${arg.event.title}</div>
                                <div class="fc-description text-sm text-gray-600 dark:text-gray-400">${arg.event.extendedProps.service_type}</div>
                            </div>
                        `
                    };
                }
            });

            calendar.render();
        });

        function handleEventClick(info) {
            const event = info.event;
            window.dispatchEvent(new CustomEvent('schedule-edit', {
                detail: {
                    id: event.id,
                    customer: event.extendedProps.customer,
                    service_type: event.extendedProps.service_type,
                    start: event.start,
                    end: event.end,
                    notes: event.extendedProps.notes
                }
            }));
        }

        function handleDateSelect(info) {
            const companySettings = @json($companySettings);
            const startTime = info.start.toTimeString().slice(0, 8);
            const endTime = info.end.toTimeString().slice(0, 8);

            if (startTime < companySettings.working_hour_start || endTime > companySettings.working_hour_end) {
                alert('O agendamento deve estar dentro do horário de funcionamento.');
                return;
            }

            window.dispatchEvent(new CustomEvent('schedule-create', {
                detail: {
                    start: info.start,
                    end: info.end
                }
            }));
        }
    </script>
@endpush
