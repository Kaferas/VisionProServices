<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SortieDetail extends Model
{
    use HasFactory;

    protected $table = "ocpos_product_sortie_items";

    protected $fillable = [
        "ref_sortie_code","ref_product_code","item_sortie_status",
        "sortie_type","item_sortie_name","item_sortie_quantity",
        "item_sortie_price"
    ];
}
