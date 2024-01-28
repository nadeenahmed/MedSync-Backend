<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany(LabTests::class, 'lab_test_medical_history');
    }
}
