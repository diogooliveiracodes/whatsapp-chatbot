<div x-data="{ open: false, settingsOpen: false }" class="flex h-screen w-64">
    <!-- Sidebar -->
    <div
        class="w-64 fixed flex-shrink-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 p-4 flex flex-col h-full">

        <!-- Logo/Brand Section -->
        <div class="mb-6">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800 dark:text-white">{{ __('pages.admin_panel') }}</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('pages.whatsapp_chatbot') }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.index') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                </svg>
                <span class="ml-2">{{ __('pages.dashboard') }}</span>
            </a>

            <!-- Management Section -->
            <div class="pt-4">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    {{ __('pages.management') }}
                </h3>
            </div>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <span class="ml-2">{{ __('pages.users') }}</span>
            </a>

            <a href="{{ route('admin.companies.index') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.companies.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="ml-2">{{ __('pages.companies') }}</span>
            </a>

            <!-- System Section -->
            <div class="pt-4">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    {{ __('pages.system') }}
                </h3>
            </div>

            <a href="{{ route('admin.logs') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.logs') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="ml-2">{{ __('pages.system_logs') }}</span>
            </a>

            <!-- Quick Actions -->
            <div class="pt-4">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    {{ __('pages.quick_actions') }}
                </h3>
            </div>

            <a href="{{ route('admin.users.create') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                <svg class="mr-4 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="ml-2">{{ __('pages.new_user') }}</span>
            </a>
        </nav>

        <!-- User info with dropdown -->
        <div x-data="{ userDropdownOpen: false }" class="relative">
            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <button @click="userDropdownOpen = !userDropdownOpen"
                    class="flex items-center w-full text-left hover:bg-gray-100 dark:hover:bg-gray-600 rounded-md transition-colors">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('pages.administrator') }}</p>
                    </div>
                    <svg class="h-5 w-5 text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': userDropdownOpen }" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <!-- Dropdown menu -->
            <div x-show="userDropdownOpen" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95" @click.away="userDropdownOpen = false"
                class="absolute bottom-full left-0 right-0 mb-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50">

                <a href="{{ route('admin.profile.edit') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors first:rounded-t-lg last:rounded-b-lg">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ __('auth.profile') }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors first:rounded-t-lg last:rounded-b-lg">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>{{ __('auth.log_out') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

