<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorApprovalRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'request_status',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
