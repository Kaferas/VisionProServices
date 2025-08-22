<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
      protected $fillable = ['car_id', 'type', 'amount', 'description', 'date', 'invoice_number', 'payment_method'];

    public function car() {
        return $this->belongsTo(Car::class);
    }
}
