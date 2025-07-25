<x-app-layout>
    <x-global.header>
        {{ __('unit-service-types.title') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex gap-4 mb-4">
                        <x-global.create-button
                            :route="route('unitServiceTypes.create')"
                            :text="__('unit-service-types.create')"
                        />
                        <x-buttons.deativated-button
                            :route="route('unitServiceTypes.deactivated')"
                            :text="__('unit-service-types.deactivated')"
                        />
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <x-global.table-header :columns="[
                                    __('unit-service-types.name'),
                                    __('units.name'),
                                    __('fields.price'),
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

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                R$ {{ number_format($type->price, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <x-actions.view :route="route('unitServiceTypes.show', $type)" />
                                                <x-actions.edit :route="route('unitServiceTypes.edit', $type)" />
                                                <x-actions.deactivate :route="route('unitServiceTypes.deactivate', $type)" :confirmMessage="__('unit-service-types.confirm_deactivate')" />
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
