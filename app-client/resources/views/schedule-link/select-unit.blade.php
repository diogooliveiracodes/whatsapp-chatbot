<x-guest-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-semibold text-white mb-6">{{ __('schedule_link.choose_unit') }}</h1>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($units as $unit)
                            <a href="{{ route('schedule-link.personal-info', ['company' => $company, 'unit' => $unit->id]) }}" class="block p-4 rounded-lg border border-gray-700 hover:border-indigo-500 hover:bg-gray-700 transition">
                                <div class="text-white font-medium">{{ $unit->name }}</div>
                                <div class="text-gray-400 text-sm">{{ $unit->city }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>


