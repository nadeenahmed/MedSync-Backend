<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalDegree extends Model
{
    use HasFactory;
    protected $table = 'medical_degrees';
    protected $fillable = [
        'arabic_name',
        'english_name',
    ];
}
