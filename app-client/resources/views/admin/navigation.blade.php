<div x-data="{
    open: false,
    settingsOpen: false,
    sidebarCollapsed: false,
    activeSection: 'dashboard'
}" class="flex h-screen">
    <!-- Sidebar -->
    <div :class="sidebarCollapsed ? 'w-16' : 'w-64'"
        class="fixed flex-shrink-0 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 border-r border-gray-700 transition-all duration-300 ease-in-out flex flex-col h-full shadow-2xl">

        <!-- Logo/Brand Section -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <div x-show="!sidebarCollapsed" class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white">{{ __('pages.admin_panel') }}</h1>
                    <p class="text-xs text-gray-400">{{ __('pages.whatsapp_chatbot') }}</p>
                </div>
            </div>
            <div x-show="sidebarCollapsed"
                class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mx-auto">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <button @click="sidebarCollapsed = !sidebarCollapsed"
                class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('admin.index') }}"
                :class="request()->routeIs('admin.index') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-300 hover:text-white">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                </svg>
                <span x-show="!sidebarCollapsed">{{ __('pages.dashboard') }}</span>
                <div x-show="sidebarCollapsed"
                    class="absolute left-16 ml-2 px-2 py-1 bg-gray-800 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                    {{ __('pages.dashboard') }}
                </div>
            </a>

            <!-- Users Section -->
            <div class="pt-4">
                <div x-show="!sidebarCollapsed" class="px-3 mb-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('pages.management') }}</h3>
                </div>

                <a href="{{ route('admin.users.index') }}"
                    :class="request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-300 hover:text-white">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed">{{ __('pages.users') }}</span>
                    <div x-show="sidebarCollapsed"
                        class="absolute left-16 ml-2 px-2 py-1 bg-gray-800 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                        {{ __('pages.users') }}
                    </div>
                </a>

                <a href="{{ route('admin.companies.index') }}"
                    :class="request()->routeIs('admin.companies.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-300 hover:text-white">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-show="!sidebarCollapsed">{{ __('pages.companies') }}</span>
                    <div x-show="sidebarCollapsed"
                        class="absolute left-16 ml-2 px-2 py-1 bg-gray-800 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                        {{ __('pages.companies') }}
                    </div>
                </a>
            </div>

            <!-- System Section -->
            <div class="pt-4">
                <div x-show="!sidebarCollapsed" class="px-3 mb-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('pages.system') }}</h3>
                </div>

                <a href="{{ route('admin.logs') }}"
                    :class="request()->routeIs('admin.logs') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-300 hover:text-white">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="!sidebarCollapsed">{{ __('pages.system_logs') }}</span>
                    <div x-show="sidebarCollapsed"
                        class="absolute left-16 ml-2 px-2 py-1 bg-gray-800 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                        {{ __('pages.system_logs') }}
                    </div>
                </a>
            </div>

            <!-- Quick Actions -->
            <div class="pt-4">
                <div x-show="!sidebarCollapsed" class="px-3 mb-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('pages.quick_actions') }}</h3>
                </div>

                <a href="{{ route('admin.users.create') }}"
                   class="group flex items-center w-full px-3 py-2.5 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span x-show="!sidebarCollapsed">{{ __('pages.new_user') }}</span>
                    <div x-show="sidebarCollapsed"
                        class="absolute left-16 ml-2 px-2 py-1 bg-gray-800 text-white text-sm rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                        {{ __('pages.new_user') }}
                    </div>
                </a>

            </div>
        </nav>

        <!-- User Profile Section -->
        <div class="p-4 border-t border-gray-700">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center w-full px-3 py-2.5 text-left text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-all duration-200 focus:outline-none">
                    <div
                        class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div x-show="!sidebarCollapsed" class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ __('pages.administrator') }}</p>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="ml-2 h-4 w-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute left-0 bottom-full mb-2 w-56 bg-gray-800 border border-gray-700 rounded-lg shadow-xl z-50"
                    style="display: none;">
                    <div class="p-3 border-b border-gray-700">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('auth.profile') }}
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('pages.settings') }}
                        </a>
                        <div class="border-t border-gray-700 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('auth.log_out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Spacer -->
    <div :class="sidebarCollapsed ? 'ml-16' : 'ml-64'" class="flex-1 transition-all duration-300 ease-in-out">
        <!-- This div provides spacing for the fixed sidebar -->
    </div>
</div>
