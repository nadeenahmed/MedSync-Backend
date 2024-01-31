<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightHeightChange extends Model
{
    use HasFactory;
    protected $fillable = [
        'emergency_data_id',
        'weight',
        'height',
        'time',
        'date',
    ];

    public function emergencyData()
    {
        return $this->belongsTo(EmergencyData::class);
    }
}
