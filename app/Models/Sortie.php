<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sortie extends Model
{
    use HasFactory;

    protected $table = "ocpos_product_sorties";

    protected $primaryKey = "sortie_id";

    protected $fillable = [
        "sortie_title","sortie_code","sortie_status",
        "sortie_type","created_by","modified_by",
        "confirmed_by","confirmed_at"
    ];

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function confirmed(){
        return $this->belongsTo(User::class,'confirmed_by');
    }
}
