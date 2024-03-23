<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $fillable = [
        'arabic_name',
        'english_name',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
