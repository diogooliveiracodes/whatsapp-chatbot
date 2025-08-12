<div x-data="{
    open: false,
    settingsOpen: false,
    closeMenu() {
        this.open = false;
        this.settingsOpen = false;
    }
}" class="block lg:hidden">
    <!-- Mobile menu button -->
    <div class="lg:hidden fixed top-2 left-2 z-50">
        <button @click="open = !open"
                class="bg-white dark:bg-gray-800 p-2 rounded-md shadow-lg border border-gray-200 dark:border-gray-700">
            <svg class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile menu overlay -->
    <div x-show="open"
         @click="closeMenu()"
         class="fixed inset-0 bg-black bg-opacity-50 z-40"
         style="display: none;">
    </div>

    <!-- Mobile menu sidebar -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed left-0 top-0 h-full w-64 bg-white dark:bg-gray-800 shadow-xl z-50 overflow-y-auto"
         style="display: none;">

        <div class="p-4">
            <!-- Logo area -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    {{ config('app.name', 'Laravel') }}
                </h1>
                <button @click="closeMenu()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
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
                <div class="mb-4">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        {{ __('pages.schedules') }}
                    </h3>
                </div>

                                <a href="{{ route('chatSessions.index') }}"
                    @click="closeMenu()"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('chatSessions.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="ml-2">{{ __('pages.chatSession') }}</span>
                </a>

                <a href="{{ route('schedules.index') }}"
                    @click="closeMenu()"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('schedules.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="ml-2">{{ __('pages.semanal_schedules') }}</span>
                </a>

                <a href="{{ route('schedules.daily') }}"
                    @click="closeMenu()"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('schedules.daily') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="ml-2">{{ __('pages.daily_schedules') }}</span>
                </a>

                <a href="{{ route('schedule-blocks.index') }}"
                    @click="closeMenu()"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('schedule-blocks.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="ml-2">{{ __('schedule-blocks.schedule_blocks') }}</span>
                </a>

                @if (auth()->user()->isOwner() && auth()->user()->company)
                    <!-- Settings Section -->
                    <div class="mt-6 mb-4">
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            {{ __('pages.settings') }}
                        </h3>
                    </div>

                                        <a href="{{ route('units.index') }}"
                        @click="closeMenu()"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('units.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="ml-2">{{ __('pages.units') }}</span>
                    </a>

                    <a href="{{ route('unitServiceTypes.index') }}"
                        @click="closeMenu()"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('unitServiceTypes.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span class="ml-2">{{ __('pages.unitServiceTypes') }}</span>
                    </a>

                    <a href="{{ route('customers.index') }}"
                        @click="closeMenu()"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('customers.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <span class="ml-2">{{ __('pages.customers') }}</span>
                    </a>

                    <a href="{{ route('signature.index') }}"
                        @click="closeMenu()"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('signature.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        <span class="ml-2">{{ __('signature.title') }}</span>
                    </a>
                @endif
            </nav>

                        <!-- Bottom actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('profile.edit') }}"
                    @click="closeMenu()"
                    class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-md transition-colors">
                    <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="ml-2">Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit"
                            @click="closeMenu()"
                            class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded-md transition-colors">
                        <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="ml-2">Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
