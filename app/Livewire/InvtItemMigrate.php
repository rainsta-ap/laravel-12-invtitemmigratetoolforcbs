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

class InvtItemMigrate extends Component
{
    public $totalItems = 0;
    public $migratedItems = 0;
    public $isMigrating = false;
    public $migrationLog = [];
    protected $selectedSource = 'mysql2'; // Hardcoded database source

    public function mount()
    {
        $this->countItems();
    }

    public function countItems()
    {
        try {
            $this->totalItems = Barang::on($this->selectedSource)->count();
        } catch (\Exception $e) {
            $this->addLog('Error counting items: ' . $e->getMessage());
        }
    }

    public function startMigration()
    {
        $this->isMigrating = true;
        $this->migratedItems = 0;
        $this->migrationLog = [];

        try {
            DB::beginTransaction();

            $this->addLog('Starting migration from ' . $this->selectedSource . ' database...');

            $barangs = Barang::on($this->selectedSource)->get();

            foreach ($barangs as $barang) {
                $this->migrateItem($barang);
                $this->migratedItems++;
            }

            DB::commit();
            $this->addLog('Migration completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addLog('Migration failed: ' . $e->getMessage());
        }

        $this->isMigrating = false;
    }

    private function migrateItem($barang)
    {
        // Find or create category
        $category = $this->findOrCreateCategory($barang->kategori);

        // Find or create unit
        $unit = $this->findOrCreateUnit($barang->satuan);

        // Create item
        $item = InvtItem::create([
            'company_id' => 1, // Assuming default company
            'item_category_id' => $category->item_category_id,
            'item_unit_id' => $unit->item_unit_id,
            'item_name' => $barang->nama,
            'item_code' => $barang->kode,
            'item_barcode' => $barang->kode_barcode ?: $barang->kode,
            'item_status' => 1, // Active
            'item_default_quantity' => $barang->gudang ?? 0,
            'item_unit_price' => $barang->harga_toko ?? 0,
            'item_unit_cost' => $barang->hpp ?? 0,
            'item_remark' => $barang->ket,
            'data_state' => 0,            'branch_id' => 1,            'created_id' => auth()->id(),
            'updated_id' => auth()->id(),
        ]);

        // Create related records
        $this->createBarcodes($item, $barang);
        $this->createPackages($item, $barang);
        $this->createStock($item, $barang);

        $this->addLog('Migrated item: ' . $barang->nama);
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
            $this->addLog('Created category: ' . $categoryName);
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
            $this->addLog('Created unit: ' . $unitName);
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

        if (!empty($barcodes)) {
            $this->addLog('Created ' . count($barcodes) . ' barcodes for item: ' . $barang->nama);
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

        if (!empty($packages)) {
            $this->addLog('Created ' . count($packages) . ' packages for item: ' . $barang->nama);
        }
    }

    private function createStock($item, $barang)
    {
        $stocks = [];

        // Gudang stock
        if ($barang->gudang > 0) {
            $stocks[] = [
                'warehouse_id' => 1, // Default warehouse for gudang
                'quantity' => $barang->gudang,
            ];
        }

        // Toko stock
        if ($barang->toko > 0) {
            $stocks[] = [
                'warehouse_id' => 1, // Same warehouse for toko (since only warehouse 1 exists)
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

        if (!empty($stocks)) {
            $this->addLog('Created ' . count($stocks) . ' stock entries for item: ' . $barang->nama);
        }
    }

    private function addLog($message)
    {
        $this->migrationLog[] = now()->format('H:i:s') . ' - ' . $message;
    }

    public function render()
    {
        return view('livewire.invt-item-migrate');
    }
}
