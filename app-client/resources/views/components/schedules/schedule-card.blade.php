@props(['schedule'])

<div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
    <div class="p-2">
        <div class="mb-1 flex justify-between space-x-1">
            <span
                class="px-1.5 py-0.5 text-xs font-semibold rounded-full
                @if ($schedule['status'] === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                @elseif($schedule['status'] === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                @elseif($schedule['status'] === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                {{ ucfirst(__('schedules.statuses.' . $schedule['status'])) }}
            </span>
            <div class="flex space-x-2">
                <a href="{{ route('schedules.edit', $schedule['id']) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form action="{{ route('schedules.destroy', $schedule['id']) }}" method="POST" class="inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                {{ $schedule['customer']['name'] }}
            </h3>
        </div>
        <p class="text-xs text-gray-600 dark:text-gray-300 truncate">
            {{ $schedule['unit_service_type']['name'] }}
        </p>
        @if($schedule['notes'])
            <p class="text-xs text-gray-700 dark:text-gray-400 truncate">
                {{ $schedule['notes'] }}
            </p>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm('{{ __('schedules.messages.confirm_delete') }}')) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endpush
