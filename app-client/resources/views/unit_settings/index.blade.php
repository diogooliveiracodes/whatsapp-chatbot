<x-app-layout>
    <x-global.header>
        {{ __('pages.unitSettings') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    @if($units->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-600 dark:text-gray-300">{{ __('pages.no_units') }}</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($units as $unit)
                                @php($unitSettingsId = $unit->unitSettings->id ?? ($unit->UnitSettingsId->id ?? null))
                                <a href="{{ $unitSettingsId ? route('unitSettings.show', $unitSettingsId) : route('units.show', $unit->id) }}"
                                   class="block bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $unit->name }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('pages.unitSettings') }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $unit->active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $unit->active ? __('units.active') : __('units.inactive') }}
                                        </span>
                                    </div>
                                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $unit->description ?? '' }}
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


