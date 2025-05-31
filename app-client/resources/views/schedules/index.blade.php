<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('pages.schedules') }}
            </h2>
            <x-confirm-link :href="route('schedules.create')">
                {{ __('schedules.new_schedule') }}
            </x-confirm-link>
        </div>
    </x-slot>

    <x-schedule-calendar :schedules="$schedules" :companySettings="$unit->company->companySettings" />
    <x-schedule-modal />
</x-app-layout>
