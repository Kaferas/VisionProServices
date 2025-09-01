<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $table ="ocpos_product_supplies";

    protected $primaryKey = 'supply_id';

    protected $fillable = [
        'title','supply_code','supply_status',
        'supply_type','created_by','modified_by',
        'confirmed_or_rejected_by','confirmed_or_rejected_at'
    ];

    public function details(){
        return $this->hasMany(SupplyDetail::class,'supply_code');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function modified(){
        return $this->belongsTo(User::class,'modified_by');
    }

    public function confirmed(){
        return $this->belongsTo(User::class,'confirmed_or_rejected_by');
    }
}
