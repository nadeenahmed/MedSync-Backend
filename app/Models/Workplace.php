<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workplace extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'doctor_id',
        'street',
        'region',
        'country',
        'description',
        'work_days'
    ];

    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }

//      public function workHours()
//     {
//         return $this->hasMany(WorkHour::class);
//     }
}
