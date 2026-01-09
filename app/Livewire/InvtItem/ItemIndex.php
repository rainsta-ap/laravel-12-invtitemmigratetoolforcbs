<?php 
namespace App\Livewire\InvtItem;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\InvtItem;

class ItemIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function deleteItem($itemId)
    {
        DB::transaction(function () use ($itemId) {

            DB::table('invt_item_barcode')->where('item_id', $itemId)->delete();
            DB::table('invt_item_packge')->where('item_id', $itemId)->delete();
            DB::table('invt_item_stock')->where('item_id', $itemId)->delete();

            DB::table('invt_item')->where('item_id', $itemId)->delete();
        });

        session()->flash('success', 'Item deleted successfully.');
    }

    public function render()
    {
        return view('livewire.invt-item.item-index', [
            'items' => InvtItem::with(['category', 'unit'])
                ->orderBy('item_id', 'desc')
                ->paginate(10),
        ]);
    }
}
