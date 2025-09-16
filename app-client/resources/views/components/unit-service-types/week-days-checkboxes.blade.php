@props(['weekDays', 'model' => null])

<div class="space-y-4">
    <div>
        <x-input-label :value="__('unit-service-types.week_days.title')" class="text-base font-medium" />
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ __('unit-service-types.week_days.description') }}
        </p>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-7">
        @foreach($weekDays as $day)
            <div class="flex items-center">
                <input
                    id="{{ $day->value }}"
                    name="{{ $day->value }}"
                    type="checkbox"
                    value="1"
                    {{ $model ? ($model->{$day->value} ? 'checked' : '') : 'checked' }}
                    {{ $attributes->merge(['class' => 'h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded']) }}
                >
                <label for="{{ $day->value }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                    {{ $day->getName() }}
                </label>
            </div>
        @endforeach
    </div>
</div>
