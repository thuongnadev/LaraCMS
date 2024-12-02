<div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-8">
    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="w-auto text-xs uppercase text-white" style="background-color: {{ $this->primaryColor }}">
                <tr>
                    @foreach ($columns as $column)
                        <th scope="col" class="px-2 md:px-6 py-3 text-left tracking-wider font-medium">
                            {{ $column }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($pricingData as $domain => $prices)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        @foreach ($columns as $column)
                            <td class="md:px-6 py-4 whitespace-nowrap text-sm @if($loop->first) font-medium text-gray-900 @else text-gray-500 @endif">
                                {{ $prices[$column] ?? 'N/A' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>