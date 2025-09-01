<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDetail extends Model
{
    use HasFactory;

    protected $table = "ocpos_product_inventory_items";

    protected $fillable = [
        "ref_inventory_code","ref_product_code","item_inventory_status",
        "item_inventory_name","item_system_quantity",
        "item_physic_quantity","item_inventory_price"
    ];

    public function inventory(){
        return $this->belongsTo(Inventory::class,'ref_inventory_code');
    }
}
