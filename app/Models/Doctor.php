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
        'years_of_experience',
        'medical_degree',
        'university_id',
        'speciality_id',
        'medical_degree_id',
        'medical_board_organization',
        'licence_information',
        'phone',
        //'profile_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function speciality() {
        return $this->belongsTo(Specialities::class);
    }

    public function workPlace()
    {
        return $this->hasMany(Workplace::class);
    }

    public function approvalRequests()
    {
        return $this->hasMany(DoctorApprovalRequest::class);
    }
}
