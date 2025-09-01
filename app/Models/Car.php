<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'registration_number', 'brand', 'model', 'year', 'color', 'mileage',
        'fuel_type', 'tank_capacity', 'chassis_number', 'engine_number','category_id',
        'insurance_expiry', 'technical_check_expiry', 'status', 'photo'
    ];

     public function driver() {
        return $this->hasOne(Driver::class, 'assigned_car_id');
    }

    public function expenses() {
        return $this->hasMany(Expense::class);
    }

    public function maintenances() {
        return $this->hasMany(Maintenance::class);
    }

    public function refuelings() {
        return $this->hasMany(Refueling::class);
    }

    public function insurances() {
        return $this->hasMany(Insurance::class);
    }

     public function category()
    {
        return $this->belongsTo(CarCategory::class,'category_id');
    }
}
