<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockFlow extends Model
{
    use HasFactory;

    protected $table = 'ocpos_stock_flow_movements';


    protected $fillable = [
        "mov_ref_code","mov_ref_product_code","mov_type",
        "mov_item_previous_qty","mov_item_quantity","mov_item_price",
        "mov_created_by","mov_obr_status","mov_obr_sent_date"
    ];


    public function user(){
        return $this->belongsTo(User::class,'mov_created_by');
    }
}
