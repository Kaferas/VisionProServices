<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'ocpos_customers';

    protected $primaryKey = 'customer_id';

    protected $hidden = ['modified_by','modified_by','modified_by'];

    protected $fillable = [
        "customer_name","customer_tin","customer_address","customer_phone",
        "customer_file","customer_type","customer_status","customer_vatpayer",
        "created_by","modified_by","customer_id_number","customer_gender","customer_filepath","discount","customer_is_member",
        "second_customer_name","second_customer_id_number","second_customer_gender","second_customer_filepath"
    ];


    public function user() {
        return $this->belongsTo(User::class,'created_by');
    }

    public function scopeActive($query)
    {
        $query->where('customer_status', 0);
    }
}