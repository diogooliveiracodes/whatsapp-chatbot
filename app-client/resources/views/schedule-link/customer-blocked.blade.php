<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $unit->company->name ?? __('schedule_link.page_title_default') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-gray-900 to-gray-800">
    <div class="min-h-screen flex flex-col sm:justify-center items-center mt-6">
        <div class="w-full max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="text-center mb-4">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ __('schedule_link.blocked_title') }}
                        </h1>
                        <p class="text-gray-300 mt-3">{{ __('schedule_link.blocked_message') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
