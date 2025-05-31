<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('pages.schedules') }}
        </h2>
    </x-slot>

    <x-schedule-calendar :schedules="$schedules" :companySettings="$unit->company->companySettings" />
    <x-schedule-modal />
</x-app-layout>
