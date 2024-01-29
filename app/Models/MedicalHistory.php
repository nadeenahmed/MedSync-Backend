<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Specialities;
use App\Models\Patient;
use App\Models\LabTestMedicalHistory;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medical_speciality_id',
        'diagnosis',
        'prescription',
        'reports',
        'files',
        'notes',
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
        return $this->belongsToMany(LabTestMedicalHistory::class, 'lab_test_medical_histories');
    }
}
