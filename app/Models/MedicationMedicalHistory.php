<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MedicationMedicalHistory extends Pivot
{
    protected $table = 'medication_medical_history';
    use HasFactory;
}
