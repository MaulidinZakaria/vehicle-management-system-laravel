<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelConsumption extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_id',
        'date',
        'fuel_volume',
        'distance',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
