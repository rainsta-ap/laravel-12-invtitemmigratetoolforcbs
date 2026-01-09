<div>
    <div class="space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Item Migration Tool
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Migrate items from CBS database ({{ config('database.connections.mysql2.database') }}) to the new inventory system.
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Items</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalItems) }}</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <div class="text-sm font-medium text-green-600 dark:text-green-400">Migrated</div>
                            <div class="text-2xl font-bold text-green-700 dark:text-green-300">{{ number_format($migratedItems) }}</div>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Remaining</div>
                            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ number_format($totalItems - $migratedItems) }}</div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <flux:button
                            wire:click="startMigration"
                            :disabled="$isMigrating"
                            variant="primary"
                            class="flex items-center gap-2"
                        >
                            @if($isMigrating)
                                Migrating...
                            @else
                                Start Migration
                            @endif
                        </flux:button>

                        <flux:button
                            wire:click="countItems"
                            variant="outline"
                            class="flex items-center gap-2"
                        >
                            Refresh Count
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                        Important Notes
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>This tool will migrate items from the CBS database ({{ config('database.connections.mysql2.database') }}) to the new system.</li>
                            <li>Categories and units will be created automatically if they don't exist.</li>
                            <li>Existing items with the same code will not be updated - only new items are created.</li>
                            <li>Ensure a default warehouse with ID 1 exists in the system, or the migration will fail.</li>
                            <li>Make sure to backup your data before running the migration.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
