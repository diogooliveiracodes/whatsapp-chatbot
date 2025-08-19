<x-app-layout>
    <x-global.header>
        {{ __('user.details') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="mb-6">
                        <x-cancel-link :href="route('users.index')">
                            {{ __('user.back') }}
                        </x-cancel-link>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div>
                            <x-forms.section-title :title="__('user.name')" />
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                        </div>

                        <!-- E-mail -->
                        <div>
                            <x-forms.section-title :title="__('user.email')" />
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                        </div>

                        <!-- Unidade -->
                        <div>
                            <x-forms.section-title :title="__('user.unit')" />
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->unit?->name ?? '-' }}</p>
                        </div>

                        <!-- Perfil -->
                        <div>
                            <x-forms.section-title :title="__('user.role')" />
                            @if($user->user_role_id === 2)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Proprietário
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Funcionário
                                </span>
                            @endif
                        </div>

                        <!-- Status -->
                        <div>
                            <x-forms.section-title :title="__('user.active')" />
                            @if($user->active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ __('user.yes') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    {{ __('user.no') }}
                                </span>
                            @endif
                        </div>

                        <!-- Criado em -->
                        <div>
                            <x-forms.section-title :title="__('user.created_at')" />
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-4">
                        <x-actions.edit :route="route('users.edit', $user)" />
                        @if($user->active)
                            <x-actions.deactivate :route="route('users.deactivate', $user)" :confirmMessage="__('user.confirm_deactivate')" />
                        @else
                            <x-actions.activate :route="route('users.activate', $user)" :confirmMessage="__('user.confirm_activate')">
                                {{ __('user.activate') }}
                            </x-actions.activate>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
