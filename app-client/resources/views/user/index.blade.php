<x-app-layout>
    <x-global.header>
        {{ __('user.title') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex gap-4 mb-4">
                        <x-global.create-button :route="route('users.create')" :text="__('user.create')" />
                        <x-buttons.deativated-button :route="route('users.deactivated')" :text="__('user.deactivated')" />
                    </div>

                    <!-- Tabela para desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        {{ __('user.name') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        {{ __('user.email') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        {{ __('user.unit') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        {{ __('user.role') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                        {{ __('user.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->email }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $user->unit?->name ?? '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if ($user->user_role_id === 2)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ __('user.roles.owner') }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ __('user.roles.employee') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <x-actions.view :route="route('users.show', $user)" />
                                            <x-actions.edit :route="route('users.edit', $user)" />
                                            <x-actions.deactivate :route="route('users.deactivate', $user)" :confirmMessage="__('user.confirm_deactivate')" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards para mobile -->
                    <div class="md:hidden space-y-4">
                        @foreach ($users as $user)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $user->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $user->unit?->name ?? '-' }}</p>
                                    </div>
                                    <div class="ml-2">
                                        @if ($user->user_role_id === 2)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ __('user.roles.owner') }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ __('user.roles.employee') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <x-actions.view-mobile :route="route('users.show', $user)" />
                                    <x-actions.edit-mobile :route="route('users.edit', $user)" />
                                    <x-actions.deactivate-mobile :route="route('users.deactivate', $user)" :confirmMessage="__('user.confirm_deactivate')" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Mensagem quando não há usuários -->
                    @if ($users->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <p class="text-lg font-medium">{{ __('user.no_users_found') }}</p>
                                <p class="mt-2">{{ __('user.no_users_description') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
