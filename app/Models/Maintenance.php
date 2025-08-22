<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
     protected $fillable = ['car_id', 'service_type', 'cost', 'date', 'mileage_at_service', 'garage', 'notes'];

    public function car() {
        return $this->belongsTo(Car::class);
    }
}
