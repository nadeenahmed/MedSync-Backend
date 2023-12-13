<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;

class EmergencyData extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'high_blood_pressure',
        'low_blood_pressure',
        'sugar',
        'weight',
        'height',
        'blood_type',
        'chronic_diseases_bad_habits',
    ];
   
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
