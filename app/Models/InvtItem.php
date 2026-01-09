<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\InvtItemStock;
use App\Models\InvtItemBarcode;
use App\Models\InvtItemPackge;

class InvtItem extends Model
{
    use SoftDeletes;

    protected $table = 'invt_item';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'company_id',
        'item_category_id',
        'item_unit_id',
        'item_name',
        'item_code',
        'item_barcode',
        'item_status',
        'item_default_quantity',
        'item_unit_price',
        'item_unit_cost',
        'item_remark',
        'data_state',
        'updated_id',
        'created_id',
        'branch_id',
        'uuid',
        'deleted_id',
        'tax_ppn_percentage_purchase',
        'tax_ppn_percentage_sales',
        'tax_ppn_amount_purchase',
        'tax_ppn_amount_sales',
        'discount_percentage_purchase',
        'discount_percentage_sales',
        'discount_amount_purchase',
        'discount_amount_sales',
        'profit',
    ];

    protected $casts = [
        'item_default_quantity' => 'decimal:2',
        'item_unit_price' => 'decimal:2',
        'item_unit_cost' => 'decimal:2',
        'tax_ppn_percentage_purchase' => 'decimal:2',
        'tax_ppn_percentage_sales' => 'decimal:2',
        'tax_ppn_amount_purchase' => 'decimal:2',
        'tax_ppn_amount_sales' => 'decimal:2',
        'discount_percentage_purchase' => 'decimal:2',
        'discount_percentage_sales' => 'decimal:2',
        'discount_amount_purchase' => 'decimal:2',
        'discount_amount_sales' => 'decimal:2',
        'profit' => 'decimal:2',
        'item_status' => 'integer',
        'data_state' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(InvtItemCategory::class, 'item_category_id', 'item_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(InvtItemUnit::class, 'item_unit_id', 'item_unit_id');
    }

    public function packges()
    {
        return $this->hasMany(InvtItemPackge::class, 'item_id', 'item_id');
    }

    
       public function stocks()
    {
        return $this->hasMany(InvtItemStock::class, 'item_id', 'item_id');
    }

    public function barcodes()
    {
        return $this->hasMany(InvtItemBarcode::class, 'item_id', 'item_id');
    }

    public function packages()
    {
        return $this->hasMany(InvtItemPackge::class, 'item_id', 'item_id');
    }
}
