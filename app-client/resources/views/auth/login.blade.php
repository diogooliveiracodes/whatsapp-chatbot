<x-guest-layout>
    <div class="pt-6 pb-2 text-center">
        <img src="{{ asset('images/logo/imperio-logo.png') }}" alt="Império Sistemas" class="mx-auto h-16 w-auto">
        <h1 class="mt-4 text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('auth.log_in') }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Acesse sua conta para continuar</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('auth.email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth.password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('auth.remember_me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4 pb-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('auth.forgot_password') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('auth.log_in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
