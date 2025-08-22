<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refueling extends Model
{
    protected $fillable = ['car_id', 'liters', 'price_per_liter', 'total_cost', 'mileage', 'date', 'station'];

    public function car() {
        return $this->belongsTo(Car::class);
    }
}
