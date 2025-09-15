<x-guest-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-semibold text-white">{{ __('schedule_link.choose_professional') }}</h1>
                    </div>

                    @if($units->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-3 text-gray-400">{{ __('schedule_link.no_units_available') ?? 'Nenhuma unidade disponível' }}</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($units as $unit)
                                @php $imgUrl = $unit->image_path ? Storage::disk('s3')->url($unit->image_path) : null; @endphp
                                <a href="{{ route('schedule-link.personal-info', ['company' => $company, 'unit' => $unit->id]) }}"
                                   aria-label="{{ $unit->name }}"
                                   class="group block rounded-xl border border-gray-700/70 bg-gray-700/30 p-4 ring-1 ring-transparent transition hover:border-indigo-500 hover:bg-gray-700/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="h-12 w-12 rounded-full overflow-hidden bg-gray-600 flex items-center justify-center shrink-0">
                                                @if($imgUrl)
                                                    <img src="{{ $imgUrl }}" alt="{{ $unit->name }}" class="h-full w-full object-cover" />
                                                @else
                                                    <span class="text-sm font-medium text-gray-300">{{ mb_substr($unit->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-white font-medium truncate">{{ $unit->name }}</div>
                                                <div class="text-gray-400 text-sm truncate">{{ $unit->city }}</div>
                                            </div>
                                        </div>
                                        <svg class="h-5 w-5 text-gray-400 transition group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    @php
                                        $settings = $unit->unitSettings ?? ($unit->UnitSettingsId ?? null);
                                        $days = [
                                            ['key' => 'sunday', 'label' => 'DOM'],
                                            ['key' => 'monday', 'label' => 'SEG'],
                                            ['key' => 'tuesday', 'label' => 'TER'],
                                            ['key' => 'wednesday', 'label' => 'QUA'],
                                            ['key' => 'thursday', 'label' => 'QUI'],
                                            ['key' => 'friday', 'label' => 'SEX'],
                                            ['key' => 'saturday', 'label' => 'SAB'],
                                        ];
                                    @endphp
                                    <div class="mt-3">
                                        <p class="text-xs text-gray-400 mb-1">{{ __('schedule_link.service_days') }}</p>
                                        <div class="flex flex-wrap gap-1">
                                            @if($settings)
                                                @foreach($days as $d)
                                                    @if(data_get($settings, $d['key']))
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $d['label'] }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-xs text-gray-500">—</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
