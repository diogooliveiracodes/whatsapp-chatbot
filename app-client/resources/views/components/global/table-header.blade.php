@props(['columns'])

<thead class="bg-gray-50 dark:bg-gray-700">
    <tr>
        @foreach ($columns as $column)
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                {{ $column }}
            </th>
        @endforeach
    </tr>
</thead>
