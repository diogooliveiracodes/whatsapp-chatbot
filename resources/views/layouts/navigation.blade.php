<div x-data="{ open: false }" class="flex h-screen w-64">
    <!-- Sidebar -->
    <div class="w-64 fixed flex-shrink-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 p-4 flex flex-col h-full">
        <!-- Logo -->
        <div class="shrink-0 flex items-center mb-4">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
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
        </nav>
    </div>
</div>
