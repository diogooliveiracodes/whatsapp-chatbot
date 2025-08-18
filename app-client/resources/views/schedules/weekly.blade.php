<x-app-layout>
    <div class="hidden lg:block">
        @include('schedules.weekly-desktop')
    </div>

    <div class="block lg:hidden">
        @include('schedules.weekly-mobile')
    </div>
</x-app-layout>
