@props(['title' => 'Novo Agendamento'])

<div id="scheduleModal" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 hidden" style="z-index: 9999;">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100" id="modalTitle">
                    {{ $title }}
                </h2>
                <button id="deleteScheduleBtn" class="hidden text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" onclick="confirmDeleteSchedule()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>

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
        document.getElementById('deleteScheduleBtn').classList.add('hidden');
    }

    window.confirmDeleteSchedule = function() {
        if (confirm('Tem certeza que deseja excluir este agendamento?')) {
            const scheduleId = document.getElementById('schedule_id').value;
            if (!scheduleId) return;

            fetch(`/schedules/${scheduleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Erro ao excluir agendamento');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao excluir agendamento');
            });
        }
    }

    // Listen for schedule edit events only
    window.addEventListener('schedule-edit', function(e) {
        const { id, customer, service_type, start, end, notes, status } = e.detail;

        document.getElementById('modalTitle').textContent = 'Editar Agendamento';
        document.getElementById('schedule_id').value = id;
        document.getElementById('customer_id').value = customer.id;
        document.getElementById('service_type').value = service_type;
        document.getElementById('status').value = status || 'pending';

        const startDate = new Date(start);
        document.getElementById('schedule_date').value = startDate.toISOString().split('T')[0];
        document.getElementById('start_time').value = startDate.toTimeString().slice(0, 5);

        const endDate = new Date(end);
        document.getElementById('end_time').value = endDate.toTimeString().slice(0, 5);

        document.getElementById('notes').value = notes || '';
        document.getElementById('deleteScheduleBtn').classList.remove('hidden');

        window.openScheduleModal();
    });
</script>
@endpush
