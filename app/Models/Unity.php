<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unity extends Model
{
    use HasFactory;

    protected $table = 'ocpos_unities';

    protected $primaryKey = 'unity_id';

    protected $fillable = [
        'title','created_by'
    ];

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'item_category');
    }
}
