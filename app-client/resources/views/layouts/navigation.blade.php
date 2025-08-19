<div x-data="{ open: false, settingsOpen: false }" class="flex h-screen w-64">
    <!-- Sidebar -->
    <div
        class="w-64 fixed flex-shrink-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 p-4 flex flex-col h-full">

        <!-- Logo area -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                {{ config('app.name', 'Laravel') }}
            </h1>
        </div>

        <!-- User info -->
        <div class="mb-6 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-1">
            <!-- Schedules Section -->
            <div class="">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    {{ __('pages.schedules') }}
                </h3>
            </div>

            <a href="{{ route('chatSessions.index') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('chatSessions.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="ml-2">{{ __('pages.chatSession') }}</span>
            </a>

            <a href="{{ route('schedules.weekly') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('schedules.weekly') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="ml-2">{{ __('pages.semanal_schedules') }}</span>
            </a>

            <a href="{{ route('schedules.daily') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('schedules.daily') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="ml-2">{{ __('pages.daily_schedules') }}</span>
            </a>

                            <a href="{{ route('schedule-blocks.index') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('schedule-blocks.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 9l-6 6" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 9l6 6" />
                    </svg>
                    <span class="ml-2">{{ __('schedule-blocks.schedule_blocks') }}</span>
                </a>

            @if (auth()->user()->isOwner() && auth()->user()->company)
                <!-- Settings Section -->
                <div class=pt-4 mb-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        {{ __('pages.settings') }}
                    </h3>
                </div>

                <a href="{{ route('units.index') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('units.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="ml-2">{{ __('pages.units') }}</span>
                </a>

                <a href="{{ route('unitSettings.index') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('unitSettings.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3a7.963 7.963 0 00-1.257-3.019l1.518-1.518-1.414-1.414-1.518 1.518A7.963 7.963 0 0015 3.06V1h-2v2.06a7.963 7.963 0 00-3.019 1.257L8.463 2.799 7.049 4.213l1.518 1.518A7.963 7.963 0 005.06 9H3v2h2.06c.243 1.096.69 2.114 1.257 3.019l-1.518 1.518 1.414 1.414 1.518-1.518c.905.567 1.923 1.014 3.019 1.257V21h2v-2.06a7.963 7.963 0 003.019-1.257l1.518 1.518 1.414-1.414-1.518-1.518c.567-.905 1.014-1.923 1.257-3.019H21v-2h-2.06z" />
                    </svg>
                    <span class="ml-2">{{ __('pages.unitSettings') }}</span>
                </a>

                <a href="{{ route('unitServiceTypes.index') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('unitServiceTypes.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="ml-2">{{ __('pages.unitServiceTypes') }}</span>
                </a>

                <a href="{{ route('customers.index') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('customers.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <span class="ml-2">{{ __('pages.customers') }}</span>
                </a>

                <a href="{{ route('users.index') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <span class="ml-2">{{ __('user.title') }}</span>
                </a>

                <a href="{{ route('signature.index') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors
                        {{ auth()->user()->company->signature && auth()->user()->company->signature->status === \App\Enum\SignatureStatusEnum::EXPIRING_SOON->value ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' : '' }}
                        {{ request()->routeIs('signature.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span class="ml-2">{{ __('signature.title') }}</span>
                    @if (\App\Helpers\SignatureHelper::isExpiringSoon())
                        <svg class="ml-auto h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    @endif
                </a>
            @endif
        </nav>

        <!-- Bottom actions -->
        <div class="mt-auto pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-md transition-colors">
                <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="ml-2">{{ __('auth.profile') }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-md transition-colors">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="ml-2">{{ __('auth.log_out') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
