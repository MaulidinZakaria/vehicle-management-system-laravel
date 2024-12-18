<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RentalCompany extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'address']);
    }
}
