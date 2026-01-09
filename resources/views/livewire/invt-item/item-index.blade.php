<div>
    <div class="space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Item List (Inventory Database)
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Showing a list of item data from Inventory.
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="space-y-4">

                    <!-- Search & Filter -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <flux:label for="search">Search Item</flux:label>
                            <flux:input
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                id="search"
                                placeholder="Search by code, name, or category..."
                                class="mt-1"
                            />
                        </div>

                        <div class="sm:w-32">
                            <flux:label for="perPage">Show</flux:label>
                            <flux:select wire:model.live="perPage" id="perPage" class="mt-1">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </flux:select>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="text-green-600 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Item Code
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Item Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Category
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Unit
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Quantity
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Price
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($items as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->item_code }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $item->item_name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $item->category?->item_category_name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $item->unit?->item_unit_name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ number_format($item->item_default_quantity ?? 0, 0) }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            Rp {{ number_format($item->item_unit_price ?? 0, 0, ',', '.') }}
                                        </td>

                                        <td class="px-6 py-4 text-right text-sm">
                                            <div class="flex justify-end gap-2">
                                                <a
                                                    href="{{ route('items.edit', $item->item_id) }}"
                                                    class="px-3 py-1 bg-blue-600 text-white rounded text-xs"
                                                >
                                                    Edit
                                                </a>

                                                <button
                                                    wire:click="deleteItem({{ $item->item_id }})"
                                                    wire:confirm="Delete this item and all related data?"
                                                    class="px-3 py-1 bg-red-600 text-white rounded text-xs"
                                                >
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No item data found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($items->hasPages())
                        <div class="mt-4">
                            {{ $items->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
