<x-app-layout>
    <x-global.header>
        {{ __('unit-service-types.deactivated_title') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex gap-4 mb-4">
                        <x-buttons.back-button
                            :route="route('unitServiceTypes.index')"
                            :text="__('unit-service-types.active')"
                        />
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <x-global.table-header :columns="[
                                    __('unit-service-types.name'),
                                    __('unit-service-types.unit'),
                                    __('unit-service-types.actions')
                                ]" />
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($unitServiceTypes as $type)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $type->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $type->unit?->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <x-actions.activate :route="route('unitServiceTypes.activate', $type)" :confirmMessage="__('unit-service-types.confirm_activate')" />
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
