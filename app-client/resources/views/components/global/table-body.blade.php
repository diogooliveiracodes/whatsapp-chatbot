@props(['types'])

<tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
    @foreach ($types as $type)
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
