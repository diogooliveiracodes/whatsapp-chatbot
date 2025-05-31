@props(['schedule' => null])

<form id="scheduleForm" class="space-y-4" method="POST">
    @csrf
    <input type="hidden" id="schedule_id" name="schedule_id" value="{{ $schedule?->id }}">
    <input type="hidden" id="customer_id" name="customer_id" value="{{ $schedule?->customer_id }}">

    <div>
        <x-input-label for="service_type" value="Tipo de Serviço" class="text-gray-700 dark:text-gray-300" />
        <x-text-input id="service_type" name="service_type" type="text"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            value="{{ $schedule?->service_type }}" required />
    </div>

    <div>
        <x-input-label for="schedule_date" value="Data" class="text-gray-700 dark:text-gray-300" />
        <x-text-input id="schedule_date" name="schedule_date" type="date"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            value="{{ $schedule?->schedule_date }}" required />
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="start_time" value="Início" class="text-gray-700 dark:text-gray-300" />
            <x-text-input id="start_time" name="start_time" type="time"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                value="{{ $schedule?->start_time }}" required />
        </div>
        <div>
            <x-input-label for="end_time" value="Fim" class="text-gray-700 dark:text-gray-300" />
            <x-text-input id="end_time" name="end_time" type="time"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                value="{{ $schedule?->end_time }}" required />
        </div>
    </div>

    <div>
        <x-input-label for="notes" value="Observações" class="text-gray-700 dark:text-gray-300" />
        <textarea id="notes" name="notes"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ $schedule?->notes }}</textarea>
    </div>

    <div class="flex justify-end space-x-3 mt-6">
        <x-secondary-button type="button" onclick="closeModal()" class="dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            Cancelar
        </x-secondary-button>
        <x-primary-button type="submit" class="dark:bg-indigo-600 dark:hover:bg-indigo-700">
            Salvar
        </x-primary-button>
    </div>
</form>

@push('scripts')
<script>
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
            formattedData.append('_method', 'PUT');
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
            method: 'POST',
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
</script>
@endpush
