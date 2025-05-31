<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('New Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <form method="POST" action="{{ route('schedules.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="customer_id" class="block font-medium text-sm text-gray-300">
                                {{ __('Customer') }}
                            </label>
                            <select id="customer_id" name="customer_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                                <option value="">{{ __('Select a customer') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="schedule_date" class="block font-medium text-sm text-gray-300">
                                {{ __('Date') }}
                            </label>
                            <input id="schedule_date" type="date" name="schedule_date" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required />
                        </div>

                        <div>
                            <label for="start_time" class="block font-medium text-sm text-gray-300">
                                {{ __('Start Time') }}
                            </label>
                            <input id="start_time" type="time" name="start_time" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required />
                        </div>

                        <div>
                            <label for="end_time" class="block font-medium text-sm text-gray-300">
                                {{ __('End Time') }}
                            </label>
                            <input id="end_time" type="time" name="end_time" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required />
                        </div>

                        <div>
                            <label for="service_type" class="block font-medium text-sm text-gray-300">
                                {{ __('Service Type') }}
                            </label>
                            <input id="service_type" type="text" name="service_type" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required />
                        </div>

                        <div>
                            <label for="notes" class="block font-medium text-sm text-gray-300">
                                {{ __('Notes') }}
                            </label>
                            <textarea id="notes" name="notes" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="3"></textarea>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                {{ __('Create Schedule') }}
                            </button>
                            <a href="{{ route('schedules.index') }}" class="text-gray-400 hover:text-gray-300">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
