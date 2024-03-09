<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistoryImage extends Model
{
    use HasFactory;
    protected $fillable = ['medical_history_id', 'image_path'];

    public function medicalHistory()
    {
        return $this->belongsTo(MedicalHistory::class, 'medical_history_id');
    }
}
