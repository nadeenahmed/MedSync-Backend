<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTests extends Model
{
    use HasFactory;
    protected $table = 'lab_tests';
    protected $fillable = [
        'arabic_name',
        'english_name',
    ];

    public function medicalHistories()
    {
        return $this->belongsToMany(MedicalHistory::class,'lab_test_medical_history', 'lab_test_id', 'medical_history_id');
    }
}
