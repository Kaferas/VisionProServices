<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        'car_id', 'provider', 'policy_number', 'cost', 'start_date', 'end_date', 'active', 'document_scan'
    ];

    public function car() {
        return $this->belongsTo(Car::class);
    }
}
