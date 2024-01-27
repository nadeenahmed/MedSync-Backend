<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmergencyData;

class EmergencyDataHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'emergency_data_id',
        'systolic',
        'diastolic',
        'blood_sugar',
        'weight',
        'height',
        'blood_type',
        'chronic_diseases_bad_habits',
        'bloodPressure_change_date',
        'bloodSugar_change_date',
        'weightHeight_change_date',
    ];

    public function emergencyData()
    {
        return $this->belongsTo(EmergencyData::class , 'emergency_data_id');
    }
}
