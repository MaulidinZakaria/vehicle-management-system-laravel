<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMaintenance extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_id',
        'maintenance_date',
        'description',
        'cost',
        'place',
        'mileage',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
