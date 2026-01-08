<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvtItemCategory extends Model
{
    protected $table = 'invt_item_category';
    protected $primaryKey = 'item_category_id';

    protected $fillable = [
        'company_id',
        'item_category_name',
        'item_category_code',
        'item_category_status',
        'item_category_remark',
        'data_state',
        'updated_id',
        'created_id',
        'branch_id',
        'uuid',
    ];

    protected $casts = [
        'item_category_status' => 'integer',
        'data_state' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(InvtItem::class, 'item_category_id', 'item_category_id');
    }
}
