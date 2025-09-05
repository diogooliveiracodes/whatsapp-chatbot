<x-admin-layout>
    <x-global.header>
        {{ __('profile.title') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">
                        @include('admin.profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">
                        @include('admin.profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">
                        @include('admin.profile.partials.delete-user-form')
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</x-admin-layout>
