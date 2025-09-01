<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "ocpos_products";

    protected $primaryKey = "item_id";

    protected $fillable = [
        "item_name","item_codebar","item_sellprice","item_costprice",
        "item_corprice","item_quantity","item_tva","item_tc",
        "item_pf","item_status","item_type","item_isSellable",
        "item_category","item_unity","created_by","modified_by"
    ];

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function category(){
        return $this->belongsTo(Category::class,'item_category');
    }

    public function unity(){
        return $this->belongsTo(Unity::class,'item_unity');
    }

    public function sales(){
        return $this->hasMany(InvoiceItems::class,'invoice_items_product_code','item_codebar');
    }


    // public function store()
    // {
    //     return $this->hasOneThrough(
    //         Store::class,
    //         Category::class,
    //         'id',       // Foreign key on categories table...
    //         'id',       // Foreign key on stores table...
    //         'category_id', // Local key on products table...
    //         'store_id'     // Local key on categories table...
    //     );
    // }
}
