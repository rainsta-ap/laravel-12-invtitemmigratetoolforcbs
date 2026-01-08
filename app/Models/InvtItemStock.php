<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtItemStock extends Model
{
    use SoftDeletes;

    protected $table = 'invt_item_stock';
    protected $primaryKey = 'item_stock_id';

    protected $fillable = [
        'company_id',
        'warehouse_id',
        'item_id',
        'item_unit_id',
        'item_category_id',
        'rack_line',
        'rack_column',
        'last_balance',
        'last_update',
        'data_state',
        'updated_id',
        'created_id',
        'branch_id',
        'uuid',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'warehouse_id' => 'integer',
        'item_id' => 'integer',
        'item_unit_id' => 'integer',
        'item_category_id' => 'integer',
        'rack_line' => 'integer',
        'rack_column' => 'integer',
        'data_state' => 'integer',
        'branch_id' => 'integer',
        'last_balance' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(InvtItem::class, 'item_id', 'item_id');
    }

    public function unit()
    {
        return $this->belongsTo(InvtItemUnit::class, 'item_unit_id', 'item_unit_id');
    }

    public function category()
    {
        return $this->belongsTo(InvtItemCategory::class, 'item_category_id', 'item_category_id');
    }
}