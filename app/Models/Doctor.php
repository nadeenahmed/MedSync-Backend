<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender',
        'years_of_exp',
        'clinic_address',
        'clinic_phone',
        'medical_speciality',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
