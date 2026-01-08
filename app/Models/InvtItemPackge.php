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
        'item_category_id',
        'item_unit_id',
        'item_packge_name',
        'item_packge_code',
        'item_packge_quantity',
        'item_packge_status',
        'item_packge_remark',
        'data_state',
        'updated_id',
        'created_id',
        'branch_id',
        'uuid',
    ];

    protected $casts = [
        'item_packge_quantity' => 'decimal:2',
        'item_packge_status' => 'integer',
        'data_state' => 'integer',
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
