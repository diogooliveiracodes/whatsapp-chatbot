<x-app-layout>
    <x-global.header>
        {{ __('units.title') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex gap-4 mb-4">
                        <x-global.create-button
                            :route="route('units.create')"
                            :text="__('units.create')"
                        />

                        <x-buttons.deativated-button
                            :route="route('units.deactivated')"
                            :text="__('units.deactivated')"
                        />
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <x-global.table-header :columns="[
                                    __('units.name'),
                                    __('units.active'),
                                    __('units.actions')
                                ]" />
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($units as $unit)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $unit->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $unit->active ? __('units.yes') : __('units.no') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                <div class="flex items-center space-x-2">
                                                    <x-actions.view :route="route('units.show', $unit->id)" />
                                                    <x-actions.edit :route="route('units.edit', $unit->id)" />
                                                    <x-actions.settings :route="route('unitSettings.show', $unit->id)" />
                                                    <x-actions.deactivate :route="route('units.deactivate', $unit->id)" :confirmMessage="__('units.confirm_deactivate')" />
                                                </div>
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
