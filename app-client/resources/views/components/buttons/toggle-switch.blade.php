@props(['name', 'label', 'value' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1" class="sr-only peer" {{ $value ? 'checked' : '' }}>
        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $label }}</span>
    </label>
</div>
