@props(['title' => 'Novo Agendamento'])

<div id="scheduleModal" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 hidden" style="z-index: 9999;">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md shadow-xl">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4" id="modalTitle">
                {{ $title }}
            </h2>

            <x-schedule-form />
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.openScheduleModal = function() {
        document.getElementById('scheduleModal').classList.remove('hidden');
    }

    window.closeScheduleModal = function() {
        document.getElementById('scheduleModal').classList.add('hidden');
        document.getElementById('scheduleForm').reset();
    }

    // Listen for schedule edit events only
    window.addEventListener('schedule-edit', function(e) {
        const { id, customer, service_type, start, end, notes } = e.detail;

        document.getElementById('modalTitle').textContent = 'Editar Agendamento';
        document.getElementById('schedule_id').value = id;
        document.getElementById('customer_id').value = customer.id;
        document.getElementById('service_type').value = service_type;

        const startDate = new Date(start);
        document.getElementById('schedule_date').value = startDate.toISOString().split('T')[0];
        document.getElementById('start_time').value = startDate.toTimeString().slice(0, 5);

        const endDate = new Date(end);
        document.getElementById('end_time').value = endDate.toTimeString().slice(0, 5);

        document.getElementById('notes').value = notes || '';

        window.openScheduleModal();
    });
</script>
@endpush
