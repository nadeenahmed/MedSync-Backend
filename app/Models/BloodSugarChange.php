<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodSugarChange extends Model
{
    use HasFactory;
    protected $fillable = [
        'emergency_data_id',
        'blood_sugar',
        'time',
        'date',
    ];

    public function emergencyData()
    {
        return $this->belongsTo(EmergencyData::class);
    }
}
