<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtItemBarcode extends Model
{
    use SoftDeletes;

    protected $table = 'invt_item_barcode';
    protected $primaryKey = 'item_barcode_id';

    protected $fillable = [
        'company_id',
        'item_id',
        'item_unit_id',
        'item_packge_id',
        'item_barcode',
        'created_id',
        'updated_id',
        'data_state',
        'branch_id',
        'uuid',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'item_id' => 'integer',
        'item_unit_id' => 'integer',
        'item_packge_id' => 'integer',
        'data_state' => 'integer',
        'branch_id' => 'integer',
    ];

    public function item()
    {
        return $this->belongsTo(InvtItem::class, 'item_id', 'item_id');
    }

    public function unit()
    {
        return $this->belongsTo(InvtItemUnit::class, 'item_unit_id', 'item_unit_id');
    }

    public function package()
    {
        return $this->belongsTo(InvtItemPackge::class, 'item_packge_id', 'item_packge_id');
    }
}