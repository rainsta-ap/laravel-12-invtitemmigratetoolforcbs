<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Edit Item (Inventory Database)
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Update item information in the inventory database.
        </p>
    </div>

    <!-- Card Container -->
    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
        <div class="p-6 space-y-4">

            <!-- Success Message -->
            @if (session('success'))
                <div class="text-green-600 dark:text-green-400 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tabs -->
            <div class="flex border-b dark:border-gray-700">
                <button wire:click="$set('tab','main')"
                        class="px-4 py-2 -mb-px font-semibold border-b-2
                        {{ $tab=='main' ? 'border-blue-600 text-blue-600' : 'border-transparent dark:text-gray-400' }}">
                    Main
                </button>
                <button wire:click="$set('tab','unit')"
                        class="px-4 py-2 -mb-px font-semibold border-b-2
                        {{ $tab=='unit' ? 'border-blue-600 text-blue-600' : 'border-transparent dark:text-gray-400' }}">
                    Unit / Price / Quantity
                </button>
            </div>

            <!-- Main Tab -->
            @if ($tab == 'main')
                <div class="space-y-4 mt-4">
                    <!-- Category -->
                    <div>
                        <flux:label for="item_category_id">Category</flux:label>
                        <flux:select id="item_category_id" wire:model.defer="item_category_id" class="mt-1 w-full">
                            <option value="" disabled>-- Select Category --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->item_category_id }}">{{ $cat->item_category_name }}</option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Barcode & Item Name -->
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <flux:label for="item_barcode">Barcode</flux:label>
                            <flux:input id="item_barcode" type="text" wire:model.defer="item_barcode" class="mt-1 w-full"/>
                        </div>
                        <div class="flex-1">
                            <flux:label for="item_name">Item Name</flux:label>
                            <flux:input id="item_name" type="text" wire:model.defer="item_name" class="mt-1 w-full"/>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="pt-4">
                        <flux:button wire:click="saveMain" variant="primary">Next / Save Main Data</flux:button>
                    </div>
                </div>
            @endif

            <!-- Unit Tab -->
            @if ($tab == 'unit')
                <div class="space-y-4 mt-4">

                    <!-- Unit Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Unit
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Default Quantity
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Unit Price
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Unit Cost
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr>
                                    <!-- Unit -->
                                    <td class="px-4 py-2">
                                        <flux:select id="item_unit_id" wire:model.defer="item_unit_id" class="w-full">
                                            <option value="" disabled>-- Select Unit --</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->item_unit_id }}">{{ $unit->item_unit_name }}</option>
                                            @endforeach
                                        </flux:select>
                                    </td>

                                    <!-- Default Quantity -->
                                    <td class="px-4 py-2">
                                        <flux:input id="item_default_quantity" type="number" step="0.01" wire:model.defer="item_default_quantity" class="w-full"/>
                                    </td>

                                    <!-- Unit Price -->
                                    <td class="px-4 py-2">
                                        <flux:input id="item_unit_price" type="number" step="0.01" wire:model.defer="item_unit_price" class="w-full"/>
                                    </td>

                                    <!-- Unit Cost -->
                                    <td class="px-4 py-2">
                                        <flux:input id="item_unit_cost" type="number" step="0.01" wire:model.defer="item_unit_cost" class="w-full"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Save Button -->
                    <div class="pt-4">
                        <flux:button wire:click="saveUnit" variant="primary">Save Unit Data</flux:button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
