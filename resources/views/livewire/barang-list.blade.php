<div>
    <div class="space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Item List (CBS Database)
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Showing a list of item data from CBS.
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Search and Filter -->
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
                    
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Item Code
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Item Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Unit
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Price
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($barangs as $barang)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $barang->kode }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $barang->nama }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $barang->kategori }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $barang->satuan }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ number_format($barang->isi, 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        Rp {{ number_format($barang->harga_toko, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No item data found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($barangs->hasPages())
                    <div class="mt-4">
                        {{ $barangs->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
