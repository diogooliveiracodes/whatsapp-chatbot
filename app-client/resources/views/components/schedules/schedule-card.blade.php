@props(['schedule'])

<div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
    <div class="p-2">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                {{ $schedule['customer']['name'] }}
            </h3>
            <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full
                @if($schedule['status'] === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                @elseif($schedule['status'] === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                @elseif($schedule['status'] === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                @endif">
                {{ ucfirst($schedule['status']) }}
            </span>
        </div>
        <p class="text-xs text-gray-600 dark:text-gray-300 truncate">
            {{ $schedule['service_type'] }}
        </p>
        <div class="mt-1 flex justify-end space-x-1">
            <form action="{{ route('schedules.destroy', $schedule['id']) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
            @if($schedule['status'] === 'pending')
                <form action="{{ route('schedules.cancel', $schedule['id']) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
