<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'rental_company_id',
        'owner_type',
        'vehicle_type',
        'license_plate',
        'brand',
        'model',
        'fuel_type',
        'status',
        'year',
    ];
}
