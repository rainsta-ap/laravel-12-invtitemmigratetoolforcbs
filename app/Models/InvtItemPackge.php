<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvtItemPackge extends Model
{
    protected $table = 'invt_item_packge';
    protected $primaryKey = 'item_packge_id';

    protected $fillable = [
        'company_id',
        'item_id',
        'item_unit_id',
        'item_category_id',
        'item_default_quantity',
        'margin_percentage',
        'item_unit_price',
        'item_unit_cost',
        'order',
        'data_state',
        'created_id',
        'updated_id',
        'item_unit_ppn',
        'item_unit_cost_after_ppn',
        'item_unit_discount',
        'item_unit_cost_final',
        'branch_id',
        'uuid',
    ];

    protected $casts = [
        'item_default_quantity' => 'integer',
        'order' => 'integer',
        'data_state' => 'integer',
        'branch_id' => 'integer',
        'item_unit_price' => 'decimal:2',
        'item_unit_cost' => 'decimal:2',
        'item_unit_ppn' => 'decimal:2',
        'item_unit_cost_after_ppn' => 'decimal:2',
        'item_unit_discount' => 'decimal:2',
        'item_unit_cost_final' => 'decimal:2',
        'margin_percentage' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(InvtItem::class, 'item_id', 'item_id');
    }

    public function category()
    {
        return $this->belongsTo(InvtItemCategory::class, 'item_category_id', 'item_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(InvtItemUnit::class, 'item_unit_id', 'item_unit_id');
    }
}
