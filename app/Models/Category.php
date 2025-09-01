<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "ocpos_categories";

    protected $primaryKey = "category_id";

    protected $fillable = [
        "title",'created_by','categorie_has_bon','store_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    // public function store()
    // {
    //     return $this->belongsTo(Store::class, 'store_id');
    // }

    public function products()
    {
        return $this->hasMany(Product::class,'item_category');
    }
}
