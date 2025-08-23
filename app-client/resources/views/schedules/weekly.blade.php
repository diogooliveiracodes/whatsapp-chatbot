<x-app-layout>
    <div class="hidden lg:block">
        @include('schedules.weekly-desktop')
    </div>

    <div class="block lg:hidden">
        @include('schedules.weekly-mobile')
    </div>

    <!-- Include message modal -->
    <x-automated_messages.message-modal />
</x-app-layout>
