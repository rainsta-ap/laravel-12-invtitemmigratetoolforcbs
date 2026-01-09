<?php

namespace App\Livewire\InvtItem;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\{
    InvtItem,
    InvtItemBarcode,
    InvtItemCategory,
    InvtItemUnit
};

class ItemEdit extends Component
{
    public InvtItem $item;

    // Main tab
    public $item_name;
    public $item_barcode;
    public $item_category_id;

    // Unit tab
    public $item_unit_id;
    public $item_default_quantity;
    public $item_unit_price;
    public $item_unit_cost;

    // Tabs state
    public $tab = 'main';

    public function mount(InvtItem $item)
    {
        $this->item = $item;

        $this->item_name = $item->item_name;
        $this->item_barcode = $item->item_barcode;
        $this->item_category_id = $item->item_category_id;

        $this->item_unit_id = $item->item_unit_id;
        $this->item_unit_price = $item->item_unit_price;
        $this->item_unit_cost = $item->item_unit_cost;
        $this->item_default_quantity = $item->item_default_quantity;
    }

    // Save main data and switch tab
    public function saveMain()
    {
        $this->validate([
            'item_name' => 'required|string|max:255',
            'item_category_id' => 'required|integer|exists:invt_item_category,item_category_id',
            'item_barcode' => 'nullable|string|max:255',
        ]);

        $this->item->update([
            'item_name' => $this->item_name,
            'item_category_id' => $this->item_category_id,
            'item_barcode' => $this->item_barcode,
        ]);

        session()->flash('success', 'Item main data updated successfully.');
        $this->tab = 'unit';
    }

    // Save unit data and default quantity
    public function saveUnit()
    {
        $this->validate([
            'item_unit_id' => 'required|integer|exists:invt_item_unit,item_unit_id',
            'item_default_quantity' => 'nullable|numeric',
            'item_unit_price' => 'nullable|numeric',
            'item_unit_cost' => 'nullable|numeric',
        ]);

        DB::transaction(function () {
            $this->item->update([
                'item_unit_id' => $this->item_unit_id,
                'item_unit_price' => $this->item_unit_price,
                'item_unit_cost' => $this->item_unit_cost,
                'item_default_quantity' => $this->item_default_quantity,
            ]);

            // Update barcode for unit
            InvtItemBarcode::updateOrCreate(
                ['item_id' => $this->item->item_id],
                ['item_barcode' => $this->item_barcode, 'item_unit_id' => $this->item_unit_id]
            );
        });

        session()->flash('success', 'Item unit data updated successfully.');
    }

    public function render()
    {
        return view('livewire.invt-item.item-edit', [
            'categories' => InvtItemCategory::all(),
            'units' => InvtItemUnit::all(),
        ]);
    }
}
