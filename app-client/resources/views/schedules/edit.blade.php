<x-app-layout>
    <x-global.header>
        {{ __('schedules.edit_schedule') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <x-global.session-alerts />

                    <form method="POST" action="{{ route('schedules.update', $schedule->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="customer_id" class="block font-medium text-sm text-gray-300">
                                {{ __('customers.client') }}
                            </label>
                            <input type="hidden" name="customer_id" value="{{ old('customer_id', $schedule->customer_id) }}" />
                            <select id="customer_id" name="customer_id_display"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed @error('customer_id') border-red-500 @enderror"
                                disabled>
                                <option value="">{{ __('customers.select') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id', $schedule->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                        </div>

                        <div>
                            <label for="schedule_date" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.date') }}
                            </label>
                            <input id="schedule_date" type="date" name="schedule_date"
                                value="{{ old('schedule_date', \Carbon\Carbon::parse($schedule->schedule_date)->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 [color-scheme:light] dark:[color-scheme:dark] @error('schedule_date') border-red-500 @enderror"
                                required />
                            <x-input-error :messages="$errors->get('schedule_date')" class="mt-2" />
                        </div>

                        <div>
                            <label for="start_time" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.start_time') }}
                            </label>
                            <input id="start_time" type="time" name="start_time"
                                value="{{ old('start_time', \Carbon\Carbon::parse($schedule->schedule_date)->setTimeFromTimeString($schedule->start_time)->setTimezone(auth()->user()->unit->unitSettings->timezone ?? 'UTC')->format('H:i')) }}"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 [color-scheme:light] dark:[color-scheme:dark] @error('start_time') border-red-500 @enderror"
                                required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div>
                            <label for="unit_service_type_id" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.service_type') }}
                            </label>
                            <select id="unit_service_type_id" name="unit_service_type_id"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('unit_service_type_id') border-red-500 @enderror"
                                required>
                                <option value=""></option>
                                @foreach ($unitServiceTypes as $serviceType)
                                    <option value="{{ $serviceType->id }}"
                                        {{ old('unit_service_type_id', $schedule->unit_service_type_id) == $serviceType->id ? 'selected' : '' }}>
                                        {{ $serviceType->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('unit_service_type_id')" class="mt-2" />
                        </div>

                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.status') }}
                            </label>
                            <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('status') border-red-500 @enderror"
                                required>
                                <option value="pending"
                                    {{ old('status', $schedule->status) === App\Enum\ScheduleStatusEnum::PENDING->value ? 'selected' : '' }}>
                                    {{ __('schedules.statuses.pending') }}</option>
                                <option value="confirmed"
                                    {{ old('status', $schedule->status) === App\Enum\ScheduleStatusEnum::CONFIRMED->value ? 'selected' : '' }}>
                                    {{ __('schedules.statuses.confirmed') }}</option>
                                <option value="cancelled"
                                    {{ old('status', $schedule->status) === App\Enum\ScheduleStatusEnum::CANCELLED->value ? 'selected' : '' }}>
                                    {{ __('schedules.statuses.cancelled') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div>
                            <label for="notes" class="block font-medium text-sm text-gray-300">
                                {{ __('schedules.notes') }}
                            </label>
                            <textarea id="notes" name="notes"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('notes') border-red-500 @enderror"
                                rows="3">{{ old('notes', $schedule->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('schedules.index') }}">
                                {{ __('schedules.back') }}
                            </x-cancel-link>

                            <!-- Update Button -->
                            <x-primary-button type="submit">
                                {{ __('actions.save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
