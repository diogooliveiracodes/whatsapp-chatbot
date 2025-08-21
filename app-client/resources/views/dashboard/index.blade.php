<x-app-layout>
    <x-global.header>
        {{ __('dashboard.title') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Componente do Link de Agendamento -->
            <x-schedule-link-display :company="auth()->user()->company" />
        </div>
    </div>
</x-app-layout>
