@props(['type' => 'success'])

@php
$classes = [
    'success' => 'text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800',
    'error' => 'text-red-700 bg-red-100 dark:bg-red-200 dark:text-red-800'
][$type];
@endphp

<div class="mb-4 p-4 text-sm rounded-lg {{ $classes }}" role="alert">
    {{ $slot }}
</div>
