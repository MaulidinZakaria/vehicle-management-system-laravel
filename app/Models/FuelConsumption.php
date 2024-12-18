<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FuelConsumption extends Model
{
    use HasFactory, LogsActivity;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['vehicle_id', 'date', 'fuel_volume', 'distance']);
    }
}
