@props(['schedules', 'unitSettings'])

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div id="calendar"
                class="h-[800px] [&_.fc-theme-standard]:dark:border-gray-700 [&_.fc-theme-standard_td]:dark:border-gray-700 [&_.fc-theme-standard_th]:dark:border-gray-700 [&_.fc-theme-standard_td]:dark:bg-gray-800 [&_.fc-theme-standard_th]:dark:bg-gray-800 [&_.fc-col-header-cell]:dark:bg-gray-800 [&_.fc-daygrid-day]:dark:bg-gray-800 [&_.fc-timegrid-slot]:dark:bg-gray-800 [&_.fc-timegrid-slot-label]:dark:text-gray-400 [&_.fc-timegrid-axis]:dark:text-gray-400 [&_.fc-timegrid-axis-cushion]:dark:text-gray-400 [&_.fc-col-header-cell-cushion]:dark:text-gray-400 [&_.fc-daygrid-day-number]:dark:text-gray-400 [&_.fc-button]:dark:bg-gray-700 [&_.fc-button]:dark:text-gray-300 [&_.fc-button]:dark:border-gray-600 [&_.fc-button:hover]:dark:bg-gray-600 [&_.fc-button-active]:dark:bg-indigo-600 [&_.fc-button-active]:dark:border-indigo-600 [&_.fc-button-active:hover]:dark:bg-indigo-700 [&_.fc-toolbar-title]:dark:text-gray-100 [&_.fc-event]:dark:border-gray-600 [&_.fc-event-title]:dark:text-gray-100 [&_.fc-event-time]:dark:text-gray-300 [&_.fc-timegrid-axis]:dark:bg-gray-800 [&_.fc-timegrid-axis-cushion]:dark:bg-gray-800 [&_.fc-timegrid-now-indicator-line]:!border-red-500 [&_.fc-timegrid-now-indicator-line]:!border-4 [&_.fc-timegrid-now-indicator-arrow]:!border-red-500 [&_.fc-timegrid-now-indicator-arrow]:!border-4">
            </div>
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
            if (!calendarEl) {
                throw new Error('Calendar element not found');
            }

            const schedulesData = @json($schedules);
            const unitSettings = @json($unitSettings);

            if (!unitSettings) {
                throw new Error('Unit settings is null or undefined');
            }

            if (!unitSettings.sunday && !unitSettings.monday && !unitSettings.tuesday && !unitSettings.wednesday &&
                !unitSettings.thursday && !unitSettings.friday && !unitSettings.saturday) {
                throw new Error('Working days not configured in unit settings');
            }

            if (!unitSettings.working_hour_start || !unitSettings.working_hour_end) {
                throw new Error('Working hours not configured in unit settings');
            }

            // Format working hours to HH:mm:ss format
            const formatTime = (time) => {
                if (!time) return null;

                // Handle datetime objects from Laravel
                if (typeof time === 'object' && time.date) {
                    const dateTime = new Date(time.date);
                    return dateTime.toTimeString().slice(0, 8);
                }

                // Handle string times
                if (typeof time === 'string') {
                    if (time.includes('T')) {
                        // Handle ISO string
                        return new Date(time).toTimeString().slice(0, 8);
                    }
                    return time.includes(':') ? time : `${time}:00`;
                }

                // Handle Date objects
                if (time instanceof Date) {
                    return time.toTimeString().slice(0, 8);
                }

                console.error('Invalid time format:', time);
                return null;
            };

            const workingHourStart = formatTime(unitSettings.working_hour_start);
            const workingHourEnd = formatTime(unitSettings.working_hour_end);

            if (!workingHourStart || !workingHourEnd) {
                throw new Error('Invalid working hours format');
            }

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
                backgroundColor: schedule.status === 'confirmed' ? '#22C55E' : schedule.status ===
                    'pending' ? '#3B82F6' : schedule.status === 'cancelled' ? '#EF4444' : '#6B7280',
                borderColor: schedule.status === 'confirmed' ? '#16A34A' : schedule.status ===
                    'pending' ? '#2563EB' : schedule.status === 'cancelled' ? '#DC2626' : '#4B5563'
            }));

            const workingDays = [];
            const dayMapping = {
                'sunday': unitSettings.sunday,
                'monday': unitSettings.monday,
                'tuesday': unitSettings.tuesday,
                'wednesday': unitSettings.wednesday,
                'thursday': unitSettings.thursday,
                'friday': unitSettings.friday,
                'saturday': unitSettings.saturday
            };

            Object.entries(dayMapping).forEach(([day, value], index) => {
                if (value === true) {
                    workingDays.push(index);
                }
            });

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                initialDate: new Date(),
                nowIndicator: true,
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
                    startTime: workingHourStart,
                    endTime: workingHourEnd,
                },
                selectConstraint: 'businessHours',
                eventConstraint: 'businessHours',
                hiddenDays: [0, 1, 2, 3, 4, 5, 6].filter(day => !workingDays.includes(day)),
                slotMinTime: workingHourStart,
                slotMaxTime: workingHourEnd,
                slotDuration: '00:30:00',
                slotLabelInterval: '01:00',
                allDaySlot: false,
                eventContent: function(arg) {
                    return {
                        html: `
                                <div class="fc-content">
                                    <div class="fc-title font-medium text-white">${arg.event.title}</div>
                                    <div class="fc-description text-sm text-white/90">${arg.event.extendedProps.service_type}</div>
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
                    notes: event.extendedProps.notes,
                    status: event.extendedProps.status
                }
            }));
        }

        function handleDateSelect(info) {
            const unitSettings = @json($unitSettings);
            const startTime = info.start.toTimeString().slice(0, 8);
            const endTime = info.end.toTimeString().slice(0, 8);

            if (startTime < unitSettings.working_hour_start || endTime > unitSettings.working_hour_end) {
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
