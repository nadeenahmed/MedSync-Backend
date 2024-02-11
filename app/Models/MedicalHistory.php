<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Specialities;
use App\Models\Patient;
use App\Models\LabTest;
use App\Models\Medication;


class MedicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medical_speciality_id',
        'diagnosis_id',
        'notes',
        'by_who',
    ];

    
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function speciality()
    {
        return $this->belongsTo(Specialities::class);
    }

    public function labTests()
    {
        return $this->belongsToMany(LabTest::class , 'lab_test_medical_history', 'lab_test_id', 'medical_history_id');
    }
    public function diagnosis()
    {
        return $this->belongsToMany(Diagnoses::class , 'diagnosis_medical_history', 'diagnosis_id', 'medical_history_id');
    }
    public function medications()
    {
        return $this->belongsToMany(Medication::class , 'medication_medical_history', 'medication_id', 'medical_history_id');
    }
}
