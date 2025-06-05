<div x-data="{ open: false, settingsOpen: false }" class="flex h-screen w-64">
    <!-- Sidebar -->
    <div
        class="w-64 fixed flex-shrink-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 p-4 flex flex-col h-full">
        <!-- Logo -->
        <div class="shrink-0 flex items-center mb-4">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex flex-col space-y-2">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('pages.dashboard') }}
            </x-nav-link>
            <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                {{ __('pages.customers') }}
            </x-nav-link>
            <x-nav-link :href="route('chatSessions.index')" :active="request()->routeIs('chatSessions.*')">
                {{ __('pages.chatSession') }}
            </x-nav-link>
            <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                {{ __('pages.schedules') }}
            </x-nav-link>
            @if (auth()->user()->isOwner() && auth()->user()->company)
                <div class="mt-4 mb-2">
                    <h3 class="px-0 text-s font-semibold text-gray-500 uppercase tracking-wider">
                        {{ __('pages.settings') }}
                    </h3>
                </div>
                <hr class="border-gray-500">
                <x-nav-link :href="route('units.index')" :active="request()->routeIs('units.*')">
                    {{ __('pages.units') }}
                </x-nav-link>
                <x-nav-link :href="route('unitServiceTypes.index')" :active="request()->routeIs('unitServiceTypes.*')">
                    {{ __('pages.unitServiceTypes') }}
                </x-nav-link>
            @endif
        </nav>

        <!-- User Profile Dropdown -->
        <div class="mt-auto">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center w-full px-4 py-2 text-left text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                    <div>{{ Auth::user()->name }}</div>
                    <svg class="ml-auto h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute left-0 bottom-full mb-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg"
                    style="display: none;">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Log
                            Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
