<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\EmergencyDataHistory;
use Illuminate\Support\Carbon;

class EmergencyData extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
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

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function history()
    {
        return $this->hasMany(EmergencyDataHistory::class ,'emergency_data_id');
    }
}
