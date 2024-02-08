<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;
    protected $table = 'medications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
    ];

    public function medicalHistories()
    {
        return $this->belongsToMany(MedicalHistory::class,'medication_medical_history', 'medication_id', 'medical_history_id');
    }
}
