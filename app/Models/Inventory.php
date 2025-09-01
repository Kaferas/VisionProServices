<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = "ocpos_product_inventories";

    protected $primaryKey = "inventory_id";

    protected $fillable = [
        "inventory_title","inventory_code","inventory_status",
        "created_by","modified_by","confirmed_by","confirmed_at"
    ];

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function confirmed(){
        return $this->belongsTo(User::class,'confirmed_by');
    }

    public function details(){
        return $this->hasMany(InventoryDetail::class,'inventory_code');
    }
}
