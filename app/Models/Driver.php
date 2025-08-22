<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
     protected $fillable = [
        'name', 'phone', 'email', 'license_number', 'license_expiry', 'assigned_car_id', 'photo'
    ];

    public function car(){
        return $this->hasOne(Car::class, 'id');
    }
}
