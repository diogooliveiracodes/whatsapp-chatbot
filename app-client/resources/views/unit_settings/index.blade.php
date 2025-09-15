<x-app-layout>
    <x-global.header>
        {{ __('pages.unitSettings') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    @if ($units->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-3 text-gray-600 dark:text-gray-300">{{ __('pages.no_units') }}</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($units as $unit)
                                @php
                                    $unitSettings = $unit->unitSettings ?? ($unit->UnitSettingsId ?? null);
                                    $unitSettingsId = $unitSettings->id ?? null;
                                    $appointmentDuration = $unitSettings->appointment_duration_minutes ?? null;
                                    $timezone = $unitSettings->timezone ?? null;
                                    $days = [
                                        'sunday',
                                        'monday',
                                        'tuesday',
                                        'wednesday',
                                        'thursday',
                                        'friday',
                                        'saturday',
                                    ];
                                    $activeDaysCount = 0;
                                    foreach ($days as $d) {
                                        if ($unitSettings && isset($unitSettings->$d) && $unitSettings->$d) {
                                            $activeDaysCount++;
                                        }
                                    }
                                    $imgUrl = $unit->image_path ? Storage::disk('s3')->url($unit->image_path) : null;
                                @endphp

                                <a href="{{ $unitSettingsId ? route('unitSettings.show', $unitSettingsId) : route('units.show', $unit->id) }}"
                                    class="group block bg-gray-50 dark:bg-gray-800 rounded-xl p-4 sm:p-5 shadow-sm border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-md transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                @if($imgUrl)
                                                    <img src="{{ $imgUrl }}" alt="{{ $unit->name }}" class="h-full w-full object-cover" />
                                                @else
                                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ substr($unit->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $unit->name }}</h3>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ __('pages.unitSettings') }}</p>
                                            </div>
                                        </div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $unit->active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $unit->active ? __('units.active') : __('units.inactive') }}
                                        </span>
                                    </div>

                                    @if (!empty($unit->description))
                                        <div class="mt-4 text-sm text-gray-700 dark:text-gray-300 truncate">
                                            {{ $unit->description }}
                                        </div>
                                    @endif

                                    <div
                                        class="mt-4 flex items-center justify-end text-indigo-600 dark:text-indigo-400">
                                        <span
                                            class="text-sm font-medium">{{ $unitSettingsId ? __('actions.view') : __('actions.settings') }}</span>
                                        <svg class="ml-2 h-4 w-4 transform transition group-hover:translate-x-0.5"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
