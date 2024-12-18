<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Vehicle extends Model
{
    use HasFactory, LogsActivity;
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

    public function rentalCompany()
    {
        return $this->belongsTo(RentalCompany::class);
    }

    public function fuelConsumption()
    {
        return $this->hasMany(FuelConsumption::class);
    }

    public function vehicleMaintenance()
    {
        return $this->hasMany(VehicleMaintenance::class);
    }

    public function bookingVehicles()
    {
        return $this->hasMany(BookingVehicle::class);
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['rental_company_id', 'owner_type', 'vehicle_type', 'license_plate', 'brand', 'model', 'fuel_type', 'status', 'year']);
    }
}
