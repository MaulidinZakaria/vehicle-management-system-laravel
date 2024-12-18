<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Approval extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'booking_id',
        'approver_id',
        'approver_level',
        'status',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function booking()
    {
        return $this->belongsTo(BookingVehicle::class, 'booking_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['booking_id', 'approver_id', 'approver_level', 'status']);
    }
}
