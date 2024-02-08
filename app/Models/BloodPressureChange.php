<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodPressureChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_data_id',
        'systolic',
        'diastolic',
        'time',
        'date',
    ];

    public function emergencyData()
    {
        return $this->belongsTo(EmergencyData::class);
    }

    
}
