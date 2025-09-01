<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarCategory extends Model
{
    protected $primaryKey = 'category_id';
    protected $fillable = ['title', 'description'];
}
