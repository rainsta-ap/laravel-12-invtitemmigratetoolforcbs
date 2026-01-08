<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\InvtItem;
use App\Models\InvtItemBarcode;
use App\Models\InvtItemCategory;
use App\Models\InvtItemPackge;
use App\Models\InvtItemStock;
use App\Models\InvtItemUnit;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SelectiveMigrate extends Component
{
    public $categories = [];
    public $units = [];
    public $selectedCategories = [];
    public $selectedUnits = [];
    public $isMigrating = false;
    public $migrationLog = [];
    public $migrationType = 'category'; // 'category' or 'unit'

    public function mount()
    {
        $this->loadCategories();
        $this->loadUnits();
    }

    public function loadCategories()
    {
        $this->categories = Barang::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->orderBy('kategori')
            ->pluck('kategori')
            ->toArray();
    }

    public function loadUnits()
    {
        $this->units = Barang::select('satuan')
            ->distinct()
            ->whereNotNull('satuan')
            ->where('satuan', '!=', '')
            ->orderBy('satuan')
            ->pluck('satuan')
            ->toArray();
    }

    public function selectAllCategories()
    {
        $this->selectedCategories = $this->categories;
    }

    public function deselectAllCategories()
    {
        $this->selectedCategories = [];
    }

    public function selectAllUnits()
    {
        $this->selectedUnits = $this->units;
    }

    public function deselectAllUnits()
    {
        $this->selectedUnits = [];
    }

    public function toggleCategory($category)
    {
        if (in_array($category, $this->selectedCategories)) {
            $this->selectedCategories = array_diff($this->selectedCategories, [$category]);
        } else {
            $this->selectedCategories[] = $category;
        }
    }

    public function toggleUnit($unit)
    {
        if (in_array($unit, $this->selectedUnits)) {
            $this->selectedUnits = array_diff($this->selectedUnits, [$unit]);
        } else {
            $this->selectedUnits[] = $unit;
        }
    }

    public function updatedMigrationType()
    {
        // Clear selections when switching between category and unit migration types
        $this->selectedCategories = [];
        $this->selectedUnits = [];
        $this->migrationLog = []; // Also clear any previous migration logs
    }

    public function startMigration()
    {
        if ($this->migrationType === 'category' && empty($this->selectedCategories)) {
            $this->addLog('Error: Please select at least one category to migrate.');
            return;
        }

        if ($this->migrationType === 'unit' && empty($this->selectedUnits)) {
            $this->addLog('Error: Please select at least one unit to migrate.');
            return;
        }

        $this->isMigrating = true;
        $this->migrationLog = [];

        try {
            DB::beginTransaction();

            if ($this->migrationType === 'category') {
                $this->migrateByCategories();
            } else {
                $this->migrateByUnits();
            }

            DB::commit();
            $this->addLog('Migration completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addLog('Migration failed: ' . $e->getMessage());
        }

        $this->isMigrating = false;
    }

    private function migrateByCategories()
    {
        foreach ($this->selectedCategories as $categoryName) {
            $this->addLog("Migrating category: {$categoryName}");

            $barangs = Barang::where('kategori', $categoryName)->get();

            foreach ($barangs as $barang) {
                $this->migrateItem($barang);
            }

            $this->addLog("Completed category: {$categoryName} ({$barangs->count()} items)");
        }
    }

    private function migrateByUnits()
    {
        foreach ($this->selectedUnits as $unitName) {
            $this->addLog("Migrating unit: {$unitName}");

            $barangs = Barang::where('satuan', $unitName)->get();

            foreach ($barangs as $barang) {
                $this->migrateItem($barang);
            }

            $this->addLog("Completed unit: {$unitName} ({$barangs->count()} items)");
        }
    }

    private function migrateItem($barang)
    {
        // Check if item already exists
        $existingItem = InvtItem::where('item_code', $barang->kode)->first();
        if ($existingItem) {
            $this->addLog("Skipping existing item: {$barang->kode}");
            return;
        }

        // Find or create category
        $category = $this->findOrCreateCategory($barang->kategori);

        // Find or create unit
        $unit = $this->findOrCreateUnit($barang->satuan);

        // Create item
        $item = InvtItem::create([
            'company_id' => 1,
            'item_category_id' => $category->item_category_id,
            'item_unit_id' => $unit->item_unit_id,
            'item_name' => $barang->nama,
            'item_code' => $barang->kode,
            'item_barcode' => $barang->kode_barcode ?: $barang->kode,
            'item_status' => 1,
            'item_default_quantity' => $barang->gudang ?? 0,
            'item_unit_price' => $barang->harga_toko ?? 0,
            'item_unit_cost' => $barang->hpp ?? 0,
            'item_remark' => $barang->ket,
            'data_state' => 0,
            'branch_id' => 1,
            'created_id' => auth()->id(),
            'updated_id' => auth()->id(),
        ]);

        // Create related records
        $this->createBarcodes($item, $barang);
        $this->createPackages($item, $barang);
        $this->createStock($item, $barang);

        $this->addLog("Migrated item: {$barang->nama}");
    }

    private function findOrCreateCategory($categoryName)
    {
        if (!$categoryName) {
            $categoryName = 'Uncategorized';
        }

        $category = InvtItemCategory::where('item_category_name', $categoryName)->first();

        if (!$category) {
            $category = InvtItemCategory::create([
                'company_id' => 1,
                'item_category_name' => $categoryName,
                'item_category_code' => strtoupper(substr($categoryName, 0, 3)),
                'item_category_remark' => '',
                'margin_percentage' => '0',
                'data_state' => 0,
                'branch_id' => 1,
                'created_id' => auth()->id(),
                'updated_id' => auth()->id(),
            ]);
        }

        return $category;
    }

    private function findOrCreateUnit($unitName)
    {
        if (!$unitName) {
            $unitName = 'PCS';
        }

        $unit = InvtItemUnit::where('item_unit_name', $unitName)->first();

        if (!$unit) {
            $unit = InvtItemUnit::create([
                'company_id' => 1,
                'item_unit_name' => $unitName,
                'item_unit_code' => strtoupper(substr($unitName, 0, 3)),
                'item_unit_remark' => '',
                'data_state' => 0,
                'branch_id' => 1,
                'created_id' => auth()->id(),
                'updated_id' => auth()->id(),
            ]);
        }

        return $unit;
    }

    private function createBarcodes($item, $barang)
    {
        $barcodes = [];

        if ($barang->kode_barcode) {
            $barcodes[] = $barang->kode_barcode;
        }
        if ($barang->kode_barcode2) {
            $barcodes[] = $barang->kode_barcode2;
        }
        if ($barang->kode_barcode3) {
            $barcodes[] = $barang->kode_barcode3;
        }

        foreach ($barcodes as $barcode) {
            InvtItemBarcode::create([
                'company_id' => 1,
                'item_id' => $item->item_id,
                'item_unit_id' => $item->item_unit_id,
                'item_barcode' => $barcode,
                'data_state' => 0,
                'branch_id' => 1,
                'created_id' => auth()->id(),
                'updated_id' => auth()->id(),
            ]);
        }
    }

    private function createPackages($item, $barang)
    {
        $packages = [];

        // Main package (satuan, isi)
        if ($barang->satuan && $barang->isi) {
            $packages[] = [
                'unit_name' => $barang->satuan,
                'quantity' => $barang->isi,
                'price' => $barang->harga_toko ?? 0,
                'cost' => $barang->hpp ?? 0,
            ];
        }

        // Package 2 (satuan2, isi2)
        if ($barang->satuan2 && $barang->isi2) {
            $packages[] = [
                'unit_name' => $barang->satuan2,
                'quantity' => $barang->isi2,
                'price' => $barang->harga_toko2 ?? 0,
                'cost' => $barang->hpp ?? 0,
            ];
        }

        // Package 3 (satuan3, isi3)
        if ($barang->satuan3 && $barang->isi3) {
            $packages[] = [
                'unit_name' => $barang->satuan3,
                'quantity' => $barang->isi3,
                'price' => $barang->harga_toko3 ?? 0,
                'cost' => $barang->hpp ?? 0,
            ];
        }

        $order = 1;
        foreach ($packages as $packageData) {
            $packageUnit = $this->findOrCreateUnit($packageData['unit_name']);

            InvtItemPackge::create([
                'company_id' => 1,
                'item_id' => $item->item_id,
                'item_unit_id' => $packageUnit->item_unit_id,
                'item_category_id' => $item->item_category_id,
                'item_default_quantity' => $packageData['quantity'],
                'margin_percentage' => '0',
                'item_unit_price' => $packageData['price'],
                'item_unit_cost' => $packageData['cost'],
                'order' => $order++,
                'data_state' => 0,
                'branch_id' => 1,
                'created_id' => auth()->id(),
                'updated_id' => auth()->id(),
            ]);
        }
    }

    private function createStock($item, $barang)
    {
        $stocks = [];

        // Gudang stock
        if ($barang->gudang > 0) {
            $stocks[] = [
                'warehouse_id' => 1,
                'quantity' => $barang->gudang,
            ];
        }

        // Toko stock
        if ($barang->toko > 0) {
            $stocks[] = [
                'warehouse_id' => 1,
                'quantity' => $barang->toko,
            ];
        }

        foreach ($stocks as $stockData) {
            InvtItemStock::create([
                'company_id' => 1,
                'warehouse_id' => $stockData['warehouse_id'],
                'item_id' => $item->item_id,
                'item_unit_id' => $item->item_unit_id,
                'item_category_id' => $item->item_category_id,
                'last_balance' => $stockData['quantity'],
                'last_update' => now(),
                'data_state' => 0,
                'branch_id' => 1,
                'created_id' => auth()->id(),
                'updated_id' => auth()->id(),
            ]);
        }
    }

    private function addLog($message)
    {
        $this->migrationLog[] = now()->format('H:i:s') . ' - ' . $message;
    }

    public function render()
    {
        return view('livewire.selective-migrate');
    }
}
