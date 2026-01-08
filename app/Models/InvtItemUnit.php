<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvtItemUnit extends Model
{
    protected $table = 'invt_item_unit';
    protected $primaryKey = 'item_unit_id';

    protected $fillable = [
        'company_id',
        'item_unit_code',
        'item_unit_name',
        'item_unit_remark',
        'data_state',
        'updated_id',
        'created_id',
        'branch_id',
        'uuid',
    ];

    protected $casts = [
        'data_state' => 'integer',
        'branch_id' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(InvtItem::class, 'item_unit_id', 'item_unit_id');
    }
}
