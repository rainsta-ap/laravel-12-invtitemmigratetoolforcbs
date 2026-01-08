<div>
    <div class="space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Selective Migration Tool
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Migrate items from CBS database by category or unit groups.
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Migration Type Selection -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Migration Type</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input
                                    wire:model.live="migrationType"
                                    type="radio"
                                    id="category"
                                    value="category"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                />
                                <label for="category" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Migrate by Category - Group items by their category (e.g., all "KECAP" items together)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input
                                    wire:model.live="migrationType"
                                    type="radio"
                                    id="unit"
                                    value="unit"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                />
                                <label for="unit" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Migrate by Unit - Group items by their unit (e.g., all "PCS" items together)
                                </label>
                            </div>
                        </div>

                        <!-- Selection Summary -->
                        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                        Current Selection: {{ ucfirst($migrationType) }} Migration
                                    </h4>
                                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                        @if($migrationType === 'category')
                                            {{ count($selectedCategories) }} of {{ count($categories) }} categories selected
                                        @else
                                            {{ count($selectedUnits) }} of {{ count($units) }} units selected
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection -->
                    @if($migrationType === 'category')
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Select Categories</h3>
                            <div class="space-x-2">
                                <flux:button wire:click="selectAllCategories" variant="outline" size="sm">
                                    Select All
                                </flux:button>
                                <flux:button wire:click="deselectAllCategories" variant="outline" size="sm">
                                    Deselect All
                                </flux:button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($categories as $index => $category)
                            <div class="flex items-center" wire:key="category-{{ $index }}-{{ count($selectedCategories) }}">
                                <input
                                    wire:click="toggleCategory('{{ $category }}')"
                                    type="checkbox"
                                    {{ in_array($category, $selectedCategories) ? 'checked' : '' }}
                                    id="category-{{ $index }}"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <label for="category-{{ $index }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    {{ $category }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Unit Selection -->
                    @if($migrationType === 'unit')
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Select Units</h3>
                            <div class="space-x-2">
                                <flux:button wire:click="selectAllUnits" variant="outline" size="sm">
                                    Select All
                                </flux:button>
                                <flux:button wire:click="deselectAllUnits" variant="outline" size="sm">
                                    Deselect All
                                </flux:button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($units as $index => $unit)
                            <div class="flex items-center" wire:key="unit-{{ $index }}-{{ count($selectedUnits) }}">
                                <input
                                    wire:click="toggleUnit('{{ $unit }}')"
                                    type="checkbox"
                                    {{ in_array($unit, $selectedUnits) ? 'checked' : '' }}
                                    id="unit-{{ $index }}"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <label for="unit-{{ $index }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    {{ $unit }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Migration Button -->
                    <div class="flex justify-center">
                        <flux:button
                            wire:click="startMigration"
                            :disabled="$isMigrating || ($migrationType === 'category' ? empty($selectedCategories) : empty($selectedUnits))"
                            variant="primary"
                            class="px-8 py-3"
                        >
                            @if($isMigrating)
                                Migrating...
                            @else
                                Start Selective Migration
                            @endif
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Migration Log -->
        @if(!empty($migrationLog))
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Migration Log</h3>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 max-h-96 overflow-y-auto">
                    <div class="space-y-1 font-mono text-sm">
                        @foreach($migrationLog as $log)
                        <div class="text-gray-700 dark:text-gray-300">{{ $log }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Selective Migration
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Choose to migrate by category or by unit</li>
                            <li>Select specific categories/units to migrate</li>
                            <li>Only selected groups will be migrated with all related data</li>
                            <li>Existing items will be skipped to avoid duplicates</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
