<x-app-layout>
    <x-global.header>
        {{ __('units.deactivated_title') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex gap-4 mb-4">
                        <x-buttons.back-button
                            :route="route('units.index')"
                            :text="__('units.active_units')"
                        />
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <x-global.table-header :columns="[
                                    __('units.name'),
                                    __('units.actions')
                                ]" />
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($units as $unit)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $unit->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <x-actions.activate :route="route('units.activate', $unit)" :confirmMessage="__('units.confirm_activate')">
                                                    {{ __('buttons.activate') }}
                                                </x-actions.activate>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
