<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'office_type',
        'role',
        'is_approver'
    ];

    public function bookingVehicle()
    {
        return $this->hasMany(BookingVehicle::class);
    }
}
