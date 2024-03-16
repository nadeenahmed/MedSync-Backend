<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalCollege extends Model
{
    use HasFactory;
    protected $table = 'medical_colleges';
    protected $fillable = [
        'arabic_name',
        'english_name',
    ];
}
