@php
    use App\Enum\WeekDaysEnum;
    $weekDays = WeekDaysEnum::getAll();
@endphp

<x-app-layout>
    <x-global.header>
        {{ __('unit-service-types.title') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex gap-4 mb-4">
                        <x-global.create-button
                            :route="route('unitServiceTypes.create')"
                            :text="__('unit-service-types.create')"
                        />
                        <x-buttons.deativated-button
                            :route="route('unitServiceTypes.deactivated')"
                            :text="__('unit-service-types.deactivated')"
                        />
                    </div>

                    <!-- Tabela para desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('unit-service-types.name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('units.name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('fields.price') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('unit-service-types.week_days.title') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('unit-service-types.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($unitServiceTypes as $type)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @php $imgUrl = $type->image_path ? Storage::disk('s3')->url($type->image_path) : null; @endphp
                                            <div class="flex items-center">
                                                <div class="mr-3 h-10 w-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                    @if($imgUrl)
                                                        <img src="{{ $imgUrl }}" alt="{{ $type->name }}" class="h-full w-full object-cover" />
                                                    @else
                                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ substr($type->name, 0, 1) }}</span>
                                                    @endif
                                                </div>
                                                <span>{{ $type->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $type->unit?->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            R$ {{ number_format($type->price, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($weekDays as $day)
                                                    @if($type->{$day->value})
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            {{ $day->getShortName() }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <x-actions.view :route="route('unitServiceTypes.show', $type)" />
                                            <x-actions.edit :route="route('unitServiceTypes.edit', $type)" />
                                            <x-actions.deactivate :route="route('unitServiceTypes.deactivate', $type)" :confirmMessage="__('unit-service-types.confirm_deactivate')" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards para mobile -->
                    <div class="md:hidden space-y-4">
                        @foreach ($unitServiceTypes as $type)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        @php $imgUrl = $type->image_path ? Storage::disk('s3')->url($type->image_path) : null; @endphp
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                @if($imgUrl)
                                                    <img src="{{ $imgUrl }}" alt="{{ $type->name }}" class="h-full w-full object-cover" />
                                                @else
                                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ substr($type->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $type->name }}</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $type->unit?->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        R$ {{ number_format($type->price, 2, ',', '.') }}
                                    </span>
                                </div>

                                <!-- Dias da semana ativos -->
                                <div class="mb-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('unit-service-types.week_days.title') }}:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($weekDays as $day)
                                            @if($type->{$day->value})
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ $day->getShortName() }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <x-actions.view-mobile :route="route('unitServiceTypes.show', $type)" />
                                    <x-actions.edit-mobile :route="route('unitServiceTypes.edit', $type)" />
                                    <x-actions.deactivate-mobile :route="route('unitServiceTypes.deactivate', $type)" :confirmMessage="__('unit-service-types.confirm_deactivate')" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Mensagem quando não há tipos de serviço -->
                    @if($unitServiceTypes->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p class="text-lg font-medium">{{ __('unit-service-types.no_types_found') }}</p>
                                <p class="mt-2">{{ __('unit-service-types.no_types_description') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
