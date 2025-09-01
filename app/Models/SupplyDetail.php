<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyDetail extends Model
{
    use HasFactory;

    protected $table ="ocpos_supplies_details";

    protected $fillable = [
        'product_name','ref_supply_code','ref_product_code',
        'supply_type','item_quantity','modified_by',
        'purchase_price'
    ];

    public function supply(){
        return $this->belongsTo(Supply::class,'ref_supply_code');
    }

    public function user(){
        return $this->belongsTo(User::class,'modified_by');
    }

}
