<x-app-layout>
    <x-global.header>
        {{ __('unit-service-types.details') }} - {{ $unitServiceType->name }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <div class="mt-2">
                                @if(!empty($unitServiceType->image_path))
                                    <img src="{{ Storage::disk('s3')->url($unitServiceType->image_path) }}" alt="{{ $unitServiceType->name }}" class="h-[120px] w-[120px] rounded-full object-cover ring-1 ring-gray-300 dark:ring-gray-700 mx-auto md:mx-0">
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">—</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.name') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.description') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->description ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('unit-service-types.unitName') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->unit?->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.price') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">R$ {{ number_format($unitServiceType->price, 2, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.created_at') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($unitServiceType->created_at)->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.updated_at') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($unitServiceType->updated_at)->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('unit-service-types.week_days.title') }}</label>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($weekDays as $day)
                                    @if($unitServiceType->{$day->value})
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            {{ $day->getName() }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $day->getName() }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <!-- Back Button -->
                        <x-cancel-link href="{{ route('unitServiceTypes.index') }}">
                            {{ __('actions.cancel') }}
                        </x-cancel-link>

                        <!-- Edit Button -->
                        <x-confirm-link href="{{ route('unitServiceTypes.edit', $unitServiceType->id) }}">
                            {{ __('actions.edit') }}
                        </x-confirm-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
