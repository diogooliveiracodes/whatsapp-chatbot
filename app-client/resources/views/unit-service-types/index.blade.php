<x-app-layout>
    <x-header>
        {{ __('unit-service-types.title') }}
    </x-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('error'))
                        <x-global.alert type="error">
                            {{ session('error') }}
                        </x-global.alert>
                    @endif

                    @if (session('success'))
                        <x-global.alert type="success">
                            {{ session('success') }}
                        </x-global.alert>
                    @endif

                    <x-global.create-button
                        :route="route('unitServiceTypes.create')"
                        :text="__('unit-service-types.create')"
                    />

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <x-global.table-header :columns="[
                                    __('unit-service-types.name'),
                                    __('units.name'),
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
                                                <x-actions.view :route="route('unitServiceTypes.show', $type)" />
                                                <x-actions.edit :route="route('unitServiceTypes.edit', $type)" />
                                                <x-actions.delete :route="route('unitServiceTypes.destroy', $type)" :confirmMessage="__('unit-service-types.confirm_delete')" />
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
